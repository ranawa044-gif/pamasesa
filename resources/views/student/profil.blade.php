@extends('layouts.app')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="page-kicker"><i class="bi bi-person-gear me-1"></i> Akun Pengguna</div>
            <h3 class="fw-bold mb-1">Pengaturan Akun</h3>
            <p class="text-muted small mb-0">Kelola foto profil dan kata sandi akun PAMASESA Anda.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4 rounded-3 shadow-sm border-0" role="alert">
            <i class="bi bi-check-circle-fill fs-5 text-success"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(isset($errors) && $errors->any())
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-start gap-2 mb-4 rounded-3 shadow-sm border-0" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-5 text-danger flex-shrink-0 mt-1"></i>
            <div>
                <strong>Terdapat beberapa kesalahan:</strong>
                <ul class="mb-0 mt-1 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        {{-- CARD KIRI: UPLOAD FOTO PROFIL --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3 border-bottom border-light">
                    <h5 class="card-title fw-bold mb-0 text-slate-800 d-flex align-items-center">
                        <i class="bi bi-image text-primary me-2"></i> Foto Profil
                    </h5>
                </div>
                <div class="card-body p-4 text-center">
                    {{-- Preview Foto --}}
                    <div class="position-relative d-inline-block mb-3">
                        @php
                            $hasPhoto = $user->foto_profil && Storage::disk('public')->exists($user->foto_profil);
                            $photoUrl = $hasPhoto ? asset('storage/' . $user->foto_profil) : null;
                        @endphp

                        <div class="avatar-wrapper rounded-circle p-1 bg-white shadow-sm border border-2 border-primary border-opacity-25" style="width: 140px; height: 140px; margin: 0 auto;">
                            <img id="preview-avatar" 
                                 src="{{ $photoUrl ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=4f46e5&color=fff&size=200&bold=true' }}" 
                                 alt="{{ $user->name }}" 
                                 class="rounded-circle w-100 h-100 object-fit-cover shadow-inner">
                        </div>
                    </div>

                    <h5 class="fw-bold text-slate-900 mb-1">{{ $user->name }}</h5>
                    <div class="text-muted small mb-2">{{ $user->email }}</div>
                    <div class="d-inline-flex align-items-center gap-1 badge bg-indigo-subtle text-primary border border-indigo-subtle px-3 py-2 rounded-pill mb-4 font-mono">
                        <i class="bi bi-person-badge me-1"></i> NIM: {{ $student?->nim ?? '-' }} &bull; {{ $student?->kelas ?? 'Mahasiswa' }}
                    </div>

                    <hr class="border-secondary border-opacity-10 my-3">

                    {{-- Form Upload Foto --}}
                    <form action="{{ route('student.profile.photo') }}" method="POST" enctype="multipart/form-data" class="text-start">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="foto_profil" class="form-label fw-semibold text-slate-700" style="font-size: 0.875rem;">
                                Pilih Foto Baru <span class="text-danger">*</span>
                            </label>
                            <input type="file" 
                                   name="foto_profil" 
                                   id="foto_profil" 
                                   class="form-control @error('foto_profil') is-invalid @enderror" 
                                   accept="image/png, image/jpeg, image/jpg" 
                                   required 
                                   onchange="handleAvatarPreview(this)">
                            @error('foto_profil')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-muted" style="font-size: 0.775rem;">
                                <i class="bi bi-info-circle me-1"></i> Format didukung: <strong>JPG, JPEG, PNG</strong> (Maksimal 2 MB).
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold rounded-3 shadow-sm">
                            <i class="bi bi-cloud-arrow-up-fill me-1"></i> Simpan Foto Profil
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- CARD KANAN: GANTI PASSWORD --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3 border-bottom border-light">
                    <h5 class="card-title fw-bold mb-0 text-slate-800 d-flex align-items-center">
                        <i class="bi bi-shield-lock-fill text-primary me-2"></i> Ganti Password
                    </h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted small mb-4">
                        Untuk menjaga keamanan akun Anda, gunakan kata sandi yang kuat dan tidak digunakan di situs lain.
                    </p>

                    <form action="{{ route('student.profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Password Lama --}}
                        <div class="mb-3">
                            <label for="current_password" class="form-label fw-semibold text-slate-700" style="font-size: 0.875rem;">
                                Password Saat Ini (Lama) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" 
                                       name="current_password" 
                                       id="current_password" 
                                       class="form-control border-start-0 @error('current_password') is-invalid @enderror" 
                                       placeholder="Ketik password lama Anda" 
                                       required>
                                <button class="btn btn-outline-secondary border-start-0" type="button" onclick="togglePassword('current_password', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Password Baru --}}
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold text-slate-700" style="font-size: 0.875rem;">
                                Password Baru <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-key-fill"></i></span>
                                <input type="password" 
                                       name="password" 
                                       id="password" 
                                       class="form-control border-start-0 @error('password') is-invalid @enderror" 
                                       placeholder="Minimal 8 karakter" 
                                       required>
                                <button class="btn btn-outline-secondary border-start-0" type="button" onclick="togglePassword('password', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text text-muted" style="font-size: 0.775rem;">
                                <i class="bi bi-check2-circle me-1"></i> Gunakan minimal 8 karakter dengan kombinasi huruf dan angka.
                            </div>
                        </div>

                        {{-- Konfirmasi Password Baru --}}
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-semibold text-slate-700" style="font-size: 0.875rem;">
                                Konfirmasi Password Baru <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-shield-check"></i></span>
                                <input type="password" 
                                       name="password_confirmation" 
                                       id="password_confirmation" 
                                       class="form-control border-start-0" 
                                       placeholder="Ketik ulang password baru Anda" 
                                       required>
                                <button class="btn btn-outline-secondary border-start-0" type="button" onclick="togglePassword('password_confirmation', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold rounded-3 shadow-sm">
                                <i class="bi bi-check-circle-fill me-1"></i> Perbarui Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script untuk Preview Foto dan Toggle Password --}}
<script>
    function handleAvatarPreview(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewImg = document.getElementById('preview-avatar');
                if (previewImg) {
                    previewImg.src = e.target.result;
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function togglePassword(inputId, button) {
        const input = document.getElementById(inputId);
        const icon = button.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
</script>
@endsection
