@extends('template.app')

@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Profile</h4>
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="javascript: void(0);">Pages</a></li>
            <li class="breadcrumb-item active">Profile</li>
        </ol>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h4 class="card-title mb-2">Profile Details</h4>
        <div class="text-center">
            <div class="profile-user position-relative d-inline-block mx-auto mb-4">
                <img class="rounded-circle" src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : asset('tamplate/assets/images/users/avatar-3.jpg') }}" alt="avatar" width="100" class="mb-3">
            </div>
        </div>
        <div class="">
        <div class="table-responsive px-2">  
            <div class="mb-3 row">
                <label for="inputFullName" class="col-sm-2 col-form-label">Full Name</label>
                <div class="col-sm-10">
                    <input type="text" readonly class="form-control" id="staticName" value="{{ Auth::user()->nama }}">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="staticPhoneNumber" class="col-sm-2 col-form-label">Phone Number</label>
                <div class="col-sm-10">
                    <input type="text" readonly class="form-control" id="staticPhoneNumber" value="{{ Auth::user()->nohp }}">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-10">
                    <input type="text" readonly class="form-control" id="staticEmail" value="{{ Auth::user()->email }}">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="staticPassword" class="col-sm-2 col-form-label">Password</label>
                <div class="col-sm-10 position-relative">
                    <input type="password" readonly class="form-control" id="staticPassword" value="************">
                    <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted" type="button" onclick="togglePassword()">
                        <i class="ri-eye-fill align-middle me-1"></i>
                    </button>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="inputAlamat" readonly class="col-sm-2 col-form-label">Alamat</label>
                <div class="col-sm-10">
                    <input type="text" readonly class="form-control" id="inputAlamat" value="{{ Auth::user()->alamat }}">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="inputGender" readonly class="col-sm-2 col-form-label">Gender</label>
                <div class="col-sm-10">
                    <input type="text" readonly class="form-control" id="inputGender" value="{{ Auth::user()->jeniskelamin }}">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="inputGender" readonly class="col-sm-2 col-form-label">Jabatan</label>
                <div class="col-sm-10">
                    <input type="text" readonly class="form-control" id="Jabatan" value="{{ Auth::user()->jabatan }}">
                </div>
            </div>
            
            <div>
                <div align="center">
                <a type="button" href="{{ route('profile.edit') }}" class="btn btn-primary">Edit</a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Script untuk melihat password
    function togglePassword() {
        const passwordInput = document.getElementById('staticPassword');
        const passwordIcon = document.querySelector('button[onclick="togglePassword()"] i');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            passwordInput.value = 'mypassword'; // Dummy password for demonstration
            passwordIcon.classList.remove('ri-eye-fill');
            passwordIcon.classList.add('ri-eye-off-fill');
        } else {
            passwordInput.type = 'password';
            passwordInput.value = '************';
            passwordIcon.classList.remove('ri-eye-off-fill');
            passwordIcon.classList.add('ri-eye-fill');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Script untuk edit foto profil
        document.getElementById('profile-img-file-input').addEventListener('change', function() {
            // Di sini Anda bisa menambahkan logika untuk mengunggah gambar
            // atau menampilkan pratinjau.
            // Contoh: window.location.href = '{{ route('profile.edit') }}';
            alert('Tombol edit foto diklik. Arahkan ke halaman edit profil.');
            window.location.href = '{{ route("profile.edit") }}';
        });
    });
</script>
@endpush
@endsection