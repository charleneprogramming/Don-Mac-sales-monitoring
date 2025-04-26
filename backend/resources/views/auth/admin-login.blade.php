@extends('Layout.app')
@section('title', 'Admin Login')

@section('content')
    <div class="container-fluid position-relative"
        style="height: 100vh; background: url('/images/sign_page.jpeg') no-repeat center center/cover;">

        <div class="position-absolute" style="top: 20px; left: 20px;">
            <img src="/images/logo.jpg" alt="Logo" class="rounded-circle" style="width: 90px; height: 90px;">
        </div>

        <div class="d-flex justify-content-end align-items-center h-100">
            <div class="col-md-4 col-lg-4 col-xl-4 position-relative me-5">
                <div class="shadow p-4 rounded" style="background-color: #252636c9;">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('admin.login') }}" method="POST">
                        @csrf
                        <div class="card-header border-0 text-center mb-4">
                            <p class="text-subtitle" style="color: #ffffff; font-weight: bold;">Don Macchiatos</p>
                            <h1 class="display-5" style="color: #ffffff; font-weight: bold;"> Login</h1>
                        </div>
                        <div class="mb-4">
                            <label for="username" class="form-label" style="color: #ffffff;">Username</label>
                            <input type="text" name="username"
                                class="form-control border-2 @error('username') is-invalid @enderror"
                                style="background-color: transparent; color: #ffffff;" id="username"
                                value="{{ old('username') }}">
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label" style="color: #ffffff;">Password</label>
                            <input type="password" name="password"
                                class="form-control border-2 @error('password') is-invalid @enderror"
                                style="background-color: transparent; color: #ffffff;" id="password">
                        </div>

                        <button type="submit" class="btn w-100 py-2"
                            style="background-color: #6f4e37; color: #fff; border: none;">
                            <strong>SIGN IN</strong>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
