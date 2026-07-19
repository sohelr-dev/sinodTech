<script setup lang="ts">
import { ref } from 'vue';
import { useAuthStore } from '../../../store/auth';

defineProps<{ isDarkMode: boolean }>();
const emit = defineEmits(['toggleSidebar', 'toggleTheme']);
//notification
const notifications = ref([
  { id: 1, title: 'New Booking', message: 'Coxs Bazar tour booked by Rakib', time: '5 min ago', icon: 'bi-calendar-check', color: 'text-primary' },
  { id: 2, title: 'Payment Received', message: '$250 received from Tanvir', time: '15 min ago', icon: 'bi-credit-card', color: 'text-success' },
  { id: 3, title: 'System Update', message: 'Server maintenance at 12:00 AM', time: '1 hour ago', icon: 'bi-exclamation-triangle', color: 'text-warning' },
]);

const unreadCount = ref(notifications.value.length);
function clearNotifications() {
  notifications.value = [];
  unreadCount.value = 0;
}


const auth = useAuthStore();
function handleLogout() {
  auth.logout();
}
</script>

<template>
  <nav class="navbar navbar-expand navbar-light bg-body border-bottom sticky-top shadow-sm">
    <div class="container-fluid px-4">
      <div class="d-flex align-items-center justify-content-between w-100">
        <div class="d-flex align-items-center">
          <button class="btn btn-light me-2" @click="emit('toggleSidebar')">
            <i class="bi bi-list fs-5"></i>
          </button>

          <form class="d-none d-lg-flex ms-2" style="width: 250px;">
            <div class="input-group input-group-sm">
              <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
              <input type="search" class="form-control bg-light border-start-0" placeholder="Search...">
            </div>
          </form>
        </div>

        <div class="d-flex align-items-center gap-1 gap-md-2">

          <button class="btn btn-light" @click="emit('toggleTheme')">
            <i :class="isDarkMode ? 'bi bi-sun' : 'bi bi-moon-stars'"></i>
          </button>
          <!-- Notification -->
          <div class="dropdown">
            <button class="btn btn-light position-relative me-2" data-bs-toggle="dropdown">
              <i class="bi bi-bell fs-5"></i>
              <span v-if="unreadCount > 0"
                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                style="font-size: 0.65rem;">
                {{ unreadCount }}
              </span>
            </button>


            <div class="dropdown-menu dropdown-menu-end shadow border-0 p-0 notification-dropdown">
              <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-light rounded-top">
                <h6 class="mb-0 fw-bold">Notifications</h6>
                <span class="badge bg-primary-subtle text-primary">{{ unreadCount }} New</span>
              </div>

              <div class="notification-list" style="max-height: 350px; overflow-y: auto;">
                <template v-if="notifications.length > 0">
                  <a v-for="note in notifications" :key="note.id" href="#"
                    class="dropdown-item p-3 border-bottom d-flex gap-3 text-wrap">
                    <div
                      :class="['flex-shrink-0 bg-light rounded-circle d-flex align-items-center justify-content-center', note.color]"
                      style="width: 40px; height: 40px;">
                      <i :class="['bi', note.icon, 'fs-5']"></i>
                    </div>
                    <div>
                      <div class="fw-semibold small">{{ note.title }}</div>
                      <div class="text-muted small mb-1">{{ note.message }}</div>
                      <div class="text-muted" style="font-size: 0.7rem;">{{ note.time }}</div>
                    </div>
                  </a>
                </template>
                <div v-else class="p-4 text-center text-muted">
                  <i class="bi bi-bell-slash fs-1 d-block mb-2"></i>
                  No new notifications
                </div>
              </div>

              <div class="p-2 border-top text-center bg-light rounded-bottom">
                <button class="btn btn-link btn-sm text-decoration-none" @click="clearNotifications">Clear All</button>
              </div>
            </div>
          </div>
          <!-- user profile dropdown -->
          <div class="dropdown">
            <button class="btn btn-light d-flex align-items-center gap-2" data-bs-toggle="dropdown">
              <div class="bg-primary bg-gradient text-white rounded-2 p-1" style="width: 35px; height: 35px;">
                <span class="d-flex align-items-center justify-content-center fw-bold">A</span>
              </div>
              <div class="text-start d-none d-md-block">
                <div class="fw-semibold small">Admin</div>
                <div class="text-muted" style="font-size: 0.75rem;">Super Admin</div>
              </div>
              <i class="bi bi-chevron-down d-none d-md-block"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
              <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><button class="dropdown-item text-danger" @click="handleLogout"><i
                    class="bi bi-box-arrow-right me-2"></i>Logout</button></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>
<style scoped>
.notification-dropdown {
  width: 320px;
  max-width: 320px;
  margin-top: 10px !important;
}

@media (max-width: 768px) {
  .notification-dropdown {
    width: 90vw;
    max-width: 350px;
    position: absolute;
    right: -80px !important;
  }
}

.notification-list {
  max-height: 350px;
  overflow-y: auto;
}

.notification-list::-webkit-scrollbar {
  width: 4px;
}

.notification-list::-webkit-scrollbar-thumb {
  background: rgba(0, 45, 98, 0.1);
  border-radius: 10px;
}

.notification-dropdown {
  z-index: 1050;
}

.dropdown-item:active {
  background-color: rgba(0, 45, 98, 0.05);
  color: #002D62;
}

.navbar {
  height: 70px;
  padding: 0;
  backdrop-filter: blur(10px);
}

/* tablet control */
@media (max-width: 768px) {
  .notification-dropdown {
    width: 90vw;
    max-width: 320px;
    position: absolute;
    right: -60px !important;
    left: auto !important;
    margin-top: 10px !important;
  }
}

.btn-light {
  border-color: transparent;
}

.btn-light:hover {
  background-color: rgba(0, 45, 98, 0.05);
}

@media (max-width: 767px) {
  .navbar {
    height: 60px;
  }
}
</style>