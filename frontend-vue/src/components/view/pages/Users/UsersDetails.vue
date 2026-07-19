<template>
    <div :class="['container-md', 'container-fluid', 'my-5']">
        <!-- Breadcrumb + Back -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-white p-2 rounded shadow-sm">
                <li class="breadcrumb-item"><router-link to="/"><i class="fas fa-home me-1"></i> Home</router-link></li>
                <li class="breadcrumb-item"><router-link to="/users"><i class="fas fa-users me-1"></i>
                        Users</router-link></li>
                <li class="breadcrumb-item active" aria-current="page">User Details</li>
            </ol>
        </nav>

        <!-- Loading / Error -->
        <div v-if="loading" class="text-center py-5">
            <div class="spinner-border" role="status" aria-hidden="true"></div>
            <div class="mt-2">Loading user...</div>
        </div>

        <div v-else-if="error" class="alert alert-danger">
            <i class="fas fa-exclamation-triangle me-2"></i> {{ error }}
        </div>

        <!-- User Details Card -->
        <div v-else class="card shadow-sm">
            <div class="card-body">
                <div class="row g-4">
                    <!-- Left: Profile -->
                    <div class="col-lg-4 col-md-5">
                        <div class="text-center">
                            <!-- <img :src="avatarUrl" class="rounded-circle mb-3" alt="avatar"
                                style="width:120px; height:120px; object-fit:cover;"> -->
                            <h4 class="mb-0">{{ user?.name }}</h4>
                            <small class="text-muted d-block mb-2">{{ user?.email }}</small>

                            <div class="d-grid gap-2">
                                <router-link to="/users" class="btn btn-outline-primary" >
                                    <i class="fas fa-arrow-left me-1"></i> Back
                                </router-link>
                                <div class="d-flex justify-content-center gap-2">
                                    <router-link to="" class="btn btn-sm btn-success" >
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </router-link>
                                    <router-link to="" class="btn btn-sm btn-danger" >
                                        <i class="fas fa-trash-alt me-1"></i> Delete
                                    </router-link>
                                </div>
                            </div>
                        </div>

                        <!-- Quick stats -->
                        <div class="mt-4">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <small class="text-muted">Role</small>
                                    <span class="fw-semibold">{{ user?.role_id }}</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <small class="text-muted">KYC</small>
                                    <span class="kycBadgeClass">{{ user?.kyc_status }}</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <small class="text-muted">Phone</small>
                                    <span>{{ user?.phone || '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Details -->
                    <div class="col-lg-8 col-md-7">
                        <h5 class="mb-3">Account Details</h5>

                        <div class="row">
                            <div class="col-sm-6 mb-3">
                                <div class="small text-muted">Account No</div>
                                <div class="fw-semibold">{{ user?.meta?.department || '-' }}</div>
                            </div>
                            <!-- <div class="col-sm-6 mb-3">
                                <div class="small text-muted">Branch</div>
                                <div class="fw-semibold">{{ meta.branch || '-' }}</div>
                            </div> -->
                            <!-- <div class="col-sm-6 mb-3">
                                <div class="small text-muted">Balance</div>
                                <div class="fw-semibold">৳ {{ formattedBalance }}</div>
                            </div> -->
                            <!-- <div class="col-sm-6 mb-3">
                                <div class="small text-muted">Joined</div>
                                <div class="fw-semibold">{{ formattedDate(user.created_at) }}</div>
                            </div> -->
                        </div>

                        <hr />

                        <h6 class="mb-3">More Info</h6>
                        <!-- <div class="row">
                            <div class="col-sm-6 mb-2">
                                <div class="small text-muted">Meta JSON</div>
                                <pre class="small bg-light p-2 rounded"
                                    style="max-height:160px; overflow:auto;">{{ prettyMeta }}</pre>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <div class="small text-muted">Email Verified</div>
                                <div class="fw-semibold">{{ user.email_verified_at ?
                                    formattedDate(user.email_verified_at) : 'Not verified' }}</div>
                            </div>
                        </div> -->

                        <hr />

                        <!-- Optional: Recent transactions (if provided in user.transactions) -->
                        <h6 class="mb-3">Recent Transactions</h6>
                        <!-- <div v-if="transactions && transactions.length" class="table-responsive">
                            <table class="table table-sm table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Txn ID</th>
                                        <th>Type</th>
                                        <th class="text-end">Amount</th>
                                        <th class="text-end">Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="t in transactions" :key="t.id">
                                        <td>{{ formattedDate(t.date) }}</td>
                                        <td>{{ t.txn_id }}</td>
                                        <td>{{ t.type }}</td>
                                        <td class="text-end">৳ {{ t.amount }}</td>
                                        <td class="text-end">৳ {{ t.balance }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div> -->
                        <!-- <div v-else class="text-muted">No recent transactions available.</div> -->

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { defaultUser, type UserType } from '@/components/interfaces/users.interfaces';
import api from '@/config/config';
import { ref } from 'vue';
import { useRoute } from 'vue-router';


    const route =useRoute();

    const queryId = route.params.id;
    // console.log('User ID from route params:', queryId);
    const loading = ref(true);
    const error = ref<string | null>(null);
        const user = ref<UserType | null>(defaultUser);

            api.get('users/'+queryId)
            .then((res)=>{
                user.value =res.data;
                loading.value =false;
                console.log(user.value);
            })
            .catch((err)=>{
                error.value ='Failed to load user details.';
                loading.value =false;
                console.log(err);
            })



</script>
