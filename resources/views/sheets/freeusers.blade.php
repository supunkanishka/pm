@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>FREE USERS [{{ count($users) }}]</h2>
            </div>
        </div>
    </div>

    <br/>
    <form action="{{ route('freeusers') }}" method="GET">
        @csrf
        <input type="hidden" name="reset" value="false" />
        <table class="table table-bordered table-responsive-lg">
            <tr>
                <th width="5%">DATE</th>
                <th width="5%">ACTIONS</th>
            </tr>
            <tr>
                <td>
                    <input  class="form-control datepicker" type="text" name="active_date" value="{{ $active_date }}" placeholder="Date">
                </td>
                <td>
                    <button type="submit" title="seaarch" class="btn btn-success">
                            Search
                    </button>
                </td>
            </tr>
        </table>
    </form>
    

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-bordered table-responsive-lg">
        <tr>
            <th width="20%" style="text-align: center;">Id</th>
            <th width="80%" style="text-align: center;">User</th>
        </tr>

        @foreach ($users as $user)
            <tr>
                <td align="center">{{ $user->id }}</td>
                <td style="text-align: center;background-color: {{ ($user) ? $user->bg_color : '#FFFFFF' }};color: {{ ($user) ? $user->txt_color : '#000000' }}">{{ ($user) ? $user->name : '-' }}</td>
            </tr>
        @endforeach
    </table>

    {!! $users->links() !!}

@endsection