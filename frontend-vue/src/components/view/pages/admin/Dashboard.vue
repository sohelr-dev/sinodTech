<template>
  <div class="container-fluid py-4">
    <div class="mb-4">
      <h4 class="fw-bold mb-0">Dashboard</h4>
      <small class="text-muted">Business overview for today and this month</small>
    </div>

    <div v-if="loading" class="text-center py-5">
      <div class="spinner-border text-primary"></div>
    </div>

    <div v-if="stats && !loading">
      <!-- KPI Cards Row 1 -->
      <div class="row g-3 mb-4">
        <div class="col-md-3">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
              <div class="dash-icon bg-success-subtle text-success"><i class="bi bi-cash-stack fs-3"></i></div>
              <div>
                <div class="text-muted small">Today's Revenue</div>
                <div class="fw-bold fs-4">৳{{ stats.sales.today_revenue.toFixed(0) }}</div>
                <div class="text-muted small">{{ stats.sales.today_count }} sale(s)</div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
              <div class="dash-icon bg-primary-subtle text-primary"><i class="bi bi-graph-up-arrow fs-3"></i></div>
              <div>
                <div class="text-muted small">Monthly Revenue</div>
                <div class="fw-bold fs-4">৳{{ stats.sales.month_revenue.toFixed(0) }}</div>
                <div class="text-muted small">This month</div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
              <div class="dash-icon bg-warning-subtle text-warning"><i class="bi bi-people-fill fs-3"></i></div>
              <div>
                <div class="text-muted small">Total Customers</div>
                <div class="fw-bold fs-4">{{ stats.customers.total }}</div>
                <div class="text-muted small">{{ stats.customers.active }} active</div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
              <div class="dash-icon bg-danger-subtle text-danger"><i class="bi bi-person-x-fill fs-3"></i></div>
              <div>
                <div class="text-muted small">Lost Customers</div>
                <div class="fw-bold fs-4 text-danger">{{ stats.customers.lost }}</div>
                <div class="text-muted small">90+ days inactive</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Row 2: Products + Top Highlights -->
      <div class="row g-3">
        <div class="col-md-4">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
              <div class="dash-icon bg-info-subtle text-info"><i class="bi bi-box-seam-fill fs-3"></i></div>
              <div>
                <div class="text-muted small">Products</div>
                <div class="fw-bold fs-4">{{ stats.products.total }}</div>
                <div class="text-danger small fw-semibold" v-if="stats.products.low_stock > 0">
                  <i class="bi bi-exclamation-triangle"></i> {{ stats.products.low_stock }} low stock!
                </div>
                <div class="text-muted small" v-else>All stocked up ✓</div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
              <div class="dash-icon bg-success-subtle text-success"><i class="bi bi-trophy-fill fs-3"></i></div>
              <div>
                <div class="text-muted small">Top Selling Product</div>
                <div class="fw-bold" v-if="stats.top_product">
                  {{ stats.top_product.name }}
                  <span class="badge bg-success ms-1">{{ stats.top_product.sold }} units</span>
                </div>
                <div class="text-muted small" v-else>No sales this month</div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
              <div class="dash-icon bg-warning-subtle text-warning"><i class="bi bi-star-fill fs-3"></i></div>
              <div>
                <div class="text-muted small">Top KPI Employee</div>
                <div class="fw-bold" v-if="stats.top_employee">
                  {{ stats.top_employee.name }}
                  <span class="badge bg-warning text-dark ms-1">{{ stats.top_employee.kpi_score }} pts</span>
                </div>
                <div class="text-muted small" v-else>No KPI data yet</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import api from '../../../../config/config';

const stats   = ref<any>(null);
const loading = ref(false);

onMounted(async () => {
  loading.value = true;
  try { const res = await api.get('dashboard/stats'); stats.value = res.data.data; }
  finally { loading.value = false; }
});
</script>

<style scoped>
.dash-icon {
  width: 56px; height: 56px;
  border-radius: 14px;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
</style>