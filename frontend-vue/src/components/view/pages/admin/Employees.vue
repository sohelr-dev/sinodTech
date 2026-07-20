<template>
  <div class="container-fluid py-4">
    <!-- Header -->
    <div class="mb-4">
      <h4 class="fw-bold mb-0">Employee KPI Leaderboard</h4>
      <small class="text-muted">Employees ranked by KPI score — earned by re-engaging lost customers</small>
    </div>

    <!-- Top 3 Podium (if enough data) -->
    <div class="row justify-content-center g-3 mb-4" v-if="!loading && employees.length >= 1">
      <!-- 2nd Place -->
      <div class="col-md-3" v-if="employees[1]">
        <div class="card text-center border-0 shadow-sm podium-card silver">
          <div class="card-body py-4">
            <div class="podium-medal silver-medal mb-2">🥈</div>
            <div class="avatar-circle bg-secondary text-white mx-auto mb-2">
              {{ employees[1].name.charAt(0) }}
            </div>
            <h6 class="fw-bold mb-0">{{ employees[1].name }}</h6>
            <small class="text-muted text-capitalize">{{ employees[1].role }}</small>
            <div class="mt-2 fw-bold fs-5 text-secondary">{{ employees[1].kpi_score }} pts</div>
          </div>
        </div>
      </div>

      <!-- 1st Place -->
      <div class="col-md-3" v-if="employees[0]">
        <div class="card text-center border-0 shadow podium-card gold" style="transform: translateY(-12px)">
          <div class="card-body py-4">
            <div class="podium-medal gold-medal mb-2">🏆</div>
            <div class="avatar-circle bg-warning text-white mx-auto mb-2">
              {{ employees[0].name.charAt(0) }}
            </div>
            <h6 class="fw-bold mb-0">{{ employees[0].name }}</h6>
            <small class="text-muted text-capitalize">{{ employees[0].role }}</small>
            <div class="mt-2 fw-bold fs-4 text-warning">{{ employees[0].kpi_score }} pts</div>
          </div>
        </div>
      </div>

      <!-- 3rd Place -->
      <div class="col-md-3" v-if="employees[2]">
        <div class="card text-center border-0 shadow-sm podium-card bronze">
          <div class="card-body py-4">
            <div class="podium-medal bronze-medal mb-2">🥉</div>
            <div class="avatar-circle bg-danger text-white mx-auto mb-2">
              {{ employees[2].name.charAt(0) }}
            </div>
            <h6 class="fw-bold mb-0">{{ employees[2].name }}</h6>
            <small class="text-muted text-capitalize">{{ employees[2].role }}</small>
            <div class="mt-2 fw-bold fs-5 text-danger">{{ employees[2].kpi_score }} pts</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Full Leaderboard Table -->
    <div class="card shadow-sm">
      <div class="card-header bg-white fw-bold">
        <i class="bi bi-bar-chart-fill me-2 text-primary"></i>Full Rankings
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead class="table-light">
              <tr>
                <th class="text-center">Rank</th>
                <th>Name</th>
                <th>Role</th>
                <th class="text-center">KPI Score</th>
                <th class="text-center">Total Sales</th>
                <th class="text-end">Revenue Generated</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="loading">
                <td colspan="6" class="text-center py-4">
                  <div class="spinner-border text-primary spinner-border-sm"></div>
                  <span class="ms-2">Loading employees...</span>
                </td>
              </tr>
              <tr v-else-if="employees.length === 0">
                <td colspan="6" class="text-center py-4 text-muted">No employees found.</td>
              </tr>
              <tr v-for="(emp, index) in employees" :key="emp.id"
                  :class="{ 'table-warning': index === 0, 'bg-light': index > 2 }">
                <td class="text-center fw-bold">
                  <span v-if="index === 0">🥇</span>
                  <span v-else-if="index === 1">🥈</span>
                  <span v-else-if="index === 2">🥉</span>
                  <span v-else class="text-muted">#{{ index + 1 }}</span>
                </td>
                <td>
                  <div class="d-flex align-items-center gap-2">
                    <div class="avatar-sm bg-primary text-white">{{ emp.name.charAt(0) }}</div>
                    <span class="fw-semibold">{{ emp.name }}</span>
                  </div>
                </td>
                <td><span class="badge bg-secondary text-capitalize">{{ emp.role }}</span></td>
                <td class="text-center">
                  <span class="badge bg-primary fs-6 px-3">{{ emp.kpi_score }}</span>
                </td>
                <td class="text-center">{{ emp.total_sales }}</td>
                <td class="text-end text-success fw-bold">৳{{ emp.total_revenue.toFixed(2) }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- KPI Info Banner -->
    <div class="alert alert-info d-flex align-items-center mt-3">
      <i class="bi bi-info-circle-fill me-2 fs-5"></i>
      <div>
        <strong>How KPI Score Works:</strong> An employee earns <strong>+1 point</strong> every time
        they complete a sale for a customer who has been <strong>inactive for 90+ days</strong>.
        This incentivizes the sales team to proactively re-engage lost customers.
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import employeeService, { type Employee } from '../../../../services/employeeService';

const employees = ref<Employee[]>([]);
const loading   = ref(false);

onMounted(async () => {
  loading.value = true;
  try {
    const res = await employeeService.getAll();
    employees.value = res.data;
  } catch {
    employees.value = [];
  } finally {
    loading.value = false;
  }
});
</script>

<style scoped>
.avatar-circle {
  width: 48px; height: 48px;
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.3rem; font-weight: 700;
}
.avatar-sm {
  width: 32px; height: 32px;
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: 0.9rem; font-weight: 700;
}
.podium-card { border-radius: 16px; }
.podium-medal { font-size: 2rem; }
.gold   { border-top: 4px solid #f5a623; }
.silver { border-top: 4px solid #a0a0a0; }
.bronze { border-top: 4px solid #c97c3b; }
</style>
