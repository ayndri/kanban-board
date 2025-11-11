@extends('template.app')

@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">My Profile</h4>
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Menu</a></li>
            <li class="breadcrumb-item active">Profile</li>
        </ol>
    </div>
</div>

{{-- Tampilkan Notifikasi --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="profile-user position-relative d-inline-block mx-auto mb-4">
                    <img src="{{ Auth::user()->photo ? url('storage/' . Auth::user()->photo) : asset('tamplate/assets/images/users/avatar-3.jpg') }}"
                        class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="user-profile-image">

                    {{-- Form Upload diletakkan di dalam form utama --}}
                    <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                        <input id="profile-img-file-input" type="file" name="photo" class="profile-img-file-input" form="profileUpdateForm">
                        <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                            <span class="avatar-title rounded-circle bg-light text-body">
                                <i class="ri-camera-fill"></i>
                            </span>
                        </label>
                    </div>
                </div>
                <h5 class="fs-16 mb-1">{{ Auth::user()->name }}</h5>
                <p class="text-muted mb-0">{{ Auth::user()->employee?->job_title ?? 'No Job Title' }}</p>
                <p class="text-muted mb-0">{{ Auth::user()->employee?->division?->name ?? 'No Division' }}</p>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <form id="profileUpdateForm" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <h5 class="card-title mb-4">Personal Details</h5>

                    {{-- Menampilkan error validasi --}}
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}">
                        </div>
                    </div>

                    <hr class="my-3">
                    <h5 class="card-title mb-4">Employee Details</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->employee?->phone) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="job_title" class="form-label">Job Title</label>
                            <input type="text" class="form-control" id="job_title" name="job_title" value="{{ old('job_title', $user->employee?->job_title) }}">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="division_id" class="form-label">Division</label>
                            <select class="form-select" id="division_id" name="division_id">
                                <option value="">Select Division...</option>
                                @foreach($divisions as $division)
                                <option value="{{ $division->id }}"
                                    {{ old('division_id', $user->employee?->division_id) == $division->id ? 'selected' : '' }}>
                                    {{ $division->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>

        {{-- Ini adalah praktik terbaik: Pisahkan form ganti password --}}
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Change Password</h5>
            </div>
            <div class="card-body">

                {{-- Notifikasi Khusus Untuk Password --}}
                @if(session('success_password'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success_password') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                @if(session('error_password'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error_password') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                {{-- Tampilkan error validasi password --}}
                @if ($errors->has('current_password') || $errors->has('password'))
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                        {{-- Hanya tampilkan error yang relevan dengan password --}}
                        @if(Str::contains($error, 'password'))
                        <li>{{ $error }}</li>
                        @endif
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- Arahkan form ke route yang benar --}}
                <form action="{{ route('profile.password.update') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" class="form-control" name="current_password" id="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control" name="password" id="new_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" required>
                    </div>
                    <div class="text-end">
                        {{-- Hapus attribute 'disabled' --}}
                        <button type="submit" class="btn btn-secondary">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Script untuk preview image-upload
    $(document).ready(function() {
        $('#profile-img-file-input').on('change', function(event) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('.user-profile-image').attr('src', e.target.result);
            };
            reader.readAsDataURL(event.target.files[0]);
        });
    });
</script>
@endpush