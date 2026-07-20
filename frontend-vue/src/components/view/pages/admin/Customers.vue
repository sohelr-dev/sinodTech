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

    <!-- Alerts -->
    <div v-if="successMsg" class="alert alert-success alert-dismissible fade show">
      <i class="bi bi-check-circle me-2"></i>{{ successMsg }}
      <button type="button" class="btn-close" @click="successMsg = ''"></button>
    </div>

    <!-- Lost Customers Alert -->
    <div v-if="showLost && !loading && customers.length > 0" class="alert alert-warning d-flex align-items-center">
      <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
      <div>
        <strong>{{ customers.length }} inactive customer(s)</strong> have not made a purchase in over
        <strong>90 days</strong>. Assign them to an employee to follow up!
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
                <th>Assigned To</th>
                <th class="text-center">Purchases</th>
                <th class="text-end">Total Spent</th>
                <th>Last Purchase</th>
                <th class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="loading">
                <td colspan="10" class="text-center py-4">
                  <div class="spinner-border text-primary spinner-border-sm"></div>
                  <span class="ms-2">Loading customers...</span>
                </td>
              </tr>
              <tr v-else-if="customers.length === 0">
                <td colspan="10" class="text-center py-4 text-muted">
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
                <td>
                  <span v-if="customer.assigned_employee" class="badge bg-info text-dark">
                    👤 {{ customer.assigned_employee }}
                  </span>
                  <span v-else class="text-muted small">—</span>
                </td>
                <td class="text-center fw-bold">{{ customer.purchase_frequency }}</td>
                <td class="text-end text-success fw-bold">৳{{ customer.total_spent.toFixed(2) }}</td>
                <td class="small text-muted">
                  {{ customer.last_purchase_at ? formatDate(customer.last_purchase_at) : 'Never' }}
                </td>
                <td class="text-center">
                  <div class="d-flex justify-content-center gap-1">
                    <router-link :to="`/crm/${customer.id}`" class="btn btn-sm btn-outline-primary" title="View History">
                      <i class="bi bi-eye"></i>
                    </router-link>
                    <button v-if="customer.is_lost" class="btn btn-sm btn-outline-warning" 
                      @click="openAssignModal(customer)" title="Assign to Employee">
                      <i class="bi bi-person-plus"></i>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Assign Modal -->
    <div class="modal fade" id="assignModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title fw-bold">Assign Customer</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" v-if="activeCustomer">
            <p>Assign <strong>{{ activeCustomer.name }}</strong> to an employee for follow up:</p>
            <div class="mb-3">
              <label class="form-label fw-semibold">Select Employee</label>
              <select class="form-select" v-model="selectedEmployeeId">
                <option :value="null" disabled>Choose employee...</option>
                <option v-for="emp in employees" :key="emp.id" :value="emp.id">
                  {{ emp.name }} ({{ emp.role }})
                </option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" :disabled="!selectedEmployeeId || saving" @click="saveAssignment">
              <span v-if="saving" class="spinner-border spinner-border-sm me-2"></span>
              Assign Employee
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Modal } from 'bootstrap';
import customerService from '../../../../services/customerService';
import employeeService from '../../../../services/employeeService';
import api from '../../../../config/config';

// Decoupled modal state helper
let modalInstance: any = null;

interface CRM_Customer {
  id: number;
  name: string;
  email: string | null;
  phone: string | null;
  last_purchase_at: string | null;
  is_lost: boolean;
  assigned_employee: string | null;
  purchase_frequency: number;
  total_spent: number;
}

const customers          = ref<CRM_Customer[]>([]);
const employees          = ref<any[]>([]);
const loading            = ref(false);
const showLost           = ref(false);
const activeCustomer     = ref<CRM_Customer | null>(null);
const selectedEmployeeId = ref<number | null>(null);
const saving             = ref(false);
const successMsg         = ref('');

onMounted(() => {
  loadCustomers();
  loadEmployees();
});

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

async function loadEmployees() {
  try {
    const res = await employeeService.getAll();
    employees.value = res.data;
  } catch {
    employees.value = [];
  }
}

function openAssignModal(customer: CRM_Customer) {
  activeCustomer.value = customer;
  selectedEmployeeId.value = null;
  const modalEl = document.getElementById('assignModal');
  if (modalEl) {
    modalInstance = Modal.getOrCreateInstance(modalEl);
    modalInstance.show();
  }
}

async function saveAssignment() {
  if (!activeCustomer.value || !selectedEmployeeId.value) return;
  saving.value = true;
  try {
    await api.post('assignments', {
      customer_id: activeCustomer.value.id,
      employee_id: selectedEmployeeId.value
    });
    
    successMsg.value = `Successfully assigned ${activeCustomer.value.name} to the selected employee.`;
    if (modalInstance) {
      modalInstance.hide();
    }
    await loadCustomers();
  } catch (e: any) {
    alert(e.response?.data?.message || 'Failed to assign customer.');
  } finally {
    saving.value = false;
  }
}

function formatDate(dateStr: string): string {
  return new Date(dateStr).toLocaleDateString('en-GB', {
    day: '2-digit', month: 'short', year: 'numeric'
  });
}
</script>
