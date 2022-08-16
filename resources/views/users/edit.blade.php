@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit User</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('users.index') }}" title="Go back"> GO BACK </a>
                
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

    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Name:</strong>
                    <input type="text" name="name" value="{{ $user->name }}" class="form-control" placeholder="Name">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Phone(s):</strong>
                    <input type="text" name="phone" value="{{ $user->phone }}" class="form-control" placeholder="Phone (Comma separated)">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Background Color:</strong>
                    <input type="text" name="bg_color" value="{{ $user->bg_color }}" class="form-control" placeholder="bg_color">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Text Color:</strong>
                    <input type="text" name="txt_color" value="{{ $user->txt_color }}" class="form-control" placeholder="txt_color">
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="active" id="active1" value="1" {{ $user->active == true ? 'checked' : '' }}>
                      <label class="form-check-label" for="active1">Active</label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="active" id="active2" value="0" {{ $user->active == false ? 'checked' : '' }}>
                      <label class="form-check-label" for="active2">Inactive</label>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="dedicated" id="dedicated1" value="1" {{ $user->dedicated == true ? 'checked' : '' }}>
                      <label class="form-check-label" for="dedicated1">Dedicated</label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="dedicated" id="dedicated2" value="0" {{ $user->dedicated == false ? 'checked' : '' }}>
                      <label class="form-check-label" for="dedicated2">Not Dedicated</label>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="role" id="role1" value="Admin" {{ $user->role == "Admin" ? 'checked' : '' }}>
                      <label class="form-check-label" for="role1">Admin</label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="role" id="role2" value="User" {{ $user->role == "User" ? 'checked' : '' }}>
                      <label class="form-check-label" for="role2">User</label>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Team:</strong>
                    <select class="form-control" name="team_id">
                        <option value="">Select Team</option>
                        @foreach ($teams as $key => $value)
                            <option value="{{ $key }}" {{ ( $key == $user->team_id) ? 'selected' : '' }}> 
                                {{ $value }} 
                            </option>
                        @endforeach    
                    </select>
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