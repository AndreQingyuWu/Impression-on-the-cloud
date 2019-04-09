<?php

namespace App\Http\Controllers;

use App\Photo;
use App\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Nexmo\Response;

/**
 * 搜索模块
 * Class SearchController
 * @package App\Http\Controllers
 */
class SearchController extends Controller
{
    /**
     * 显示搜索页面
     * @param int $type
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index($type = 1)
    {
        // 多对多关系 完成图片上传及向量存储
        if (!Auth::check()) {
            session()->flash('danger', '请先登陆');
            return redirect()->route('login');
        }
        $cloudDisks = Auth::user()->cloudDisks;
        return view('search.create', compact('cloudDisks', 'type'));
    }

    /**
     * 接收并处理搜索
     * @param Request $request
     * @return string
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'clouds' => 'required',
            'name' => 'sometimes|required|string',
            'keys' => 'sometimes|array',
            'characteristicValue' => 'sometimes|required|string'
        ]);

        $uploads = Upload::where('user_id', Auth::id())->whereIn('cloud_disk_id', explode(',', $request->clouds))->with('photo', 'cloudDisk')->get();

        $photos = [];

        # 文字搜索
        if ($request->has('name')) {
            $res = [];
            foreach ($uploads as $index => $upload) {
                $photo = $upload->photo;
                $photo['source'] = $upload->cloudDisk['name'];
                $photo['index'] = array_search($request->name, json_decode($photo['keys']));
                if ($photo['index'] !== false) {
                    $res[] = $photo;
                }
            }
            if (!empty($res)) {
                foreach ($res as $key => $value) {
                    $order[$key] = $value['index'];
                }
                array_multisort($order, SORT_ASC, $res);
            }
            return Response()->json($res);
        }

        # 手绘搜索
        if ($request->has('keys')) {
            $res = [];
            // 获取需要对比的图片列表
            foreach ($uploads as $index => $upload) {
                $photo = $upload->photo;
                $photo['source'] = $upload->cloudDisk['name'];
                // 交集越多 匹配度越高
                $count = count(array_intersect($request->keys, json_decode($photo['keys'])));
                if ($count !== 0) {
                    $photo['index'] = $count;
                    $res[] = $photo;
                }
            }
            if (!empty($res)) {
                foreach ($res as $key => $value) {
                    $order[$key] = $value['index'];
                }
                array_multisort($order, SORT_DESC, $res);
            }
            return Response()->json($res);
        }

        # 判断图片搜索
        if ($request->has('characteristicValue')) {
            // 获取需要对比的图片列表
            foreach ($uploads as $index => $upload) {
                $photos[$index] = $upload->photo;
                $photos[$index]['source'] = $upload->cloudDisk['name'];
            }
            ### 存入图片向量到redis,并调用python接口获取结果
            // 存入redis
            $randomName = str_random(10);
            Redis::set($randomName . '_target', serialize(json_encode(array_map('floatval', explode(',', $request->characteristicValue)))));
            if (Redis::set($randomName, serialize(json_encode($photos))) == 'OK') {
                $dir = storage_path('api');
                $output = shell_exec("cd {$dir} && python3 test.py {$randomName}");
                if (trim($output) === 'success') {
                    $res = unserialize(Redis::get($randomName . '_res'));
                    foreach ($res as $key => $value) {
                        $order[$key] = $value['sim'];
                    }
                    array_multisort($order, SORT_DESC, $res);
                    return Response()->json($res);
                } else {
                    return Response()->json(['status' => 'error'], 400);
                }
            } else {
                return Response()->json(['status', 'error'], 400);
            }
        }
    }

}
