<template>
    <div class="container my-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="fas fa-home me-1"></i> Home</a></li>
                <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-users me-1"></i> Users</li>
            </ol>
        </nav>

        <!-- Card -->
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-users me-2"></i> User List
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>KYC Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="user in users" :key="user.id">
                                <td>{{ user.id }}</td>
                                <td>{{ user.name }}</td>
                                <td>{{ user.email }}</td>
                                <td>{{ user.role }}</td>
                                <td>
                                    <span :class="{
                                        'badge bg-warning': user.kyc_status == 'pending',
                                        'badge bg-success': user.kyc_status == 'verified',
                                        'badge bg-danger': user.kyc_status == 'rejected'
                                    }">
                                        {{ user.kyc_status }}
                                    </span>
                                </td>
    
                                <td class="d-flex gap-2">
                                    <router-link :to="`users/user-details/${user.id}`" class="btn btn-primary">
                                        view
                                    </router-link>
                                    <router-link :to="`users/user-edit/${user.id}`" class="btn btn-outline-primary">
                                        Edit
                                    </router-link>
    
                                </td>
                            </tr>
                            <tr v-if="users.length === 0">
                                <td colspan="5" class="text-center py-3">No users found.</td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import type { UserType } from '@/components/interfaces/users.interfaces';
import api from '@/config/config';


let users = ref<UserType[]>([]);
// console.log(users);
api.get('/users')
    .then((res) => {
        console.log(res.data);
        users.value = res.data;
    })
    .catch((err) => {
        console.log(err);
    })
</script>

<style>
.table th,
.table td {
    vertical-align: middle;
}

.breadcrumb a {
    text-decoration: none;
}
</style>
