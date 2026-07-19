<script setup lang="ts">
import { onMounted, reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import { defaultUser } from '../../interfaces/users.interfaces';
import { useAuthStore } from '../../../store/auth';
import api from '../../../config/config';

const form = reactive({
  ...defaultUser,
  remember_me: false
});

const router = useRouter();
const auth = useAuthStore();
const loading = ref(false);
const errors = ref<any>({});

function handleLogin() {
  loading.value = true;
  errors.value = {};

  api.post('auth/login', form)
    .then((response) => {
      if (response.data.status === 'success') {
        const { user, token, expires_at } = response.data.data;
        auth.setAuth({ user, token, expires_at });
        router.replace('/dashboard');
      } else {
        alert(response.data.message || 'Login failed.');
      }
    })
    .catch((error) => {
      if (error.response && error.response.status === 422) {
        errors.value = error.response.data.errors;
      } else {
        alert('Something went wrong. Please try again.');
      }
    })
    .finally(() => {
      loading.value = false;
    });
}

onMounted(() => {
  document.title = "Welcome Back | FNF Trip";
});

</script>

<template>
  <div class="login-wrapper">
    <div class="bg-split">
      <div class="bg-yellow"></div>
      <div class="bg-blue"></div>
    </div>

    <div class="container d-flex flex-column justify-content-center align-items-center min-vh-100 position-relative">

      <div class="logo-container shadow login-logo d-flex align-items-center justify-content-center bg-white">
        <h3 class="fw-bold m-0 text-primary">Sinodtech</h3>
      </div>

      <div class="card border-0 shadow-lg login-card">
        <div class="card-body p-4 p-md-5">
          <div class="text-center mb-4">
            <h2 class="fw-bold text-dark mb-1">Welcome Back</h2>
            <p class="text-muted small">Please enter your details to sign in</p>
          </div>

          <div class="role-selector mb-4 p-3 rounded-3 bg-light">
            <p class="text-center x-small text-uppercase fw-bold text-muted mb-3"
              style="letter-spacing: 1px; font-size: 10px;">Fast Login Roles</p>
            <div class="row g-2">
              <div class="col-6">
                <button type="button"
                  class="btn btn-white btn-sm w-100 shadow-sm border text-start d-flex align-items-center"
                  @click="form.email = 'admin@gmail.com'; form.password = 'password'">
                  <i class="bi bi-person-circle me-2 text-primary"></i> Admin
                </button>
              </div>
              <div class="col-6">
                <button type="button"
                  class="btn btn-white btn-sm w-100 shadow-sm border text-start d-flex align-items-center"
                  @click="form.email = 'manager@gmail.com'; form.password = 'password'">
                  <i class="bi bi-people me-2 text-secondary"></i> Manager
                </button>
              </div>
              <div class="col-6">
                <button type="button"
                  class="btn btn-white btn-sm w-100 shadow-sm border text-start d-flex align-items-center"
                  @click="form.email = 'sales@gmail.com'; form.password = 'password'">
                  <i class="bi bi-cart me-2 text-warning"></i> Sales
                </button>
              </div>
              <div class="col-6">
                <button type="button"
                  class="btn btn-white btn-sm w-100 shadow-sm border text-start d-flex align-items-center"
                  @click="form.email = 'editor@gmail.com'; form.password = 'password'">
                  <i class="bi bi-pencil-square me-2 text-danger"></i> Editor
                </button>
              </div>
            </div>
          </div>

          <form @submit.prevent="handleLogin">
            <div class="mb-3">
              <label class="form-label small fw-bold text-muted text-uppercase">Email Address</label>
              <input type="email" v-model="form.email" class="form-control form-control-lg bg-light border-0"
                placeholder="admin@gmail.com" />
              <div v-if="errors.email" class="text-danger  mt-1" data-aos="zoom-in-up">{{ errors.email[0] }}</div>
            </div>

            <div class="mb-3">
              <label class="form-label small fw-bold text-muted text-uppercase">Password</label>
              <input type="password" v-model="form.password" class="form-control form-control-lg bg-light border-0"
                placeholder="••••••••" />
              <div v-if="errors.password" class="text-danger mt-1" data-aos="zoom-in-up">{{ errors.password[0] }}</div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
              <div class="form-check">
                <input type="checkbox" class="form-check-input" id="rememberMe" v-model="form.remember_me">
                <label class="form-check-label small text-muted" for="rememberMe">Remember me</label>
              </div>
              <a href="#" class="small text-decoration-none fw-bold text-primary">Forgot password?</a>
            </div>

            <button type="submit" class="btn btn-navy btn-lg w-100 shadow" :disabled="loading">
              <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
              {{ loading ? 'Processing...' : 'Login to Dashboard' }}
            </button>
          </form>

          <div class="text-center mt-4 pt-2">
            <p class="small text-muted mb-1">Need help or a new account?</p>
            <a href="#" class="text-decoration-none fw-bold text-dark d-flex align-items-center justify-content-center">
              <i class="bi bi-geo-alt-fill text-warning me-1"></i> Please contact administration
            </a>
          </div>
        </div>
      </div>

      <p class="text-white-50 x-small text-uppercase mt-4" style="letter-spacing: 2px; font-size: 10px;">
        Powered by FNF Trip Global Network
      </p>
    </div>
  </div>
</template>

<style scoped>
/* Background CSS */
.login-wrapper {
  position: relative;
  overflow: hidden;
}

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
  /* logo color */
}

.bg-blue {
  height: 50%;
  background-color: #002244;
  /* logo under color */
}

/* Logo styling */
.logo-container {
  width: 80px;
  height: 80px;
  background: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 10;
  margin-bottom: -40px;
  /* set logo with cart */
  padding: 15px;
}

/* Card styling */
.login-card {
  max-width: 480px;
  width: 100%;
  border-radius: 25px;
  z-index: 5;
}

/* Form styling */
.form-control-lg {
  font-size: 0.95rem;
  padding: 12px 15px;
  border-radius: 10px;
}

.form-control:focus {
  background-color: #f8f9fa;
  box-shadow: none;
  border: 1px solid #ffcc00;
}

/* Button & Custom UI */
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

.btn-white {
  background-color: #ffffff;
  font-size: 12px;
  font-weight: 600;
  border-radius: 8px;
}

.x-small {
  font-size: 12px;
}

@media (max-width: 576px) {
  .login-card {
    border-radius: 15px;
  }
}
.login-logo {
  width: 80px;
  height: 80px;
  object-fit: contain;

  animation: zoomIn 1s ease;
}

@keyframes zoomIn {
  0% {
    transform: scale(0.5);
    opacity: 0;
  }

  100% {
    transform: scale(1);
    opacity: 1;
  }
}
</style>