<template>
  <div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="fw-bold mb-0">CRM — Customers</h4>
        <small class="text-muted">Manage and track all customers</small>
      </div>
      <!-- Filter Toggle -->
      <div class="btn-group">
        <button class="btn" :class="showLost ? 'btn-outline-secondary' : 'btn-primary'" @click="showLost = false; loadCustomers()">
          <i class="bi bi-people me-1"></i>All Customers
        </button>
        <button class="btn" :class="showLost ? 'btn-danger' : 'btn-outline-danger'" @click="showLost = true; loadCustomers()">
          <i class="bi bi-person-x me-1"></i>Lost Customers
        </button>
      </div>
    </div>

    <!-- Lost Customers Alert -->
    <div v-if="showLost && !loading && customers.length > 0" class="alert alert-warning d-flex align-items-center">
      <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
      <div>
        <strong>{{ customers.length }} inactive customer(s)</strong> have not made a purchase in over
        <strong>90 days</strong>. Consider sending them a promotion!
      </div>
    </div>

    <!-- Table Card -->
    <div class="card shadow-sm">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th class="text-center">Status</th>
                <th class="text-center">Purchases</th>
                <th class="text-end">Total Spent</th>
                <th>Last Purchase</th>
                <th class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="loading">
                <td colspan="9" class="text-center py-4">
                  <div class="spinner-border text-primary spinner-border-sm"></div>
                  <span class="ms-2">Loading customers...</span>
                </td>
              </tr>
              <tr v-else-if="customers.length === 0">
                <td colspan="9" class="text-center py-4 text-muted">
                  <i class="bi bi-people fs-3 d-block mb-1"></i>
                  No customers found.
                </td>
              </tr>
              <tr v-for="(customer, index) in customers" :key="customer.id">
                <td>{{ index + 1 }}</td>
                <td class="fw-semibold">{{ customer.name }}</td>
                <td class="text-muted small">{{ customer.email || '—' }}</td>
                <td class="text-muted small">{{ customer.phone || '—' }}</td>
                <td class="text-center">
                  <span class="badge" :class="customer.is_lost ? 'bg-danger' : 'bg-success'">
                    {{ customer.is_lost ? 'Inactive' : 'Active' }}
                  </span>
                </td>
                <td class="text-center fw-bold">{{ customer.purchase_frequency }}</td>
                <td class="text-end text-success fw-bold">৳{{ customer.total_spent.toFixed(2) }}</td>
                <td class="small text-muted">
                  {{ customer.last_purchase_at ? formatDate(customer.last_purchase_at) : 'Never' }}
                </td>
                <td class="text-center">
                  <router-link :to="`/crm/${customer.id}`" class="btn btn-sm btn-outline-primary" title="View History">
                    <i class="bi bi-eye"></i>
                  </router-link>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import customerService, { type Customer } from '../../../../services/customerService';

const customers = ref<Customer[]>([]);
const loading   = ref(false);
const showLost  = ref(false);

onMounted(() => loadCustomers());

async function loadCustomers() {
  loading.value = true;
  try {
    const res = await customerService.getAll(showLost.value);
    customers.value = res.data;
  } catch {
    customers.value = [];
  } finally {
    loading.value = false;
  }
}

function formatDate(dateStr: string): string {
  return new Date(dateStr).toLocaleDateString('en-GB', {
    day: '2-digit', month: 'short', year: 'numeric'
  });
}
</script>
