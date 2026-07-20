<script setup lang="ts">
import { ref, Transition, watch } from 'vue';

const props = defineProps<{
    isCollapsed: boolean;
    isMobileOpen: boolean;
}>();

const emit = defineEmits(['update:isCollapsed', 'closeMobile']);

const openMenu = ref<string | null>(null);

const toggleMenu = (menuName: string) => {
    //if sidebar collapsed , menu click to sidebar expand
    if (props.isCollapsed) {
        emit('update:isCollapsed', false);
        openMenu.value = menuName;
    } else {
        openMenu.value = openMenu.value === menuName ? null : menuName;
    }
};

//sidebar dropdown 
watch(() => props.isCollapsed, (newVal) => {
    if (newVal) openMenu.value = null;
});
</script>

<template>
    <div id="sidebar" :class="[
        'position-fixed top-0 start-0 vh-100 bg-body border-end shadow-sm',
        { 'sidebar-collapsed': isCollapsed, 'show-mobile': isMobileOpen }
    ]" style="z-index: 1040;">

        <div class="p-3 border-bottom logo-container">
            <div class="d-flex align-items-center gap-2">
                <div class="bg-warning rounded   d-flex align-items-center justify-content-center overflow-hidden shadow-sm"
                    style="width: 42px; height: 42px; padding: 2px;">

                    <span class="fw-bold fs-5 text-white">S</span>
                </div>

                <h5 v-if="!isCollapsed" class="mb-0 fw-bold sidebar-text text-primary">
                    Sinodtech
                </h5>
            </div>
        </div>

        <nav class="p-3">
            <ul class="nav flex-column gap-1">
                <li class="nav-item">
                    <router-link to="/dashboard" class="nav-link d-flex align-items-center gap-3 rounded-3 py-2"
                        @click="emit('closeMobile')">
                        <i class="bi bi-speedometer2 fs-5"></i>
                        <span v-if="!isCollapsed" class="sidebar-text">Dashboard</span>
                    </router-link>
                </li>

                <!-- ─── Products ─────────────────────────────── -->
                <li class="nav-item">
                    <router-link to="/products" class="nav-link d-flex align-items-center gap-3 rounded-3 py-2"
                        @click="emit('closeMobile')">
                        <i class="bi bi-box-seam fs-5"></i>
                        <span v-if="!isCollapsed" class="sidebar-text">Products</span>
                    </router-link>
                </li>

                <!-- ─── Sales ─────────────────────────────────── -->
                <li class="nav-item">
                    <router-link to="/sales" class="nav-link d-flex align-items-center gap-3 rounded-3 py-2"
                        @click="emit('closeMobile')">
                        <i class="bi bi-cart-check fs-5"></i>
                        <span v-if="!isCollapsed" class="sidebar-text">Sales</span>
                    </router-link>
                </li>

                <!-- ─── CRM ───────────────────────────────────── -->
                <li class="nav-item">
                    <router-link to="/crm" class="nav-link d-flex align-items-center gap-3 rounded-3 py-2"
                        @click="emit('closeMobile')">
                        <i class="bi bi-people-fill fs-5"></i>
                        <span v-if="!isCollapsed" class="sidebar-text">CRM</span>
                    </router-link>
                </li>

                <!-- ─── Users ─────────────────────────────────── -->
                <li class="nav-item">
                    <div class="nav-link d-flex align-items-center gap-3 rounded-3 py-2 cursor-pointer"
                        :class="{ 'active-dropdown': openMenu === 'users' }" @click="toggleMenu('users')">
                        <i class="bi bi-shield-lock fs-5"></i>
                        <span v-if="!isCollapsed" class="sidebar-text">Users</span>
                        <i v-if="!isCollapsed"
                            :class="['bi bi-chevron-right ms-auto small transition-icon', { 'rotate-90': openMenu === 'users' }]"></i>
                    </div>
                    <Transition name="expand">
                        <div class="submenu-wrapper" v-show="openMenu === 'users' && !isCollapsed">
                            <ul class="nav flex-column ms-4 gap-1 mt-1">
                                <li><router-link to="/users" class="nav-link py-2 small"
                                        @click="emit('closeMobile')">All Users</router-link></li>
                                <li><router-link to="/add-user" class="nav-link py-2 small"
                                        @click="emit('closeMobile')">Add User</router-link></li>
                                <li><router-link to="/roles" class="nav-link py-2 small"
                                        @click="emit('closeMobile')">User Roles</router-link></li>
                            </ul>
                        </div>
                    </Transition>
                </li>

                <!-- ─── Employees ─────────────────────────────── -->
                <li class="nav-item">
                    <router-link to="/employees" class="nav-link d-flex align-items-center gap-3 rounded-3 py-2"
                        @click="emit('closeMobile')">
                        <i class="bi bi-person-badge fs-5"></i>
                        <span v-if="!isCollapsed" class="sidebar-text">Employees</span>
                    </router-link>
                </li>

                <!-- ─── Inventory ──────────────────────────────── -->
                <li class="nav-item">
                    <router-link to="/inventory" class="nav-link d-flex align-items-center gap-3 rounded-3 py-2"
                        @click="emit('closeMobile')">
                        <i class="bi bi-boxes fs-5"></i>
                        <span v-if="!isCollapsed" class="sidebar-text">Inventory</span>
                    </router-link>
                </li>


                <!-- ─── Branches ──────────────────────────────── -->
                <li class="nav-item">
                    <router-link to="/branches" class="nav-link d-flex align-items-center gap-3 rounded-3 py-2"
                        @click="emit('closeMobile')">
                        <i class="bi bi-building fs-5"></i>
                        <span v-if="!isCollapsed" class="sidebar-text">Branches</span>
                    </router-link>
                </li>
            </ul>
        </nav>
    </div>
</template>

<style scoped>
#sidebar {
    width: 280px;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    overflow-y: auto;
}

#sidebar.sidebar-collapsed {
    width: 80px;
}

.logo-container {
    height: 71px;
    display: flex;
    align-items: center;
}

.cursor-pointer {
    cursor: pointer;
}

.rotate-90 {
    transform: rotate(90deg);
}

.nav-link {
    color: var(--bs-body-color);
    white-space: nowrap;
    text-decoration: none;
}

.nav-link:hover,
.router-link-active {
    background: rgba(0, 45, 98, 0.1);
    color: #002D62 !important;
}

.active-dropdown {
    color: #002D62;
    font-weight: 600;
}

.expand-enter-active,
.expand-leave-active {
    transition: all 0.4s ease-in-out;
    max-height: 200px;
    overflow: hidden;
}

.expand-enter-from,
.expand-leave-to {
    max-height: 0;
    opacity: 0;
    transform: translateY(-10px);
}

.transition-icon {
    transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}


@media (max-width: 768px) {
    #sidebar {
        transform: translateX(-100%);
    }

    #sidebar.show-mobile {
        transform: translateX(0);
    }
}
</style>