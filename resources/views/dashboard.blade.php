@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1 class="fw-semibold">
        <i class="fas fa-gauge-high me-2"></i>
        Dashboard
    </h1>
@endsection

@section('content')
<div class="row">

    <div class="col-md-4">
        <div class="info-box bg-primary">
            <span class="info-box-icon"><i class="fas fa-users"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Pessoas</span>
                <span class="info-box-number">0</span>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="info-box bg-success">
            <span class="info-box-icon"><i class="fas fa-file-contract"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Contratos</span>
                <span class="info-box-number">0</span>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="info-box bg-warning">
            <span class="info-box-icon"><i class="fas fa-sitemap"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Workflows</span>
                <span class="info-box-number">0</span>
            </div>
        </div>
    </div>

</div>
@endsection
