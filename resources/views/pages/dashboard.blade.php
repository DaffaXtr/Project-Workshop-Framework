@extends('layouts.app')

@section('title', 'Dashboard')

@section('style-page')

@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card custom-card p-4">
            <h4>Selamat Datang di Dashboard</h4>
        </div>
    </div>
</div>
@endsection

@section('js-page')
<script>
    console.log("Dashboard Loaded");
</script>
@endsection
