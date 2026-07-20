<template>
  <div class="container-fluid py-4">
    <div class="mb-4">
      <h4 class="fw-bold mb-0">Promotions</h4>
      <small class="text-muted">Send targeted offers to inactive customers</small>
    </div>

    <!-- Send Panel -->
    <div class="card shadow-sm mb-4 border-primary">
      <div class="card-header bg-primary text-white fw-bold">
        <i class="bi bi-megaphone-fill me-2"></i>Send Promotions to Lost Customers
      </div>
      <div class="card-body">
        <p class="text-muted mb-3">
          This will send a promotion to all customers who have been inactive for <strong>90+ days</strong>.
          Customers already contacted today will be skipped automatically.
        </p>
        <div class="d-flex align-items-center gap-3">
          <select class="form-select w-auto" v-model="channel">
            <option value="email">📧 Email</option>
            <option value="sms">📱 SMS</option>
            <option value="push">🔔 Push Notification</option>
          </select>
          <button class="btn btn-primary px-4" @click="sendPromotion" :disabled="sending">
            <span v-if="sending" class="spinner-border spinner-border-sm me-2"></span>
            <i v-else class="bi bi-send-fill me-2"></i>
            {{ sending ? 'Sending...' : 'Send Promotions' }}
          </button>
        </div>
        <div v-if="sendResult" class="alert mt-3 mb-0" :class="sendResult.sent > 0 ? 'alert-success' : 'alert-info'">
          <i class="bi bi-check-circle me-2"></i>{{ sendResult.message }}
        </div>
      </div>
    </div>

    <!-- Promotion Log -->
    <div class="card shadow-sm">
      <div class="card-header bg-white fw-bold">
        <i class="bi bi-clock-history me-2 text-muted"></i>Promotion History
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead class="table-light">
              <tr>
                <th>Customer</th>
                <th>Channel</th>
                <th class="text-center">Status</th>
                <th>Sent At</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="loadingLogs">
                <td colspan="4" class="text-center py-4">
                  <div class="spinner-border spinner-border-sm text-primary"></div>
                  <span class="ms-2">Loading logs...</span>
                </td>
              </tr>
              <tr v-else-if="logs.length === 0">
                <td colspan="4" class="text-center py-4 text-muted">No promotions sent yet.</td>
              </tr>
              <tr v-for="log in logs" :key="log.id">
                <td class="fw-semibold">{{ log.customer_name }}</td>
                <td><span class="badge bg-info text-dark text-capitalize">{{ log.channel }}</span></td>
                <td class="text-center"><span class="badge bg-success">{{ log.status }}</span></td>
                <td class="text-muted small">{{ log.sent_at }}</td>
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
import api from '../../../../config/config';

const channel    = ref('email');
const sending    = ref(false);
const sendResult = ref<any>(null);
const logs       = ref<any[]>([]);
const loadingLogs = ref(false);

onMounted(loadLogs);

async function loadLogs() {
  loadingLogs.value = true;
  try { const res = await api.get('promotions'); logs.value = res.data.data; }
  finally { loadingLogs.value = false; }
}

async function sendPromotion() {
  sending.value = true; sendResult.value = null;
  try {
    const res = await api.post('promotions/send-to-lost', { channel: channel.value });
    sendResult.value = res.data;
    await loadLogs();
  } catch (e: any) {
    sendResult.value = { message: e.response?.data?.message || 'Failed to send.', sent: 0 };
  } finally { sending.value = false; }
}
</script>
