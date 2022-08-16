@extends('layouts.app')

@section('content')
    
    <div id="chartContainer" style="height: 300px; width: 100%;"></div>

@endsection

@section('scripts')
<script type="text/javascript">
    window.onload = function () {

    var data = [];

    <?php 

    foreach ($users as $user) {
        ?>
        var name = '<?php echo $user->name; ?>';
        data.push({ label: name,  y: Math.floor(Math.random() * 10) + 1  });
        <?php
    }
    ?>
</script>
@endsection