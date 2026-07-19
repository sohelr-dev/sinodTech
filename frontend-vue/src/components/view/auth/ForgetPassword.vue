<script setup lang="ts">
import { ref, reactive } from 'vue';
import { useRouter } from 'vue-router';
import api from '../../../config/config';

const router = useRouter();
const loading = ref(false);
const errors = ref<any>({});

const form = reactive({
    email: '',
    token: '', // OTP code
    password: '',
    password_confirmation: '',
    code: ''
});

// email submit 
async function handleForgotPassword() {
    loading.value = true;
    errors.value = {};
    try{
        const response = await api.post('auth/forgot-password', { email: form.email });
         console.log(response.data);
         if (response.data.success === true) {
            
            localStorage.setItem('email',response.data.email);
            localStorage.setItem('token',response.data.token);
            alert(JSON.stringify(response.data.message));
            //go to otp step
             errors.value = {};
             router.push('/varify-code');
         }
        }catch (error:any){
            console.error("Full Error:", error.response);

            if (error.response && error.response?.status === 422) {
                errors.value = error.response.data.errors;
            } else if (error.response && error.response.status === 500) {
                alert('There is a problem with the server (Internal Server Error). Please try again in a few minutes.');
            } else {
                alert('Something went wrong. Please check your internet connection.');
            }
        }finally{
            loading.value = false;
        };
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
                        <i class="bi bi-envelope-at text-warning display-4"></i>
                        <h2 class="fw-bold text-dark mt-2">Forgot Password</h2>
                        <p class="text-muted small">Please enter your email address to receive a reset code and link</p>
                    </div>
                    <form @submit.prevent="handleForgotPassword">
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted text-uppercase">Email Address</label>
                            <input type="email" v-model="form.email"
                                class="form-control form-control-lg bg-light border-0" placeholder="admin@gmail.com"
                                required />
                            <div v-if="errors.email" class="text-danger mt-1 small">{{ errors.email[0] }}</div>
                        </div>
                        <button type="submit" class="btn btn-navy btn-lg w-100 shadow" :disabled="loading">
                            {{ loading ? 'Processing...' : 'Send Reset Code' }}
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