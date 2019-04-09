<?php

namespace App\Http\Controllers;

use App\Photo;
use App\Upload;
use App\UserCloud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * 上传模块
 * Class UploadController
 * @package App\Http\Controllers
 */
class UploadController extends Controller
{
    /**
     * 上传页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index()
    {
        // 多对多关系 完成图片上传及向量存储
        if (!Auth::check()) {
            session()->flash('danger', '请先登陆');
            return redirect()->route('login');
        }
        $cloudDisks = Auth::user()->cloudDisks;
        return view('upload.index', compact('cloudDisks'));
    }

    /**
     * 上传数据接收
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        // 验证表单内容类型 (安全)
        $this->validate($request, [
            'clouds' => 'required',
            'keys' => 'required|array',
            'imageName' => 'required|nullable',
            'image' => 'required|image',
            'characteristicValue' => 'required|string'
        ]);

        // 判断图片是否上传失败
        if (!$request->file('image')->isValid()){
            return Response()->setStatusCode(422)->json('图片上传失败');
        }

        // 图片保存到images目录
        $path = $request->image->store('images', 'public');

        // 将图片信息存入数据库
        $photo = Photo::create([
            'name' => $request->imageName,
            'keys' => json_encode($request->keys),
            'path' => $path,
            'characteristic_value' => json_encode(array_map('floatval', explode(',', $request->characteristicValue)))
        ]);

        //将图片信息绑定到用户云盘下
        foreach ($request->clouds as $cloud){
            Upload::create([
                'user_id' => Auth::id(),
                'cloud_disk_id' => $cloud,
                'photo_id' => $photo->id
            ]);
        }

        return Response()->json(['status'=>'success']);

    }
}
