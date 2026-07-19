<script setup lang="ts">
import { ref, reactive } from 'vue';
import { useRouter } from 'vue-router';
import api from '../../../config/config';

const router = useRouter();
const loading = ref(false);
const errors = ref<any>({});
const showPassword = ref(false);
const showPasswordConfirm = ref(false);
const getEmail = localStorage.getItem('email');
const getToken = localStorage.getItem('token');
const form = reactive({
    email: getEmail,
    token: getToken,
    password: '',
    password_confirmation: '',
    code: ''
});
async function handleResetPassword() {
    loading.value = true;
    try {
        const response = await api.post('auth/reset-password', form);
        if (response.data.success === true) {
            alert('Passwoord updated successfully. Please login with your new password.');
            router.push('/login');
        }
    } catch (error: any) {
        console.log(error.response)
        if (error.response && error.response.status === 422) {
            errors.value = error.response.data.errors;
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
                        <i class="bi bi-lock-fill text-warning display-4"></i>
                        <h2 class="fw-bold text-dark mt-2">New Password</h2>
                        <p class="text-muted small">Please set your new password.</p>
                    </div>
                    <form @submit.prevent="handleResetPassword">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">New Password</label>
                            <div class="input-group">
                                <input :type="showPassword ? 'text' : 'password'" v-model="form.password"
                                    class="form-control form-control-lg bg-light border-0"
                                    :class="{ 'is-invalid': errors.password }" placeholder="••••••••"
                                    autocomplete="new-password" required />
                                <button class="btn btn-light border-0" type="button"
                                    @click="showPassword = !showPassword">
                                    <i :class="showPassword ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                                </button>
                                <div v-if="errors.password" class="text-danger mt-1 small">{{ errors.password[0] }}
                                </div>

                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted text-uppercase">Confirm
                                Password</label>
                            <div class="input-group">

                                <input :type="showPasswordConfirm ? 'text' : 'password'"
                                    v-model="form.password_confirmation"
                                    class="form-control form-control-lg bg-light border-0" placeholder="••••••••"
                                    required autocomplete="password-confirmation" />
                                <button class="btn btn-light border-0" type="button"
                                    @click="showPasswordConfirm = !showPasswordConfirm">
                                    <i :class="showPasswordConfirm ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                                </button>
                                <div v-if="errors.password" class="text-danger mt-1 small">{{ errors.password[0] }}
                                </div>

                            </div>
                            <div v-if="errors.password" class="text-danger mt-1 small">{{ errors.password[0] }}
                            </div>
                        </div>
                        <button type="submit" class="btn btn-navy btn-lg w-100 shadow" :disabled="loading">
                            {{ loading ? 'Updating...' : 'Update Password' }}
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
