<template>
  <div class="container-fluid py-4">
    <!-- Header -->
    <div class="mb-4">
      <h4 class="fw-bold mb-0">Editor Dashboard</h4>
      <small class="text-muted">Welcome back, {{ auth.user?.name }} — manage products and inventory</small>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
      <div class="col-sm-4">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body d-flex align-items-center gap-3">
            <div class="stat-icon bg-primary-subtle text-primary rounded-3">
              <i class="bi bi-box-seam-fill fs-4"></i>
            </div>
            <div>
              <div class="text-muted small">Total Products</div>
              <div class="fw-bold fs-4">{{ totalProducts }}</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body d-flex align-items-center gap-3">
            <div class="stat-icon bg-danger-subtle text-danger rounded-3">
              <i class="bi bi-exclamation-triangle-fill fs-4"></i>
            </div>
            <div>
              <div class="text-muted small">Low Stock Items</div>
              <div class="fw-bold fs-4 text-danger">{{ lowStockItems.length }}</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body d-flex align-items-center gap-3">
            <div class="stat-icon bg-success-subtle text-success rounded-3">
              <i class="bi bi-check-circle-fill fs-4"></i>
            </div>
            <div>
              <div class="text-muted small">Healthy Stock</div>
              <div class="fw-bold fs-4 text-success">{{ healthyCount }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Low stock alert -->
    <div v-if="lowStockItems.length > 0" class="alert alert-danger d-flex align-items-center mb-4">
      <i class="bi bi-exclamation-octagon-fill me-2 fs-5"></i>
      <div>
        <strong>{{ lowStockItems.length }} inventory item(s)</strong> are running low (≤ 5 units).
        Go to <router-link to="/inventory" class="alert-link">Inventory</router-link> to restock.
      </div>
    </div>

    <div class="row g-3">
      <!-- Low Stock Table -->
      <div class="col-lg-7">
        <div class="card shadow-sm h-100">
          <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
            <span><i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i>Low Stock Alert</span>
            <span class="badge bg-danger">≤ 5 units</span>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover mb-0 small">
                <thead class="table-light">
                  <tr>
                    <th>Product</th>
                    <th>SKU</th>
                    <th>Branch</th>
                    <th class="text-center">Stock</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="loadingLow"><td colspan="4" class="text-center py-3"><div class="spinner-border spinner-border-sm text-danger"></div></td></tr>
                  <tr v-else-if="lowStockItems.length === 0">
                    <td colspan="4" class="text-center text-muted py-3">
                      <i class="bi bi-check-circle text-success fs-4 d-block mb-1"></i>
                      All products are well stocked!
                    </td>
                  </tr>
                  <tr v-for="item in lowStockItems" :key="item.id">
                    <td class="fw-semibold">{{ item.product_name }}</td>
                    <td class="text-muted">{{ item.product_sku }}</td>
                    <td>{{ item.branch_name }}</td>
                    <td class="text-center">
                      <span class="badge" :class="item.stock_quantity === 0 ? 'bg-danger' : 'bg-warning text-dark'">
                        {{ item.stock_quantity }}
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Links -->
      <div class="col-lg-5">
        <div class="card shadow-sm h-100">
          <div class="card-header bg-white fw-bold">
            <i class="bi bi-lightning-fill me-2 text-warning"></i>Quick Actions
          </div>
          <div class="card-body d-flex flex-column gap-3 justify-content-center">
            <router-link to="/products" class="btn btn-outline-primary d-flex align-items-center gap-2 py-3">
              <i class="bi bi-box-seam fs-5"></i>
              <div class="text-start">
                <div class="fw-semibold">Manage Products</div>
                <small class="text-muted">Add, edit or remove products</small>
              </div>
              <i class="bi bi-chevron-right ms-auto"></i>
            </router-link>
            <router-link to="/inventory" class="btn btn-outline-warning d-flex align-items-center gap-2 py-3">
              <i class="bi bi-boxes fs-5"></i>
              <div class="text-start">
                <div class="fw-semibold">Adjust Inventory</div>
                <small class="text-muted">Update stock levels per branch</small>
              </div>
              <i class="bi bi-chevron-right ms-auto"></i>
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useAuthStore } from '../../../../store/auth';
import api from '../../../../config/config';

const auth = useAuthStore();
const totalProducts  = ref(0);
const lowStockItems  = ref<any[]>([]);
const totalInventory = ref(0);
const loadingLow     = ref(false);

const healthyCount = computed(() => totalInventory.value - lowStockItems.value.length);

onMounted(async () => {
  document.title = 'Dashboard | Editor';
  loadProducts();
  loadLowStock();
});

async function loadProducts() {
  try {
    const res = await api.get('products');
    totalProducts.value = (res.data.data ?? []).length;
  } catch {}
}

async function loadLowStock() {
  loadingLow.value = true;
  try {
    const inv = await api.get('inventory');
    totalInventory.value = (inv.data.data ?? []).length;
    const low = await api.get('inventory/low-stock');
    lowStockItems.value = low.data.data ?? [];
  } catch { lowStockItems.value = []; }
  finally { loadingLow.value = false; }
}
</script>

<style scoped>
.stat-icon {
  width: 52px; height: 52px;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
</style>