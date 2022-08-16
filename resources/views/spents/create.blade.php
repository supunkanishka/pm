@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Name:</strong>
                {{ $task->name }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Description:</strong>
                {!! $task->description !!}
            </div>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('tasks.spents.index',$task->id) }}" title="Go back"> GO BACK</a>
        </div>
    </div>
    <br/>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('tasks.spents.store',$task->id) }}" method="POST" >
        @csrf

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Date:</strong>
                    <input autocomplete="off" class="form-control datepicker" type="text" name="date" placeholder="Date">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Spent Hours: (15mins = 0.25 / 30mins = 0.5 / 45mins = 0.75)</strong>
                    <input type="text" value="{{ old('spent_hours') }}" name="spent_hours" class="form-control" placeholder="Spent Hours">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>

    </form>
@endsection
@section('scripts')

@endsection