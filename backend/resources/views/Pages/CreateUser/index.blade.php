@extends('Layout.app')
@section('title', 'Create User')
@include('Components.NavBar.navbar')
@section('content')
    <div class="container-fluid py-5" style="min-height: calc(100vh - 56px);">
        <div class="row justify-between">
            <div class="col-md-6">
                <div class="card shadow-lg border-0 rounded-lg" style="background-color: white;">
                    <div class="card-header bg-primary text-white">
                        <h3 class="text-center font-weight-bold my-2">User Management</h3>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Create/Edit User Form -->
                        <form id="userForm" action="{{ route('users.store') }}" method="POST" class="mb-5">
                            @csrf
                            <input type="hidden" id="userId" name="user_id">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" name="name" id="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name') }}" placeholder="Enter name">
                                </div>
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" name="username" id="username"
                                        class="form-control @error('username') is-invalid @enderror"
                                        value="{{ old('username') }}" placeholder="Enter username">
                                </div>
                                @error('username')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="contact" class="form-label">Contact number</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-address-book"></i></span>
                                    <input type="number" name="contact" id="contact"
                                        class="form-control @error('contact') is-invalid @enderror"
                                        value="{{ old('contact') }}" placeholder="Enter contact number">
                                </div>
                                @error('contact')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" name="password" id="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Enter password">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="user_type" class="form-label">User Type</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user-shield"></i></span>
                                    <select name="user_type" id="user_type"
                                        class="form-control @error('user_type') is-invalid @enderror">
                                        <option value="">Select User Type</option>
                                        <option value="1">Admin</option>
                                        <option value="2">Cashier User</option>
                                    </select>
                                </div>
                                @error('user_type')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                    <i class="fas fa-plus-circle me-2"></i>Create User
                                </button>
                                <button type="button" class="btn btn-secondary btn-lg d-none" id="cancelBtn">
                                    <i class="fas fa-times-circle me-2"></i>Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                         <!-- Users Table -->
                         <div class="card mt-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Existing Users</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Username</th>
                                                <th>Created At</th>
                                                <th>User Type</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($users as $user)
                                                <tr>
                                                    <td>{{ $user->username }}</td>
                                                    <td>{{ $user->created_at->format('Y-m-d H:i:s') }}</td>
                                                    <td>{{ $user->isAdmin == 1 ? 'Admin' : 'User Admin' }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-warning btn-sm edit-user"
                                                            data-id="{{ $user->id }}"
                                                            data-username="{{ $user->username }}"
                                                            data-user-type="{{ $user->isAdmin ? '1' : '2' }}">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <form action="{{ route('users.destroy', $user->id) }}"
                                                            method="POST" class="d-inline"
                                                            onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('userForm');
            const submitBtn = document.getElementById('submitBtn');
            const cancelBtn = document.getElementById('cancelBtn');
            const userId = document.getElementById('userId');
            const username = document.getElementById('username');
            const password = document.getElementById('password');

            // Edit user button click handlers
            document.querySelectorAll('.edit-user').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const usernameValue = this.dataset.username;
                    const userType = this.dataset.userType;

                    // Update form for editing
                    form.action = `/users/${id}`;
                    userId.value = id;
                    username.value = usernameValue;
                    document.getElementById('user_type').value = userType;
                    password.value = '';
                    password.placeholder = 'Enter new password (leave blank to keep current)';

                    // Update UI for editing mode
                    submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Update User';
                    submitBtn.classList.remove('btn-primary');
                    submitBtn.classList.add('btn-success');
                    cancelBtn.classList.remove('d-none');

                    // Add method override for PUT request
                    let methodInput = form.querySelector('input[name="_method"]');
                    if (!methodInput) {
                        methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        form.appendChild(methodInput);
                    }
                    methodInput.value = 'PUT';
                });
            });

            // Cancel button handler
            cancelBtn.addEventListener('click', function() {
                resetForm();
            });

            // Password visibility toggle
            document.getElementById('togglePassword').addEventListener('click', function() {
                const password = document.getElementById('password');
                const icon = this.querySelector('i');

                if (password.type === 'password') {
                    password.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    password.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });

            function resetForm() {
                form.reset();
                form.action = "{{ route('users.store') }}";
                userId.value = '';
                password.placeholder = 'Enter password';
                submitBtn.innerHTML = '<i class="fas fa-plus-circle me-2"></i>Create User';
                submitBtn.classList.remove('btn-success');
                submitBtn.classList.add('btn-primary');
                cancelBtn.classList.add('d-none');

                // Remove method override
                const methodInput = form.querySelector('input[name="_method"]');
                if (methodInput) {
                    methodInput.remove();
                }
            }
        });
    </script>
@endsection

<style>
    html,
    body {
        min-height: 100vh;
        margin: 0;
        padding: 0;
        background-color: #fff8e7;
    }

    .container-fluid {
        padding-right: 15px;
        padding-left: 15px;
        margin-right: auto;
        margin-left: auto;
        min-height: calc(100vh - 56px);
        /* Adjust 56px according to your navbar height */
    }

    .card {
        background-color: white;
        border: 1px solid #d4b8a5;
    }

    .table-hover tbody tr:hover {
        background-color: #fff8e7 !important;
    }

    .btn-primary {
        background-color: #6b4226;
        border-color: #6b4226;
    }

    .btn-primary:hover {
        background-color: #4b3025;
        border-color: #4b3025;
    }

    .card-header.bg-primary {
        background-color: #6b4226 !important;
    }

    .btn-warning {
        background-color: #d4b8a5;
        border-color: #d4b8a5;
        color: #4b3025;
    }

    .btn-warning:hover {
        background-color: #c4a08d;
        border-color: #c4a08d;
        color: #4b3025;
    }

    .input-group-text {
        background-color: #fff8e7;
        border-color: #d4b8a5;
        color: #6b4226;
    }

    .form-control:focus {
        border-color: #d4b8a5;
        box-shadow: 0 0 0 0.2rem rgba(107, 66, 38, 0.25);
    }

    .table-light {
        background-color: #fff8e7;
    }

    .alert-success {
        background-color: #e8f5e9;
        border-color: #a5d6a7;
        color: #2e7d32;
    }

    .alert-danger {
        background-color: #ffebee;
        border-color: #ffcdd2;
        color: #c62828;
    }
</style>