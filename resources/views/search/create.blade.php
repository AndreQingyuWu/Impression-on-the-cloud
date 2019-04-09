@extends('layouts.default')

@section('content')
    <input type="hidden" id="csrf_token" value="{{ csrf_token() }}">
    <div>
        @foreach($cloudDisks as $cloudDisk)
            <label class="image-checkbox" title="{{ $cloudDisk->name }}">
                <img src="{{ app('url')->asset('storage/'.$cloudDisk->logo) }}"
                     class="img-thumbnail img-cloud-small"/>
                <input type="checkbox" name="clouds[]" value="{{ $cloudDisk->id }}" checked/>
            </label>
        @endforeach
    </div>
    <div class="border-top my-1"></div>
    @if($type == 1)
        <div class="row mt-2">
            <div class="col-md-4 offset-1">
                <form id="searchImage1" method="POST" action="{{ route('search') }}">
                    <div class="card">
                        <div class="card-header">
                            以图搜图
                        </div>
                        <img class="card-img-top" id="preview"
                             src="{{ app('url')->asset('storage/images/DybXenYDKxbBgOLHDCaw0qPosm1vKMyIRRsq65Ks.jpeg') }}">
                        <div class="card-body">
                            <div class="form-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="imageFile" name="image"
                                           accept="image/gif,image/jpeg,image/jpg,image/png,image/svg">
                                    <label class="custom-file-label" for="imageFile">选择图片</label>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary" id="searchImageButton1">识别图片</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-4">
                <form id="searchImage2" method="POST" action="{{ route('search') }}">
                    <div class="card">
                        <div class="card-header">
                            文字搜图
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="name">图片名称</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                           placeholder="名称">
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary" id="searchImageButton2">开始搜索</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-2">
                <div class="card">
                    <div class="card-header">
                        绘图搜索
                    </div>
                    <div class="card-body">
                        <a href="2" class="btn btn-primary">绘图搜索</a>
                    </div>
                </div>
            </div>
            @else
                <form id="searchImage3" method="POST" action="{{ route('search') }}">
                    <h3 class="text-center">在线绘图</h3>
                    <canvas id="canvas" width="900" height="600"></canvas>
                    <button type="button" class="btn btn-primary btn-block mt-4" id="searchImageButton3">开始识图</button>
                </form>
            @endif
        </div>
        <div class="border-top my-3"></div>
        <h3 class="text-center">识图结果</h3>
        <div class="row" id="imageResult">
        </div>
        <div style="display: none;">
            <input type="color" id="color"/>
            <div id="demo"></div>
            <input type="range" id="range" min="1" max="10"/>
        </div>
@stop

@section('search', 'active')