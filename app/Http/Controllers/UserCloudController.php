<?php

namespace App\Http\Controllers;

use App\CloudDisk;
use App\UserCloud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * 用户云上模块
 * Class UserCloudController
 * @package App\Http\Controllers
 */
class UserCloudController extends Controller
{

    /**
     * 用户(我的云上|绑定网盘页面)
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index()
    {
        // 检测是否登陆
        if (!Auth::check()) {
            session()->flash('danger', '请先登陆!');
            return redirect()->route('login');
        }

        $cloudDisks = CloudDisk::all();
        foreach ($cloudDisks as $key => $cloudDisk) {
            if (UserCloud::where(['user_id' => Auth::id(), 'cloud_disk_id' => $cloudDisk->id])->first()) {
                $cloudDisks[$key]['checked'] = 'checked';
            } else {
                $cloudDisks[$key]['checked'] = '';
            }
        }
        return view('user_cloud', compact('cloudDisks'));
    }

    /**
     * 处理绑定网盘
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            session()->flash('danger', '请先登陆!');
            return redirect()->route('login');
        }
        $clouds = $request->clouds;
        foreach ($clouds as $cloud) {
            UserCloud::firstOrCreate([
                'user_id' => Auth::id(),
                'cloud_disk_id' => $cloud
            ]);
        }
        session()->flash('success', '添加成功,可以开始上传图片了');
        return redirect()->back();
    }
}
