@extends('layouts.master')
@section('title', 'Settings - FNF Trip')
@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="bg-primary px-4 pt-5 pb-5 text-center position-relative"
                        style="min-height: 150px; background-image: url('{{ asset('assets/images/fnfLogo.jpg') }}');">
                        <div class="position-absolute start-50 translate-middle-x" style="bottom: -50px;">
                            <div class="position-relative" data-aos="flip-left" data-aos-easing="ease-out-cubic"
                                data-aos-duration="1000">
                                <img id="profile-image-preview"
                                    src="{{ auth()->user()->photo ? asset('storage/' . auth()->user()->photo) : asset('assets/images/default-user.jpg') }}"
                                    class="rounded-circle border border-4 border-white shadow" alt="Profile Photo"
                                    style="width: 120px; height: 120px; object-fit: cover;">

                                <label for="photo-upload"
                                    class="position-absolute bottom-0 end-0 bg-white rounded-circle shadow-sm p-2 cursor-pointer"
                                    style="cursor: pointer;">
                                    <i class="fa-solid fa-camera text-primary"></i>
                                    <input type="file" id="photo-upload" class="d-none" accept="image/*">
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="card-body pt-5 mt-4" data-aos="zoom-in-right">
                        <div class="text-center mb-4">
                            <h4 class="fw-bold mb-1">{{ auth()->user()->name }}</h4>
                            <p class="text-muted small"><i class="fa-solid fa-envelope me-1"></i>
                                {{ auth()->user()->email }}</p>
                            <span class="badge bg-primary-subtle text-primary rounded-pill px-3">Verified Member</span>
                        </div>
                        @if (session('status') === 'password-updated')
                            <div id="successAlert" class="alert alert-success alert-dismissible fade show text-center m-4 "
                                data-aos="fade-down-right" data-aos-duration="1000" data-aos-offset="0" role="alert">
                                Password updated successfully
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <hr class="text-muted opacity-25">
                        <div class="card mt-4 mb-5">
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-3"><i class="fa-solid fa-lock me-2 text-primary"></i> Change Password
                                </h5>
                                <form method="POST" action="{{ route('password.update') }}">
                                    @csrf
                                    @method('PUT')

                                    <div class="row g-3">
                                        <!-- Current Password -->
                                        <div class="col-md-4">
                                            <div class="position-relative">
                                                <input type="password" name="current_password" id="current_password"
                                                    class="form-control" placeholder="Min 8 characters" required>
                                                <span class="position-absolute top-50 end-0 translate-middle-y pe-3"
                                                    style="cursor:pointer;" onclick="togglePass('current_password')">
                                                    <i class="fa-regular fa-eye"></i>
                                                </span>
                                            </div>
                                            @error('current_password', 'updatePassword')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>


                                        <!-- New Password -->
                                        <div class="col-md-4">
                                            <div class="position-relative">
                                                <input type="password" name="password" id="password"
                                                    class="form-control bg-light border-0" placeholder="New Password">
                                                <span class="position-absolute top-50 end-0 translate-middle-y pe-3"
                                                    style="cursor:pointer;" onclick="togglePass('password')">
                                                    <i class="fa-regular fa-eye"></i>
                                                </span>
                                            </div>
                                            @error('password', 'updatePassword')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Confirm Password -->
                                        <div class="col-md-4">
                                            <div class="position-relative">
                                                <input type="password" name="password_confirmation"
                                                    id="password_confirmation" class="form-control bg-light border-0"
                                                    placeholder="Confirm Password">
                                                <span class="position-absolute top-50 end-0 translate-middle-y pe-3"
                                                    style="cursor:pointer;" onclick="togglePass('password_confirmation')">
                                                    <i class="fa-regular fa-eye"></i>
                                                </span>
                                            </div>
                                            @error('password_confirmation', 'updatePassword')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="col-12 mt-2">
                                            <button class="btn btn-outline-primary w-100 rounded-pill">Update
                                                Password</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePass(id) {
            const input = document.getElementById(id);
            const icon = input.nextElementSibling.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
        setTimeout(function() {
            const alert = document.getElementById('successAlert');
            if(alert){
                alert.classList.add('hide');
                setTimeout(() => alert.remove(), 1000);
            }
        }, 3000);

    </script>

@endsection
