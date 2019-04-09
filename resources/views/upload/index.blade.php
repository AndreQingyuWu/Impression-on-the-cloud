@extends('layouts.default')

@section('content')
    <form id="uploadImage" method="POST" action="{{ route('upload') }}" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div>
            @foreach($cloudDisks as $cloudDisk)
                <label class="image-checkbox" title="{{ $cloudDisk->name }}">
                    <img src="{{ app('url')->asset('storage/'.$cloudDisk->logo) }}"
                         class="img-thumbnail img-cloud-small"/>
                    <input type="checkbox" name="clouds[]" value="{{ $cloudDisk->id }}" {{ $cloudDisk->checked }} checked/>
                </label>
            @endforeach
        </div>
        <div class="border-top my-1"></div>
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <img class="card-img-top" id="preview"
                         src="{{ app('url')->asset('storage/images/DybXenYDKxbBgOLHDCaw0qPosm1vKMyIRRsq65Ks.jpeg') }}">
                    <div class="card-body">
                        <div class="form-group">
                            <input type="text" class="form-control" id="imageName" name="imageName" placeholder="图片名称">
                        </div>
                        <div class="form-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="imageFile" name="image" accept="image/gif,image/jpeg,image/jpg,image/png,image/svg">
                                <label class="custom-file-label" for="imageFile">选择图片</label>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary" id="uploadImageButton">上传图片</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

@section('index', 'active')