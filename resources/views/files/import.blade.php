@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Add New Task</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('tasks.index') }}" title="Go back"> GO BACK</i> </a>
            <input class="btn btn-primary" id="clickMe" type="button" value="Download Sample File" onclick="doFunction();" />
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
<form action="{{ route('import') }}" method="POST" id="formId" enctype="multipart/form-data">
    @csrf

    <div class="row">
        
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Select File:</strong>
                <input type="file" name="file" class="form-control">
            </div>
        </div>
        
        
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Project:</strong>
                <select class="form-control" name="project_id" value="{{ old('project_id') }}">
                    <option value="">Select Project</option>
                    @foreach ($projects as $key => $value)
                        
                        <option value="{{ $key }}" {{ $selectedProjectId == $key ? 'selected' : '' }} {{ old('project_id') == $key ? 'selected' : '' }}> 
                            {{ $value }} 
                        </option>
                    @endforeach    
                </select>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Sprint:</strong>
                <select class="form-control" name="sprint_id" value="{{ old('sprint_id') }}">
                    <option value="">Select Sprint</option>
                    @foreach ($sprints as $key => $value)
                        <option value="{{ $key }}" {{ old('sprint_id') == $key ? 'selected' : '' }}> 
                            {{ $value }} 
                        </option>
                    @endforeach    
                </select>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>User:</strong>
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
                <select class="form-control" name="status_id" value="{{ old('status_id') }}">
                    @foreach ($statuses as $key => $value)
                        <option value="{{ $key }}" {{ $selectedStatusId == $key ? 'selected' : '' }} {{ old('status_id') == $key ? 'selected' : '' }}> 
                            {{ $value }} 
                        </option>
                    @endforeach    
                </select>
            </div>
        </div>
        
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
            <button id="btnSubmit" type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>

</form>






<!-- <div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('tasks.index') }}" title="Go back"> GO BACK</i> </a>
        </div>
    </div>
</div>
<div class="row">
    <div class="card bg-light mt-3">
        <div class="card-header">
            <h2>Upload Task List</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="file" class="form-control">
                <br>
                <button class="btn btn-success">Import User Data</button>
                <a class="btn btn-warning" href="{{ route('export') }}">Export User Data</a>
            </form>
        </div>
    </div>
</div> -->
@endsection
@section('scripts')
<script type="text/javascript">
$(document).ready(function() {
    document.getElementById("clickMe").onclick = function () { 
        window.location = 'downloadfile';
        // $.ajax({
        //     url: '/downloadfile',
        //     type: 'POST',
        //     beforeSend: function (request) {
        //         return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
        //     },
        //     success: function(response){
        //         var blob = new Blob([response]);
        //         var link = document.createElement('a');
        //         link.href = window.URL.createObjectURL(blob);
        //         link.download = "Sample.pdf";
        //         link.click();
        //     }
        // })
    };

    $("#formId").submit(function (e) {

        //disable the submit button
        $("#btnSubmit").attr("disabled", true);

        

    });

    function downloadfile(){
        $.ajax({
            url: '/downloadfile',
            type: 'POST',
            beforeSend: function (request) {
                return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
            },
            success: function(response){
                console.log(response);
            }
        })
    }
    
});
</script>
@endsection