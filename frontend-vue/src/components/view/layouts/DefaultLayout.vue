<script setup lang="ts">
import { computed, ref, onMounted } from 'vue';
import SidebarVue from './Sidebar.vue';
import TopbarVue from './Topbar.vue';
import { useAuthStore } from '../../../store/auth';

const auth = useAuthStore();
const isAuth = computed(() => auth.isAuthenticated);

const isCollapsed = ref(false);
const isMobileOpen = ref(false);
const isDarkMode = ref(localStorage.getItem('theme') === 'dark');

const handleToggle = () => {
  if (window.innerWidth <= 768) {
    isMobileOpen.value = !isMobileOpen.value;
  } else {
    isCollapsed.value = !isCollapsed.value;
  }
};

const toggleTheme = () => {
  isDarkMode.value = !isDarkMode.value;
  const theme = isDarkMode.value ? 'dark' : 'light';
  document.documentElement.setAttribute('data-bs-theme', theme);
  localStorage.setItem('theme', theme);
};

onMounted(() => {
  const savedTheme = localStorage.getItem('theme') || 'light';
  document.documentElement.setAttribute('data-bs-theme', savedTheme);
});
</script>

<template>
  <div v-if="isAuth" class="admin-layout">
    <SidebarVue v-model:is-collapsed="isCollapsed" :is-mobile-open="isMobileOpen"
      @close-mobile="isMobileOpen = false" />

    <div v-if="isMobileOpen" class="mobile-overlay d-md-none" @click="isMobileOpen = false"></div>

    <div class="main-wrapper" :class="{ 'sidebar-expanded': !isCollapsed, 'sidebar-collapsed-padding': isCollapsed }">
      <TopbarVue :is-dark-mode="isDarkMode" @toggle-sidebar="handleToggle" @toggle-theme="toggleTheme" />

      <main class="content-body p-4">
        <router-view />
      </main>
    </div>
  </div>

  <div v-else>
    <router-view />
  </div>
</template>

<style scoped>
.admin-layout {
  display: flex;
  min-height: 100vh;
  background-color: var(--bs-body-bg);
}

.main-wrapper {
  flex: 1;
  display: flex;
  flex-direction: column;
  min-width: 0;
  transition: padding-left 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

@media (min-width: 769px) {
  .sidebar-expanded {
    padding-left: 280px;
  }

  .sidebar-collapsed-padding {
    padding-left: 80px;
  }
}

:deep(.navbar) {
  width: 100%;
  z-index: 1030;
}

.mobile-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  z-index: 1035;
}
</style>