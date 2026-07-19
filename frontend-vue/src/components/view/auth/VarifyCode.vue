<script setup lang="ts">
import { ref, reactive } from 'vue';
import { useRouter } from 'vue-router';
import api from '../../../config/config';

const router = useRouter();
const loading = ref(false);
const errors = ref<any>({});
const email = localStorage.getItem('email');

const form = reactive({
    email: '',
    token: '', // OTP code
    password: '',
    password_confirmation: '',
    code: ''
});

// otp verify
async function handleVerifyCode() {
    loading.value = true;
    try {
        const response = await api.post('auth/verify-code', {
            email: email,
            code: form.code
        });
        console.log(response.data + 'otp verify');
        if (response.data.success === true) {
            alert(JSON.stringify(response.data.message));
            router.push('/change-password');
            errors.value={};
        }
    } catch (error: any) {
        if (error.response && error.response.status === 422) {
            alert(error.response.data.message || 'Invaild OTP code . Please Try again.')
        } else {
            alert('Server error. Please try again later.')
        }

    } finally {
        loading.value = false;
    }
}
</script>
<template>
    <div class="login-wrapper">
        <div class="bg-split">
            <div class="bg-yellow"></div>
            <div class="bg-blue"></div>
        </div>

        <div
            class="container d-flex flex-column justify-content-center align-items-center min-vh-100 position-relative">

            <div class="card border-0 shadow-lg login-card">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-shield-check text-warning display-4"></i>
                        <h2 class="fw-bold text-dark mt-2">Verify Code</h2>
                        <p class="text-muted small">We send a verification code to your email. Please enter the code
                            sent to <b>{{ email }}</b>.</p>
                    </div>
                    <form @submit.prevent="handleVerifyCode">
                        <div class="mb-4">
                            <input type="text" v-model="form.code"
                                class="form-control form-control-lg bg-light border-0 text-center fw-bold"
                                placeholder="OTP Code" maxlength="6" required />
                        </div>
                        <button type="submit" class="btn btn-navy btn-lg w-100 shadow" :disabled="loading">
                            {{ loading ? 'Verifying...' : 'Verify code' }}
                        </button>
                    </form>
                    <div class="text-center mt-4">
                        <router-link to="/login" class="text-decoration-none small fw-bold text-primary">
                            <i class="bi bi-arrow-left me-1"></i> Back to Login
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
@import '../../../../public/assets/css/gustLayout.css';
</style>
