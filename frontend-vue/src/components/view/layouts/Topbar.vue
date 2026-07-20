<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useAuthStore } from '../../../store/auth';
import { useRouter } from 'vue-router';
import { Dropdown } from 'bootstrap';
import api from '../../../config/config';

defineProps<{ isDarkMode: boolean }>();
const router = useRouter();
const emit = defineEmits(['toggleSidebar', 'toggleTheme']);

const auth = useAuthStore();

// Dynamic user display
const userName    = computed(() => auth.user?.name ?? 'User');
const userRole    = computed(() => auth.user?.role ?? '');
const userInitial = computed(() => userName.value.charAt(0).toUpperCase());

// Notifications — built from real system data
interface Notification {
  id: number;
  title: string;
  message: string;
  time: string;
  icon: string;
  color: string;
  link: string;
}

const notifications = ref<Notification[]>([]);
const unreadCount   = ref(0);
const loadingNotifs = ref(false);

async function loadNotifications() {
  loadingNotifs.value = true;
  const items: Notification[] = [];
  let id = 1;

  try {
    // 1. Lost customers count
    const custRes = await api.get('customers', { params: { lost: 1 } });
    const lost = custRes.data?.data ?? [];
    if (lost.length > 0) {
      items.push({
        id: id++,
        title: 'Lost Customers Alert',
        message: `${lost.length} customer(s) inactive for 90+ days need follow-up.`,
        time: 'CRM → Lost Customers',
        icon: 'bi-person-x',
        color: 'text-danger',
        link: '/crm',
      });
    }

    // 2. Low-stock items
    const lowRes = await api.get('inventory/low-stock');
    const lowItems = lowRes.data?.data ?? [];
    if (lowItems.length > 0) {
      items.push({
        id: id++,
        title: 'Low Stock Warning',
        message: `${lowItems.length} inventory item(s) are running low (≤5 units).`,
        time: 'Inventory',
        icon: 'bi-exclamation-triangle',
        color: 'text-warning',
        link: '/inventory',
      });
    }

    // 3. Pending assignments
    if (userRole.value === 'sales' || userRole.value === 'admin' || userRole.value === 'manager') {
      const asgRes = await api.get('assignments');
      const pending = (asgRes.data?.data ?? []).filter((a: any) =>
        a.status === 'assigned' &&
        (userRole.value !== 'sales' || a.employee_id === auth.user?.id)
      );
      if (pending.length > 0) {
        items.push({
          id: id++,
          title: 'Pending Assignments',
          message: `${pending.length} customer(s) assigned and awaiting follow-up.`,
          time: 'CRM → Assignments',
          icon: 'bi-person-plus',
          color: 'text-primary',
          link: '/crm',
        });
      }
    }
  } catch {
    // silently fail — notifications are best-effort
  }

  notifications.value  = items;
  unreadCount.value    = items.length;
  loadingNotifs.value  = false;
}

function clearNotifications() {
  notifications.value = [];
  unreadCount.value   = 0;
}

function goToNotification(note: Notification) {
  // Close the Bootstrap dropdown first
  const btn = document.querySelector('[data-bs-toggle="dropdown"].btn-light.position-relative') as HTMLElement;
  if (btn) {
    const dd = Dropdown.getInstance(btn);
    if (dd) dd.hide();
  }
  router.push(note.link);
}

function handleLogout() {
  auth.logout();
}

onMounted(() => {
  loadNotifications();
});
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

          <!-- Notifications -->
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
                <div v-if="loadingNotifs" class="p-4 text-center text-muted">
                  <div class="spinner-border spinner-border-sm text-primary mb-2"></div>
                  <div class="small">Loading...</div>
                </div>
                <template v-else-if="notifications.length > 0">
                  <button
                    v-for="note in notifications" :key="note.id"
                    class="dropdown-item p-3 border-bottom d-flex gap-3 text-wrap w-100 text-start"
                    @click="goToNotification(note)">
                    <div
                      :class="['flex-shrink-0 bg-light rounded-circle d-flex align-items-center justify-content-center', note.color]"
                      style="width: 40px; height: 40px; flex-shrink:0;">
                      <i :class="['bi', note.icon, 'fs-5']"></i>
                    </div>
                    <div class="flex-grow-1">
                      <div class="fw-semibold small">{{ note.title }}</div>
                      <div class="text-muted small mb-1">{{ note.message }}</div>
                      <div class="text-primary" style="font-size: 0.7rem;">
                        <i class="bi bi-arrow-right-circle me-1"></i>{{ note.time }}
                      </div>
                    </div>
                  </button>
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

          <!-- User profile dropdown -->
          <div class="dropdown">
            <button class="btn btn-light d-flex align-items-center gap-2" data-bs-toggle="dropdown">
              <div class="bg-primary bg-gradient text-white rounded-2 d-flex align-items-center justify-content-center fw-bold"
                style="width: 35px; height: 35px; font-size: 0.95rem;">
                {{ userInitial }}
              </div>
              <div class="text-start d-none d-md-block">
                <div class="fw-semibold small">{{ userName }}</div>
                <div class="text-muted text-capitalize" style="font-size: 0.75rem;">{{ userRole }}</div>
              </div>
              <i class="bi bi-chevron-down d-none d-md-block"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
              <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a></li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <button class="dropdown-item text-danger" @click="handleLogout">
                  <i class="bi bi-box-arrow-right me-2"></i>Logout
                </button>
              </li>
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
  z-index: 1050;
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

.notification-list::-webkit-scrollbar { width: 4px; }
.notification-list::-webkit-scrollbar-thumb {
  background: rgba(0, 45, 98, 0.1);
  border-radius: 10px;
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

.btn-light {
  border-color: transparent;
}

.btn-light:hover {
  background-color: rgba(0, 45, 98, 0.05);
}

@media (max-width: 767px) {
  .navbar { height: 60px; }
}
</style>