@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('tasks.index') }}" title="Go back"> GO BACK </a>
        </div>
    </div>
    <hr/>

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>TASK DETAILS</h2>
            </div>
        </div>
    </div>

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
    </div>

    <hr/>

    @if(count($spents) != 0)

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>SPENT HOURS</h2>
            </div>
        </div>
    </div>



    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-bordered table-responsive-lg">
        <tr>
            <th width="5%">ID</th>
            <th width="15%">Date</th>
            <th width="5%">Spent</th>
            <th width="25%">Action</th>
        </tr>
        @foreach ($spents as $spent)
            <tr>
                <td>{{ $spent->id }}</td>
                <td>{{ $spent->date }}</td>
                <td>{{ $spent->spent_hours }}</td>
                <td>
                    <form action="{{ route('tasks.spents.destroy', [$task->id,$spent->id]) }}" method="POST">

                        <!-- <a href="">
                            Edit
                        </a> -->

                        @csrf
                        @method('DELETE')

                        <button type="submit" title="delete" class="btn btn-danger">
                            Delete

                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>

    {!! $spents->links("pagination::bootstrap-4") !!}



    <hr/>

    @endif

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>ADD HOURS </h2>
            </div>
        </div>
    </div>

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