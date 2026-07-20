<template>
  <div class="container-fluid py-4">
    <!-- Back button + Header -->
    <div class="mb-4">
      <router-link to="/crm" class="btn btn-sm btn-outline-secondary mb-3">
        <i class="bi bi-arrow-left me-1"></i> Back to Customers
      </router-link>

      <div v-if="customer" class="d-flex align-items-center gap-3">
        <div class="avatar-circle bg-primary text-white">
          {{ customer.name.charAt(0).toUpperCase() }}
        </div>
        <div>
          <h4 class="fw-bold mb-0">{{ customer.name }}</h4>
          <small class="text-muted">{{ customer.email || 'No email' }} · {{ customer.phone || 'No phone' }}</small>
        </div>
        <span class="badge ms-2 fs-6" :class="customer.is_lost ? 'bg-danger' : 'bg-success'">
          {{ customer.is_lost ? '⚠ Inactive (Lost)' : '✓ Active' }}
        </span>
      </div>
    </div>

    <!-- Loading state -->
    <div v-if="loading" class="text-center py-5">
      <div class="spinner-border text-primary"></div>
      <p class="mt-2 text-muted">Loading customer data...</p>
    </div>

    <div v-if="customer && !loading">
      <!-- Stats Row -->
      <div class="row g-3 mb-4">
        <div class="col-md-4">
          <div class="card shadow-sm border-0">
            <div class="card-body">
              <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-primary-subtle text-primary">
                  <i class="bi bi-cart-check-fill fs-4"></i>
                </div>
                <div>
                  <div class="fw-bold fs-4">{{ customer.purchase_frequency }}</div>
                  <div class="text-muted small">Total Purchases</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card shadow-sm border-0">
            <div class="card-body">
              <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-success-subtle text-success">
                  <i class="bi bi-cash-stack fs-4"></i>
                </div>
                <div>
                  <div class="fw-bold fs-4">৳{{ customer.total_spent.toFixed(2) }}</div>
                  <div class="text-muted small">Total Spent</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card shadow-sm border-0">
            <div class="card-body">
              <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-warning-subtle text-warning">
                  <i class="bi bi-clock-history fs-4"></i>
                </div>
                <div>
                  <div class="fw-bold fs-5">{{ customer.last_purchase_at ? formatDate(customer.last_purchase_at) : 'Never' }}</div>
                  <div class="text-muted small">Last Purchase</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Purchase History Timeline -->
      <div class="card shadow-sm">
        <div class="card-header bg-white fw-bold">
          <i class="bi bi-clock-history me-2 text-primary"></i>Purchase History
        </div>
        <div class="card-body">
          <div v-if="customer.sales && customer.sales.length > 0">
            <div v-for="sale in customer.sales" :key="sale.id" class="timeline-item mb-4">
              <!-- Invoice Header -->
              <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                  <span class="badge bg-primary me-2">{{ sale.invoice_number }}</span>
                  <span class="text-muted small">{{ formatDate(sale.created_at) }}</span>
                  <span class="ms-2 text-muted small">· {{ sale.branch?.name }}</span>
                </div>
                <span class="fw-bold text-success">৳{{ parseFloat(sale.total_amount).toFixed(2) }}</span>
              </div>

              <!-- Items Table -->
              <table class="table table-sm table-bordered mb-0">
                <thead class="table-light">
                  <tr>
                    <th>Product</th>
                    <th class="text-center">Qty</th>
                    <th class="text-end">Unit Price</th>
                    <th class="text-end">Subtotal</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="item in sale.items" :key="item.product_id">
                    <td>{{ item.product_name }}</td>
                    <td class="text-center">{{ item.quantity }}</td>
                    <td class="text-end">৳{{ parseFloat(item.unit_price).toFixed(2) }}</td>
                    <td class="text-end text-success fw-bold">৳{{ parseFloat(item.subtotal).toFixed(2) }}</td>
                  </tr>
                </tbody>
              </table>
              <hr />
            </div>
          </div>
          <div v-else class="text-center py-4 text-muted">
            <i class="bi bi-receipt fs-2 d-block mb-2"></i>
            No purchase history yet.
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import customerService, { type Customer } from '../../../../services/customerService';

const route    = useRoute();
const customer = ref<Customer | null>(null);
const loading  = ref(false);

onMounted(async () => {
  loading.value = true;
  try {
    const res = await customerService.getById(Number(route.params.id));
    customer.value = res.data;
  } catch {
    customer.value = null;
  } finally {
    loading.value = false;
  }
});

function formatDate(dateStr: string): string {
  return new Date(dateStr).toLocaleDateString('en-GB', {
    day: '2-digit', month: 'short', year: 'numeric'
  });
}
</script>

<style scoped>
.avatar-circle {
  width: 52px;
  height: 52px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.4rem;
  font-weight: 700;
}

.stat-icon {
  width: 52px;
  height: 52px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.timeline-item {
  border-left: 3px solid #0d6efd;
  padding-left: 1rem;
}
</style>
