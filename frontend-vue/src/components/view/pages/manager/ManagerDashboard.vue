<template>
  <div class="container-fluid py-4">
    <!-- Header -->
    <div class="mb-4">
      <h4 class="fw-bold mb-0">Manager Dashboard</h4>
      <small class="text-muted">Welcome back, {{ auth.user?.name }} — here's today's overview</small>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
      <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body d-flex align-items-center gap-3">
            <div class="stat-icon bg-primary-subtle text-primary rounded-3">
              <i class="bi bi-cart-check-fill fs-4"></i>
            </div>
            <div>
              <div class="text-muted small">Total Sales</div>
              <div class="fw-bold fs-4">{{ stats.total_sales ?? '—' }}</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body d-flex align-items-center gap-3">
            <div class="stat-icon bg-success-subtle text-success rounded-3">
              <i class="bi bi-currency-dollar fs-4"></i>
            </div>
            <div>
              <div class="text-muted small">Total Revenue</div>
              <div class="fw-bold fs-4">৳{{ stats.total_revenue?.toFixed(0) ?? '—' }}</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body d-flex align-items-center gap-3">
            <div class="stat-icon bg-info-subtle text-info rounded-3">
              <i class="bi bi-people-fill fs-4"></i>
            </div>
            <div>
              <div class="text-muted small">Total Customers</div>
              <div class="fw-bold fs-4">{{ stats.total_customers ?? '—' }}</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body d-flex align-items-center gap-3">
            <div class="stat-icon bg-warning-subtle text-warning rounded-3">
              <i class="bi bi-person-x-fill fs-4"></i>
            </div>
            <div>
              <div class="text-muted small">Lost Customers</div>
              <div class="fw-bold fs-4 text-danger">{{ stats.lost_customers ?? '—' }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Lost customers alert -->
    <div v-if="stats.lost_customers > 0" class="alert alert-warning d-flex align-items-center mb-4">
      <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
      <div>
        <strong>{{ stats.lost_customers }} customer(s)</strong> have been inactive for 90+ days.
        Go to <router-link to="/crm" class="alert-link">CRM</router-link> to assign them for follow-up.
      </div>
    </div>

    <div class="row g-3">
      <!-- Recent Sales -->
      <div class="col-lg-7">
        <div class="card shadow-sm h-100">
          <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
            <span><i class="bi bi-receipt me-2 text-primary"></i>Recent Sales</span>
            <span class="badge bg-primary-subtle text-primary">Last 10</span>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover mb-0 small">
                <thead class="table-light">
                  <tr>
                    <th>Invoice</th>
                    <th>Customer</th>
                    <th class="text-center">Status</th>
                    <th class="text-end">Amount</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="loadingSales"><td colspan="4" class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary"></div></td></tr>
                  <tr v-else-if="recentSales.length === 0"><td colspan="4" class="text-center text-muted py-3">No sales yet.</td></tr>
                  <tr v-for="sale in recentSales" :key="sale.id">
                    <td class="text-muted">{{ sale.invoice_number }}</td>
                    <td class="fw-semibold">{{ sale.customer_name }}</td>
                    <td class="text-center">
                      <span class="badge" :class="sale.status === 'completed' ? 'bg-success' : 'bg-warning text-dark'">
                        {{ sale.status }}
                      </span>
                    </td>
                    <td class="text-end text-success fw-bold">৳{{ Number(sale.total_amount).toFixed(2) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- KPI Leaderboard (mini) -->
      <div class="col-lg-5">
        <div class="card shadow-sm h-100">
          <div class="card-header bg-white fw-bold">
            <i class="bi bi-bar-chart-fill me-2 text-warning"></i>KPI Leaderboard
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover mb-0 small">
                <thead class="table-light">
                  <tr>
                    <th class="text-center">Rank</th>
                    <th>Name</th>
                    <th class="text-center">KPI</th>
                    <th class="text-end">Revenue</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="loadingEmp"><td colspan="4" class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary"></div></td></tr>
                  <tr v-else-if="employees.length === 0"><td colspan="4" class="text-center text-muted py-3">No data.</td></tr>
                  <tr v-for="(emp, i) in employees" :key="emp.id" :class="{ 'table-warning': i === 0 }">
                    <td class="text-center">
                      <span v-if="i===0">🥇</span>
                      <span v-else-if="i===1">🥈</span>
                      <span v-else-if="i===2">🥉</span>
                      <span v-else class="text-muted">#{{ i+1 }}</span>
                    </td>
                    <td class="fw-semibold">{{ emp.name }}</td>
                    <td class="text-center"><span class="badge bg-primary">{{ emp.kpi_score }}</span></td>
                    <td class="text-end text-success">৳{{ emp.total_revenue.toFixed(0) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useAuthStore } from '../../../../store/auth';
import api from '../../../../config/config';
import employeeService from '../../../../services/employeeService';

const auth = useAuthStore();

const stats        = ref<any>({});
const recentSales  = ref<any[]>([]);
const employees    = ref<any[]>([]);
const loadingSales = ref(false);
const loadingEmp   = ref(false);

onMounted(async () => {
  document.title = 'Dashboard | Manager';
  loadStats();
  loadSales();
  loadEmployees();
});

async function loadStats() {
  try {
    const res = await api.get('dashboard/stats');
    stats.value = res.data.data ?? res.data;
  } catch { stats.value = {}; }
}

async function loadSales() {
  loadingSales.value = true;
  try {
    const res = await api.get('sales');
    recentSales.value = (res.data.data ?? []).slice(0, 10);
  } catch { recentSales.value = []; }
  finally { loadingSales.value = false; }
}

async function loadEmployees() {
  loadingEmp.value = true;
  try {
    const res = await employeeService.getAll();
    employees.value = res.data;
  } catch { employees.value = []; }
  finally { loadingEmp.value = false; }
}
</script>

<style scoped>
.stat-icon {
  width: 52px; height: 52px;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
</style>