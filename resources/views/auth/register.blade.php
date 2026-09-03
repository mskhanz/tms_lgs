@extends('layouts.auth')

@section('title', 'Register')

@section('header-title', 'Trainee Registration')

@section('header-subtitle', config('app.tagline'))

@section('content')
    <div class="alert alert-info d-flex align-items-start" role="alert">
        <i class="bi bi-info-circle me-2 mt-1 flex-shrink-0"></i>
        <span>Please use your official government email address to register. Your login details will be sent to this email for confirmation.</span>
    </div>

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle me-2"></i>
        Please correct the errors below and try again.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" novalidate>
        @csrf

        <p class="form-section-title">Training Selection</p>

        <div class="mb-4">
            <label for="registration_training_id" class="form-label">Registering For Training <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-mortarboard"></i></span>
                <select class="form-select @error('registration_training_id') is-invalid @enderror"
                        id="registration_training_id"
                        name="registration_training_id"
                        required>
                    <option value="" disabled {{ old('registration_training_id') ? '' : 'selected' }}>-- Select Training Program --</option>
                    @foreach($trainings as $training)
                    <option value="{{ $training->id }}" {{ (string) old('registration_training_id') === (string) $training->id ? 'selected' : '' }}>
                        {{ $training->title }}
                    </option>
                    @endforeach
                </select>
                @error('registration_training_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            @if($trainings->isEmpty())
            <div class="form-text text-danger">No training programs are currently open for registration. Please contact the training office.</div>
            @else
            <div class="form-text">Select the training program you are applying for.</div>
            @endif
        </div>

        <p class="form-section-title">Personal Information</p>

        <div class="mb-3">
            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input type="text"
                       class="form-control @error('name') is-invalid @enderror"
                       id="name"
                       name="name"
                       placeholder="Enter your full name"
                       value="{{ old('name') }}"
                       required
                       autofocus>
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-4">
            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email"
                       class="form-control @error('email') is-invalid @enderror"
                       id="email"
                       name="email"
                       placeholder="your.email@gov.pk"
                       value="{{ old('email') }}"
                       required>
                @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label">Trainee Photo <span class="text-danger">*</span></label>
            <div class="photo-upload-card @error('photo') border-danger @enderror" id="photoUploadCard">
                <div class="photo-preview-wrap">
                    <img id="photoPreview" src="" alt="Photo preview" class="photo-preview d-none">
                    <div id="photoPlaceholder" class="photo-placeholder">
                        <i class="bi bi-person-bounding-box"></i>
                        <span>No photo selected</span>
                    </div>
                </div>
                <div class="d-flex gap-2 flex-wrap justify-content-center mt-3">
                    <button type="button" class="btn btn-outline-primary btn-sm" id="btnGallery">
                        <i class="bi bi-images me-1"></i>Choose from Gallery
                    </button>
                    <button type="button" class="btn btn-outline-success btn-sm" id="btnCamera">
                        <i class="bi bi-camera me-1"></i>Take Photo
                    </button>
                </div>
                <input type="file"
                       id="photo"
                       name="photo"
                       class="d-none @error('photo') is-invalid @enderror"
                       accept="image/*">
                <div class="form-text text-center mt-2">
                    Passport-size photo. JPG, PNG or WEBP. Max 5 MB. Works on mobile gallery and camera.
                </div>
                @error('photo')
                <div class="invalid-feedback d-block text-center">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <p class="form-section-title">Account Security</p>

        <div class="mb-3">
            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password"
                       class="form-control @error('password') is-invalid @enderror"
                       id="password"
                       name="password"
                       placeholder="Create a password"
                       required>
                <button type="button" class="btn btn-outline-secondary" id="togglePassword" tabindex="-1" aria-label="Show password">
                    <i class="bi bi-eye" id="togglePasswordIcon"></i>
                </button>
                @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-text">Use at least 8 characters with a mix of letters and numbers.</div>
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input type="password"
                       class="form-control"
                       id="password_confirmation"
                       name="password_confirmation"
                       placeholder="Re-enter your password"
                       required>
                <button type="button" class="btn btn-outline-secondary" id="togglePasswordConfirm" tabindex="-1" aria-label="Show confirm password">
                    <i class="bi bi-eye" id="togglePasswordConfirmIcon"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2" @if($trainings->isEmpty()) disabled @endif>
            <i class="bi bi-person-plus me-2"></i>
            Create Account
        </button>
    </form>

    <div class="mt-4 text-center">
        <span class="text-muted">Already have an account?</span>
        <a href="{{ route('login') }}" class="auth-link text-decoration-none fw-medium ms-1">Sign In</a>
    </div>

    <div class="modal fade" id="cameraModal" tabindex="-1" aria-labelledby="cameraModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cameraModalLabel">Take Trainee Photo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 bg-dark">
                    <video id="cameraVideo" autoplay playsinline muted class="w-100" style="max-height: 360px; object-fit: cover;"></video>
                    <canvas id="cameraCanvas" class="d-none"></canvas>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" id="btnCapture">
                        <i class="bi bi-camera-fill me-1"></i>Capture Photo
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .photo-upload-card {
        border: 2px dashed #d1d5db;
        border-radius: 12px;
        padding: 20px;
        background: #fafafa;
        text-align: center;
    }
    .photo-upload-card.has-photo {
        border-color: #10b981;
        background: #f0fdf4;
    }
    .photo-preview-wrap {
        width: 140px;
        height: 140px;
        margin: 0 auto;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid #10b981;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .photo-preview {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .photo-placeholder {
        color: #9ca3af;
        font-size: 0.85rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
    }
    .photo-placeholder i {
        font-size: 2rem;
    }
</style>
@endpush

@push('scripts')
<script>
    function setupPasswordToggle(buttonId, inputId, iconId) {
        const button = document.getElementById(buttonId);
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);

        if (!button || !input || !icon) return;

        button.addEventListener('click', function () {
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            icon.classList.toggle('bi-eye', !isPassword);
            icon.classList.toggle('bi-eye-slash', isPassword);
            button.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
        });
    }

    setupPasswordToggle('togglePassword', 'password', 'togglePasswordIcon');
    setupPasswordToggle('togglePasswordConfirm', 'password_confirmation', 'togglePasswordConfirmIcon');

    (function () {
        const photoInput = document.getElementById('photo');
        const photoPreview = document.getElementById('photoPreview');
        const photoPlaceholder = document.getElementById('photoPlaceholder');
        const photoUploadCard = document.getElementById('photoUploadCard');
        const btnGallery = document.getElementById('btnGallery');
        const btnCamera = document.getElementById('btnCamera');
        const btnCapture = document.getElementById('btnCapture');
        const cameraModalEl = document.getElementById('cameraModal');
        const cameraVideo = document.getElementById('cameraVideo');
        const cameraCanvas = document.getElementById('cameraCanvas');
        const registerForm = document.querySelector('form[action="{{ route('register') }}"]');

        if (!photoInput || !btnGallery || !btnCamera) return;

        let cameraStream = null;
        let cameraModal = null;

        if (cameraModalEl && window.bootstrap) {
            cameraModal = new bootstrap.Modal(cameraModalEl);
        }

        function isImageFile(file) {
            if (!file) return false;
            if (file.type && file.type.startsWith('image/')) return true;
            const name = (file.name || '').toLowerCase();
            return /\.(jpe?g|png|webp)$/i.test(name);
        }

        function setPhotoFile(file) {
            if (!file || !isImageFile(file)) {
                alert('Please select a valid image file.');
                return false;
            }

            if (file.size > 5 * 1024 * 1024) {
                alert('Photo must be 5 MB or smaller.');
                return false;
            }

            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            photoInput.files = dataTransfer.files;

            const reader = new FileReader();
            reader.onload = function (e) {
                photoPreview.src = e.target.result;
                photoPreview.classList.remove('d-none');
                photoPlaceholder.classList.add('d-none');
                photoUploadCard.classList.add('has-photo');
            };
            reader.readAsDataURL(file);

            return true;
        }

        function stopCamera() {
            if (cameraStream) {
                cameraStream.getTracks().forEach(function (track) {
                    track.stop();
                });
                cameraStream = null;
            }
            if (cameraVideo) {
                cameraVideo.srcObject = null;
            }
        }

        async function startCamera() {
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                return false;
            }

            try {
                stopCamera();
                cameraStream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: { ideal: 'user' },
                        width: { ideal: 1280 },
                        height: { ideal: 720 }
                    },
                    audio: false
                });
                cameraVideo.srcObject = cameraStream;
                return true;
            } catch (error) {
                stopCamera();
                return false;
            }
        }

        function openGalleryPicker() {
            photoInput.removeAttribute('capture');
            photoInput.value = '';
            photoInput.click();
        }

        async function openCameraCapture() {
            if (cameraModal) {
                cameraModal.show();
                const started = await startCamera();
                if (!started) {
                    cameraModal.hide();
                    photoInput.setAttribute('capture', 'user');
                    photoInput.value = '';
                    photoInput.click();
                }
            } else {
                photoInput.setAttribute('capture', 'user');
                photoInput.value = '';
                photoInput.click();
            }
        }

        btnGallery.addEventListener('click', openGalleryPicker);

        btnCamera.addEventListener('click', function () {
            openCameraCapture();
        });

        if (btnCapture) {
            btnCapture.addEventListener('click', function () {
                if (!cameraVideo.videoWidth || !cameraVideo.videoHeight) {
                    alert('Camera is not ready yet. Please wait a moment and try again.');
                    return;
                }

                cameraCanvas.width = cameraVideo.videoWidth;
                cameraCanvas.height = cameraVideo.videoHeight;
                const context = cameraCanvas.getContext('2d');
                context.drawImage(cameraVideo, 0, 0, cameraCanvas.width, cameraCanvas.height);

                cameraCanvas.toBlob(function (blob) {
                    if (!blob) {
                        alert('Could not capture photo. Please try again.');
                        return;
                    }

                    const file = new File([blob], 'camera-photo.jpg', {
                        type: 'image/jpeg',
                        lastModified: Date.now()
                    });

                    if (setPhotoFile(file) && cameraModal) {
                        cameraModal.hide();
                    }
                }, 'image/jpeg', 0.92);
            });
        }

        if (cameraModalEl) {
            cameraModalEl.addEventListener('hidden.bs.modal', stopCamera);
        }

        photoInput.addEventListener('change', function () {
            const file = photoInput.files && photoInput.files[0];
            if (!file) return;
            setPhotoFile(file);
        });

        if (registerForm) {
            registerForm.addEventListener('submit', function (e) {
                if (!photoInput.files || !photoInput.files.length) {
                    e.preventDefault();
                    alert('Please upload or capture your trainee photo before submitting.');
                    photoUploadCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
        }
    })();
</script>
@endpush
