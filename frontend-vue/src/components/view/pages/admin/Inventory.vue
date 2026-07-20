<template>
  <div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="fw-bold mb-0">Inventory Management</h4>
        <small class="text-muted">Adjust stock levels per product per branch</small>
      </div>
    </div>

    <!-- Low Stock Alert -->
    <div v-if="!loading && lowStockItems.length > 0" class="alert alert-warning d-flex align-items-center mb-3">
      <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
      <span><strong>{{ lowStockItems.length }} item(s)</strong> have critically low stock (≤ 5 units).</span>
    </div>

    <!-- Filter by Branch -->
    <div class="mb-3 d-flex gap-2 flex-wrap">
      <button class="btn btn-sm" :class="selectedBranch === null ? 'btn-primary' : 'btn-outline-secondary'"
        @click="selectedBranch = null">All Branches</button>
      <button v-for="branch in branches" :key="branch" class="btn btn-sm"
        :class="selectedBranch === branch ? 'btn-primary' : 'btn-outline-secondary'"
        @click="selectedBranch = branch">{{ branch }}</button>
    </div>

    <div class="card shadow-sm">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead class="table-light">
              <tr>
                <th>Product</th>
                <th>SKU</th>
                <th>Branch</th>
                <th class="text-center">Stock</th>
                <th class="text-center" style="width:200px">Adjust</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="loading">
                <td colspan="5" class="text-center py-4">
                  <div class="spinner-border text-primary spinner-border-sm"></div>
                  <span class="ms-2">Loading inventory...</span>
                </td>
              </tr>
              <tr v-for="item in filteredInventory" :key="item.id"
                :class="{ 'table-danger': item.stock_quantity <= 5 }">
                <td class="fw-semibold">{{ item.product_name }}</td>
                <td class="text-muted small font-monospace">{{ item.product_sku }}</td>
                <td><span class="badge bg-secondary">{{ item.branch_name }}</span></td>
                <td class="text-center">
                  <span class="fw-bold" :class="item.stock_quantity <= 5 ? 'text-danger' : 'text-success'">
                    {{ item.stock_quantity }}
                  </span>
                </td>
                <td class="text-center">
                  <div class="d-flex align-items-center justify-content-center gap-1">
                    <button class="btn btn-sm btn-outline-danger" @click="adjust(item, -1)"
                      :disabled="item.stock_quantity <= 0 || adjusting === item.id">−1</button>
                    <input type="number" class="form-control form-control-sm text-center" style="width:65px"
                      v-model.number="adjustments[item.id]" placeholder="±qty" />
                    <button class="btn btn-sm btn-outline-success" @click="adjust(item, 1)"
                      :disabled="adjusting === item.id">+1</button>
                    <button class="btn btn-sm btn-primary" @click="adjustCustom(item)"
                      :disabled="!adjustments[item.id] || adjusting === item.id">
                      <span v-if="adjusting === item.id" class="spinner-border spinner-border-sm"></span>
                      <i v-else class="bi bi-check-lg"></i>
                    </button>
                  </div>
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
import { ref, computed, onMounted } from 'vue';
import api from '../../../../config/config';

interface InventoryItem { id: number; product_name: string; product_sku: string; branch_name: string; stock_quantity: number; }

const inventory      = ref<InventoryItem[]>([]);
const adjustments    = ref<Record<number, number>>({});
const loading        = ref(false);
const adjusting      = ref<number | null>(null);
const selectedBranch = ref<string | null>(null);

const branches = computed(() => [...new Set(inventory.value.map(i => i.branch_name))]);
const filteredInventory = computed(() =>
  selectedBranch.value ? inventory.value.filter(i => i.branch_name === selectedBranch.value) : inventory.value
);
const lowStockItems = computed(() => inventory.value.filter(i => i.stock_quantity <= 5));

onMounted(load);

async function load() {
  loading.value = true;
  try { const res = await api.get('inventory'); inventory.value = res.data.data; }
  catch { inventory.value = []; }
  finally { loading.value = false; }
}

async function adjust(item: InventoryItem, qty: number) {
  adjusting.value = item.id;
  try {
    const res = await api.patch(`inventory/${item.id}`, { adjustment: qty });
    item.stock_quantity = res.data.data.stock_quantity;
  } catch (e: any) { alert(e.response?.data?.message || 'Failed'); }
  finally { adjusting.value = null; }
}

async function adjustCustom(item: InventoryItem) {
  const qty = adjustments.value[item.id];
  if (!qty) return;
  await adjust(item, qty);
  delete adjustments.value[item.id];
}
</script>
