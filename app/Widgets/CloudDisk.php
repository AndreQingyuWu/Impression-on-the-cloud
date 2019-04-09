<?php

namespace App\Widgets;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Widgets\BaseDimmer;

class CloudDisk extends BaseDimmer
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $count = \App\CloudDisk::count();
        $string = '云盘';

        return view('voyager::dimmer', array_merge($this->config, [
            'icon'   => 'voyager-cloud-download',
            'title'  => "{$count} {$string}",
            'text'   => "您有 {$count} {$string} 在数据库中。点击下面的按钮查看所有{$string}。",
            'button' => [
                'text' => '查看所有云盘',
                'link' => route('voyager.cloud-disks.index'),
            ],
            'image' => voyager_asset('images/widget-backgrounds/02.jpg'),
        ]));
    }

    /**
     * Determine if the widget should be displayed.
     *
     * @return bool
     */
    public function shouldBeDisplayed()
    {
        return Auth::user()->can('browse', Voyager::model('User'));
    }
}
