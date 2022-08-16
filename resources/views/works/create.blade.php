@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Add New Task</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('works.index') }}" title="Go back"> GO BACK</i> </a>
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
    <form action="{{ route('works.store') }}" method="POST" >
        @csrf

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Name:</strong>
                    <input type="text" value="{{ old('name') }}" name="name" class="form-control" placeholder="Name">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Description:</strong>
                    <textarea class="form-control" cols="5" rows="5" name="description"
                        placeholder="description">{{ old('description') }}</textarea>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Estimated Hours: (15mins = 0.25 / 30mins = 0.5 / 45mins = 0.75)</strong>
                    <input type="text" value="{{ 0, old('estimated_hours') }}" name="estimated_hours" class="form-control" placeholder="Estimated Hours">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Project:</strong>
                    <!-- <select class="form-control" name="project_id" value="{{ old('project_id') }}">
                        <option value="">Select Project</option>
                        @foreach ($projects as $key => $value)
                            <option value="{{ $key }}" {{ ( $key == $selectedProjectId) ? 'selected' : '' }}{{ old('project_id') == $key ? 'selected' : '' }}> 
                                {{ $value }} 
                            </option>
                        @endforeach    
                    </select> -->
                    <select class="form-control" name="project_id" value="{{ old('project_id') }}">
                        <option value="">Select Project</option>
                        @foreach ($projects as $key => $value)
                            <option value="{{ $key }}" {{ old('project_id') == $key ? 'selected' : '' }}> 
                                {{ $value }} 
                            </option>
                        @endforeach    
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>User:</strong>
                    <!-- <select class="form-control" name="user_id" value="{{ old('user_id') }}">
                        <option value="0">Select User</option>
                        @foreach ($users as $key => $value)
                            <option value="{{ $key }}" {{ ( $key == $selectedUserId) ? 'selected' : '' }}> 
                                {{ $value }} 
                            </option>
                        @endforeach    
                    </select> -->
                    <select class="form-control" name="user_id" value="{{ old('user_id') }}">
                        <option value="0">Select User</option>
                        @foreach ($users as $key => $value)
                            <option value="{{ $key }}" {{ $selectedUserId == $key ? 'selected' : '' }} {{ old('user_id') == $key ? 'selected' : '' }}> 
                                {{ $value }} 
                            </option>
                        @endforeach    
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Status:</strong>
                    <!-- <select class="form-control" name="status_id" value="{{ old('status_id') }}">
                        <option value="0">Select Status</option>
                        @foreach ($statuses as $key => $value)
                            <option value="{{ $key }}" {{ ( $key == $selectedStatusId) ? 'selected' : '' }}> 
                                {{ $value }} 
                            </option>
                        @endforeach    
                    </select> -->
                    <select class="form-control" name="status_id" value="{{ old('status_id') }}">
                        <!-- <option value="0">Select Status</option> -->
                        @foreach ($statuses as $key => $value)
                            <option value="{{ $key }}" {{ $selectedStatusId == $key ? 'selected' : '' }} {{ old('status_id') == $key ? 'selected' : '' }}> 
                                {{ $value }} 
                            </option>
                        @endforeach    
                    </select>
                </div>
            </div>
            <!-- <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Start Date:</strong>
                    <input  class="form-control datepicker" value="{{ old('start_date') }}" type="text" name="start_date" placeholder="Start Date">
                </div>
            </div> -->
            
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Due Date:</strong>
                    <input autocomplete="off"  class="form-control datepicker" value="{{ old('due_date') }}" type="text" name="due_date" placeholder="Due Date">
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>************* Active Date *************</strong>
                    <input autocomplete="off"  class="form-control datepicker" value="{{ $active_date }}" type="text" name="active_date" placeholder="Active Date">
                    <strong>***************************************</strong>
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