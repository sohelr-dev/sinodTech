@extends('layouts.master')
@section('title', 'Edit Profile - FNF Trip')
@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="bg-primary px-4 pt-5 pb-5 text-center position-relative" style="min-height: 150px; background-image: url('{{ asset('assets/images/fnfLogo.jpg') }}');">
                        <div class="position-absolute start-50 translate-middle-x" style="bottom: -50px;">
                            <div class="position-relative"  data-aos="flip-left"
                                        data-aos-easing="ease-out-cubic"
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
                    @if(session('status'))
                        <div id="successAlert" class="alert alert-success alert-dismissible fade show text-center m-4 " data-aos="fade-down-left" role="alert">
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif


                    <div class="card-body pt-5 mt-4" data-aos="zoom-in-right">
                        <div class="text-center mb-4">
                            <h4 class="fw-bold mb-1">{{ auth()->user()->name }}</h4>
                            <p class="text-muted small"><i class="fa-solid fa-envelope me-1"></i>
                                {{ auth()->user()->email }}</p>
                            <span class="badge bg-primary-subtle text-primary rounded-pill px-3">Verified Member</span>
                        </div>

                        <hr class="text-muted opacity-25">

                        <form action="{{ route('profile.update') }}" method="POST" class="px-md-4">
                            @csrf
                            @method('PATCH')

                            <div class="row g-3">
                                <div class="col-md-6" data-aos="fade-right" data-aos-offset="200" data-aos-easing="ease-in-sine">
                                    <label class="form-label fw-semibold">Full Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i
                                                class="fa-regular fa-user"></i></span>
                                        <input type="text" class="form-control bg-light border-0"
                                            value="{{ auth()->user()->name }}" name="name">
                                    </div>
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6" data-aos="fade-left" data-aos-offset="200" data-aos-easing="ease-in-sine">
                                    <label class="form-label fw-semibold">Email Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i
                                                class="fa-regular fa-envelope"></i></span>
                                        <input type="email" class="form-control bg-light border-0"
                                            value="{{ auth()->user()->email }}" disabled>
                                    </div>
                                    <small class="text-muted" style="font-size: 11px;">Email cannot be changed.</small>
                                </div>

                                <div class="col-md-6" data-aos="fade-up" data-aos-offset="200" data-aos-easing="ease-in-sine">
                                    <label class="form-label fw-semibold">Phone Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i
                                                class="fa-solid fa-phone"></i></span>
                                        <input type="text" class="form-control bg-light border-0" value="{{ auth()->user()->phone ?? "" }}"
                                            name="phone" placeholder="+880 1XXX XXXXXX">
                                    </div>
                                    @error('phone')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6" data-aos="fade-up" data-aos-offset="200" data-aos-easing="ease-in-sine">
                                    <label class="form-label fw-semibold">Location</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i
                                                class="fa-solid fa-location-dot"></i></span>
                                        <input type="text" class="form-control bg-light border-0" value="{{ auth()->user()->location ?? "" }}"
                                           name="location" placeholder="City, Country">
                                    </div>
                                    @error('location')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-12 mt-4 text-end" data-aos="fade-left" data-aos-offset="200" data-aos-easing="ease-in-sine">
                                    <button type="button" class="btn btn-light px-4 me-2 rounded-pill">Cancel</button>
                                    <button type="submit" class="btn btn-primary px-4 rounded-pill shadow-sm">Save
                                        Changes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 mt-4 mb-5" data-aos="fade-up">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3"><i class="fa-solid fa-lock me-2 text-primary"></i> Change Password</h5>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="password" class="form-control bg-light border-0"
                                    placeholder="Current Password">
                            </div>
                            <div class="col-md-4">
                                <input type="password" class="form-control bg-light border-0" placeholder="New Password">
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-outline-primary w-100 rounded-pill">Update Password</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-primary-subtle {
            background-color: #e7f1ff;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .form-control:focus {
            background-color: #fff !important;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
            border: 1px solid #0d6efd !important;
        }

        @media (max-width: 768px) {
            .card-body {
                padding: 1.5rem;
            }

            .bg-primary {
                min-height: 120px;
            }
        }
    </style>
    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            $(document).ready(function() {
                $('#photo-upload').on('change', function() {
                    let file = this.files[0];
                    if (!file) return;
                    //preview image
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        $('#profile-image-preview').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(file);

                    // FormData maker
                    let formData = new FormData();
                    formData.append('photo', file);
                    formData.append('_token', '{{ csrf_token() }}');

                    // AJAX request to update profile photo
                    $.ajax({
                        url: "{{ route('profile.updatePhoto') }}",
                        type: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        beforeSend: function() {
                            //options before sending request
                            $('#profile-image-preview').css('opacity', '0.5');
                        },
                        success: function(response) {
                            $('#profile-image-preview').css('opacity', '1');
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: 'Profile photo updated successfully.',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                                // update image preview with new url from response
                                $('#profile-image-preview').attr('src', response.image_url);
                            }
                        },
                        error: function(xhr) {
                            $('#profile-image-preview').css('opacity', '1');
                            let errorMessage = xhr.responseJSON ? xhr.responseJSON.message :
                                'Something went wrong!';
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: errorMessage
                            });
                        }
                    });
                });
            });

            // auto hide success alert after 4 seconds
            $(document).ready(function(){
                setTimeout(function(){
                    $('#successAlert').fadeOut('slow');
                },4000);
                });

            AOS.init({
                duration: 800, // animation duration in ms
                easing: 'ease-in-out', // animation easing
                once: true, // scroll animation should happen only once-while scrolling down
            });
        </script>
    @endpush
@endsection
