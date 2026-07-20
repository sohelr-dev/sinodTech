<template>
  <div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-2">
      <div>
        <h4 class="fw-bold mb-0">Sales Dashboard</h4>
        <small class="text-muted">Welcome back, {{ auth.user?.name }}</small>
      </div>
      <router-link to="/sales" class="btn btn-primary d-flex align-items-center gap-2">
        <i class="bi bi-cart-plus-fill"></i>
        <span>New Sale</span>
      </router-link>
    </div>

    <!-- KPI Card -->
    <div class="row g-3 mb-4">
      <div class="col-sm-4">
        <div class="card border-0 shadow-sm text-center py-3 h-100">
          <div class="card-body">
            <div class="display-6 fw-bold text-primary">{{ myKpi }}</div>
            <div class="text-muted small mt-1">My KPI Score</div>
            <div class="mt-2">
              <span class="badge bg-primary-subtle text-primary px-3">+1 per lost customer recovered</span>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="card border-0 shadow-sm text-center py-3 h-100">
          <div class="card-body">
            <div class="display-6 fw-bold text-success">{{ mySales }}</div>
            <div class="text-muted small mt-1">My Total Sales</div>
          </div>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="card border-0 shadow-sm text-center py-3 h-100">
          <div class="card-body">
            <div class="display-6 fw-bold text-warning">{{ assignedCount }}</div>
            <div class="text-muted small mt-1">Assigned Customers</div>
          </div>
        </div>
      </div>
    </div>

    <div class="row g-3">
      <!-- Assigned Customers -->
      <div class="col-lg-6">
        <div class="card shadow-sm h-100">
          <div class="card-header bg-white fw-bold">
            <i class="bi bi-person-lines-fill me-2 text-warning"></i>My Assigned Customers
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover mb-0 small">
                <thead class="table-light">
                  <tr>
                    <th>Customer</th>
                    <th class="text-center">Status</th>
                    <th>Assigned</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="loadingAssign"><td colspan="3" class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary"></div></td></tr>
                  <tr v-else-if="myAssignments.length === 0">
                    <td colspan="3" class="text-center text-muted py-3">
                      <i class="bi bi-check-circle text-success fs-4 d-block mb-1"></i>
                      No assigned customers right now.
                    </td>
                  </tr>
                  <tr v-for="a in myAssignments" :key="a.id">
                    <td class="fw-semibold">{{ a.customer_name }}</td>
                    <td class="text-center">
                      <span class="badge" :class="a.status === 'completed' ? 'bg-success' : 'bg-warning text-dark'">
                        {{ a.status }}
                      </span>
                    </td>
                    <td class="text-muted">{{ formatDate(a.assigned_at) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Sales -->
      <div class="col-lg-6">
        <div class="card shadow-sm h-100">
          <div class="card-header bg-white fw-bold">
            <i class="bi bi-receipt me-2 text-primary"></i>My Recent Sales
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover mb-0 small">
                <thead class="table-light">
                  <tr>
                    <th>Invoice</th>
                    <th>Customer</th>
                    <th class="text-end">Amount</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="loadingSales"><td colspan="3" class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary"></div></td></tr>
                  <tr v-else-if="recentSales.length === 0"><td colspan="3" class="text-center text-muted py-3">No sales yet.</td></tr>
                  <tr v-for="sale in recentSales" :key="sale.id">
                    <td class="text-muted">{{ sale.invoice_number }}</td>
                    <td class="fw-semibold">{{ sale.customer_name }}</td>
                    <td class="text-end text-success fw-bold">৳{{ Number(sale.total_amount).toFixed(2) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- KPI Info -->
    <div class="alert alert-info d-flex align-items-center mt-4">
      <i class="bi bi-info-circle-fill me-2 fs-5"></i>
      <div>
        <strong>Earn KPI Points:</strong> Complete a sale for a customer who has been
        <strong>inactive for 90+ days</strong> to earn +1 KPI point!
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import { useAuthStore } from '../../../../store/auth';
import api from '../../../../config/config';
import employeeService from '../../../../services/employeeService';

const auth = useAuthStore();
const myKpi         = ref(0);
const mySales       = ref(0);
const myAssignments = ref<any[]>([]);
const recentSales   = ref<any[]>([]);
const loadingAssign = ref(false);
const loadingSales  = ref(false);

const assignedCount = computed(() =>
  myAssignments.value.filter(a => a.status !== 'completed').length
);

onMounted(async () => {
  document.title = 'Dashboard | Sales';
  loadMyStats();
  loadAssignments();
  loadSales();
});

async function loadMyStats() {
  try {
    const res = await employeeService.getAll();
    const me = res.data.find((e: any) => e.email === auth.user?.email);
    if (me) {
      myKpi.value   = me.kpi_score;
      mySales.value = me.total_sales;
    }
  } catch {}
}

async function loadAssignments() {
  loadingAssign.value = true;
  try {
    const res = await api.get('assignments');
    // filter to this employee's assignments
    myAssignments.value = (res.data.data ?? []).filter(
      (a: any) => a.employee_id === auth.user?.id
    );
  } catch { myAssignments.value = []; }
  finally { loadingAssign.value = false; }
}

async function loadSales() {
  loadingSales.value = true;
  try {
    const res = await api.get('sales');
    // filter to this employee's sales
    recentSales.value = (res.data.data ?? [])
      .filter((s: any) => s.employee_id === auth.user?.id)
      .slice(0, 5);
  } catch { recentSales.value = []; }
  finally { loadingSales.value = false; }
}

function formatDate(dateStr: string): string {
  return new Date(dateStr).toLocaleDateString('en-GB', {
    day: '2-digit', month: 'short', year: 'numeric'
  });
}
</script>

<style scoped>
.display-6 { font-size: 2rem; }
</style>