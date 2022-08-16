@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit Sprint</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('sprints.index') }}" title="Go back"> GO BACK </a>
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

    <form action="{{ route('sprints.update', $sprint->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Name:</strong>
                    <input type="text" name="name" value="{{ $sprint->name }}" class="form-control" placeholder="Name">
                </div>
            </div>
            
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Project:</strong>
                    <select class="form-control" name="project_id">
                        <option value="">Select Project</option>
                        @foreach ($projects as $key => $value)
                            <option value="{{ $key }}" {{ ( $key == $sprint->project_id) ? 'selected' : '' }}> 
                                {{ $value }} 
                            </option>
                        @endforeach    
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Status:</strong>
                    <select class="form-control" name="status_id">
                        <!-- <option value="">Select Status</option> -->
                        @foreach ($statuses as $key => $value)
                            <option value="{{ $key }}" {{ ( $key == $sprint->status_id) ? 'selected' : '' }}> 
                                {{ $value }} 
                            </option>
                        @endforeach    
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Start Date:</strong>
                    <input autocomplete="off"  class="form-control datepicker" type="text" value="{{ $sprint->start_date }}" name="start_date" placeholder="Start Date">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Due Date:</strong>
                    <input autocomplete="off"  class="form-control datepicker" type="text" value="{{ $sprint->due_date }}" name="due_date" placeholder="Due Date">
                </div>
            </div>
            
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>

    </form>
@endsection
@section('scripts')
<script>
        CKEDITOR.replace('description');
</script>
@endsection