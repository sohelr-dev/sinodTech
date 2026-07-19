<script setup lang="ts">
import { useRouter } from 'vue-router';

const router = useRouter();

const goHome = () => {
    router.push('/dashboard');
};
</script>

<template>
    <div class="error-wrapper">
        <div class="bg-split">
            <div class="bg-yellow"></div>
            <div class="bg-blue"></div>
        </div>

        <div class="container d-flex justify-content-center align-items-center min-vh-100 position-relative">
            <div class="card border-0 shadow-lg error-card text-center">
                <div class="card-body p-5">
                    <div class="error-code mb-4">
                        <h1 class="display-1 fw-bold text-navy">404</h1>
                    </div>

                    <div class="mb-4">
                        <h2 class="fw-bold text-dark">Oops! Page Not Found</h2>
                        <p class="text-muted">
                            The page you are looking for might have been removed, <br>
                            had its name changed, or is temporarily unavailable.
                        </p>
                    </div>

                    <div class="d-grid gap-2">
                        <button @click="goHome" class="btn btn-navy btn-lg shadow">
                            <i class="bi bi-house-door-fill me-2"></i> Back to Dashboard
                        </button>
                        <button @click="$router.go(-1)" class="btn btn-outline-secondary">
                            Go Back
                        </button>
                    </div>

                    <div class="mt-5 pt-3">
                        <p class="small text-muted">Need help? <a href="#"
                                class="text-navy fw-bold text-decoration-none">Contact Support</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.error-wrapper {
    position: relative;
    overflow: hidden;
}

/* Background Matching your Login Design */
.bg-split {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 0;
}

.bg-yellow {
    height: 50%;
    background-color: #ffcc00;
}

.bg-blue {
    height: 50%;
    background-color: #002244;
}

/* Card Styling */
.error-card {
    max-width: 500px;
    width: 100%;
    border-radius: 25px;
    z-index: 5;
}

.text-navy {
    color: #002e5b;
}

.error-code h1 {
    font-size: 8rem;
    letter-spacing: -5px;
    background: linear-gradient(180deg, #002e5b 0%, #004a8f 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.btn-navy {
    background-color: #002e5b;
    color: white;
    border-radius: 12px;
    padding: 12px;
    font-weight: 600;
}

.btn-navy:hover {
    background-color: #001f3d;
    color: white;
}

@media (max-width: 576px) {
    .error-code h1 {
        font-size: 5rem;
    }
}
</style>