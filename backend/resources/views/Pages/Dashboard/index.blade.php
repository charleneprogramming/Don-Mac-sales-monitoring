@extends('Layout.app')
@section('title', 'Dashboard')
@include('Components.NavBar.navbar')

@section('content')
    <div class="container-fluid py-4" style="background-color: #ffffff; min-height: calc(100vh - 56px);">
        <div class="row mb-4 text-center">
            <div class="col-12">
                <h2 class="dashboard-title">
                    <i class="fas fa-chart-line me-2"></i>Dashboard
                </h2>
                <p class="text-muted">This page is currently empty.</p>
            </div>
        </div>
    </div>
@endsection