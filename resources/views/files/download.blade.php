@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Add New Task</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('tasks.index') }}" title="Go back"> GO BACK</i> </a>
        </div>
    </div>
</div>


@endsection
@section('scripts')
<script type="text/javascript">

</script>
@endsection