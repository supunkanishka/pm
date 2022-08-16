@extends('layouts.app')


@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('tasks.index') }}" title="Go back"> GO BACK </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <hr/>
            <div class="form-group">
                <strong>Name:</strong>
                {{ $task->name }}
            </div>
            <hr/>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Description:</strong>
                {!! $task->description !!}
            </div>
            <hr/>
        </div>
        <?php 
        $count = 1;
        ?>
        @foreach ($task->images as $image)
        <form action="{{ route('images.destroy', $image->id) }}" method="POST">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Image :{{$count}}</strong>
                @csrf
                @method('DELETE')

                <button type="submit" title="delete" class="btn btn-danger">
                    Delete

                </button>
                <hr/>
                <img src="<?php echo asset("$image->path")?>" style="width: 100%"></img>
            </div>
        </div>
        
        <br/>
        <?php 
        $count++;
        ?>
        @endforeach
    </div>
@endsection