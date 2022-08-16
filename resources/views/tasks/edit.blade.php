@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit Task</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('tasks.index') }}" title="Go back"> GO BACK </a>
                <a class="btn btn-primary" href="{{ route('tasks.spents.index', $task->id) }}">Hours</a>
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

    <form action="{{ route('tasks.update', $task->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 mt-3">
                <div class="form-group">
                    <input type="checkbox" name="meeting" value="1" placeholder="Meeting" {{$task->meeting == 1 ?'checked="checked"':''}}>
                    <strong>Meeting:</strong>
                    
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mt-3">
                <div class="form-group">
                    <input type="checkbox" name="bug" value="1" placeholder="Bug" {{$task->bug == 1 ?'checked="checked"':''}}>
                    <strong>Bug:</strong>
                    
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Name:</strong>
                    <input type="text" name="name" value="{{ $task->name }}" class="form-control" placeholder="Name">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Images:</strong>
                    <input type="file" name="images[]" multiple  accept="image/*">
                    @if ($errors->has('files'))
                        @foreach ($errors->get('files') as $error)
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $error }}</strong>
                        </span>
                        @endforeach
                      @endif
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Description:</strong>
                    <textarea class="form-control" cols="5" rows="5" name="description"
                        placeholder="Description">{{ $task->description }}</textarea>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Estimated Hours: (15mins = 0.25 / 30mins = 0.5 / 45mins = 0.75)</strong>
                    <input type="text" name="estimated_hours" value="{{ $task->estimated_hours }}" class="form-control" placeholder="Estimated Hours">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Project:</strong>
                    <select class="form-control" name="project_id">
                        <option value="">Select Project</option>
                        @foreach ($projects as $key => $value)
                            <option value="{{ $key }}" {{ ( $key == $task->project_id) ? 'selected' : '' }}> 
                                {{ $value }} 
                            </option>
                        @endforeach    
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Sprint:</strong>
                    <select class="form-control" name="sprint_id">
                        <option value="">Select Sprint</option>
                        @foreach ($sprints as $key => $value)
                            <option value="{{ $key }}" {{ ( $key == $task->sprint_id) ? 'selected' : '' }}> 
                                {{ $value }} 
                            </option>
                        @endforeach    
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>User:</strong>
                    <select class="form-control" name="user_id">
                        <option value="">Select User</option>
                        @foreach ($users as $key => $value)
                            <option value="{{ $key }}" {{ ( $key == $task->user_id) ? 'selected' : '' }}> 
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
                            <option value="{{ $key }}" {{ ( $key == $task->status_id) ? 'selected' : '' }}> 
                                {{ $value }} 
                            </option>
                        @endforeach    
                    </select>
                </div>
            </div>
            <!-- <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Start Date:</strong>
                    <input  class="form-control datepicker" type="text" value="{{ $task->start_date }}" name="start_date" placeholder="Start Date">
                </div>
            </div> -->

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Due Date:</strong>
                    <input autocomplete="off"  class="form-control datepicker" type="text" value="{{ $task->due_date }}" name="due_date" placeholder="Due Date">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>************* Active Date *************</strong>
                    <input autocomplete="off"  class="form-control datepicker" type="text" value="{{ $task->active_date }}" name="active_date" placeholder="Active Date">
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