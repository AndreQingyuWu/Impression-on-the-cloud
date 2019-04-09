@extends('layouts.default')

@section('content')
    <form action="{{ route('my_cloud') }}" method="POST">
        {{ csrf_field() }}
        <div class="row">
            @foreach($cloudDisks as $cloudDisk)
                <div class="col-md-2 text-center">
                    <label class="image-checkbox" title="{{ $cloudDisk->name }}">
                        <img src="{{ app('url')->asset('storage/'.$cloudDisk->logo) }}"
                             class="img-thumbnail img-cloud"/>
                        <input type="checkbox" name="clouds[]" value="{{ $cloudDisk->id }}" {{ $cloudDisk->checked }}/>
                    </label>
                </div>
            @endforeach
        </div>
        <button type="submit" class="btn btn-primary btn-lg btn-block mt-3">绑定选中的网盘</button>
    </form>
@stop

@section('index', 'active')