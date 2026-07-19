<template>
  <div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="fw-bold mb-0">New Sale / Checkout</h4>
        <small class="text-muted">Select customer, branch, and add products</small>
      </div>
    </div>

    <!-- Alerts -->
    <div v-if="successMsg" class="alert alert-success alert-dismissible fade show">
      <i class="bi bi-check-circle me-2"></i>{{ successMsg }}
      <button type="button" class="btn-close" @click="successMsg = ''"></button>
    </div>
    <div v-if="errorMsg" class="alert alert-danger alert-dismissible fade show">
      <i class="bi bi-exclamation-triangle me-2"></i>{{ errorMsg }}
      <button type="button" class="btn-close" @click="errorMsg = ''"></button>
    </div>

    <div class="row g-4">
      <!-- LEFT — Checkout Form -->
      <div class="col-lg-7">
        <div class="card shadow-sm">
          <div class="card-header fw-bold bg-white">
            <i class="bi bi-cart3 me-2 text-primary"></i>Sale Details
          </div>
          <div class="card-body">
            <!-- Branch -->
            <div class="mb-3">
              <label class="form-label fw-semibold">Branch <span class="text-danger">*</span></label>
              <select class="form-select" v-model="form.branch_id" required>
                <option :value="null" disabled>Select branch...</option>
                <option v-for="b in branches" :key="b.id" :value="b.id">{{ b.name }}</option>
              </select>
            </div>

            <!-- Customer (optional) -->
            <div class="mb-3">
              <label class="form-label fw-semibold">Customer <span class="text-muted small">(optional)</span></label>
              <select class="form-select" v-model="form.customer_id">
                <option :value="null">Walk-in Customer</option>
                <option v-for="c in customers" :key="c.id" :value="c.id">{{ c.name }} — {{ c.email }}</option>
              </select>
            </div>

            <!-- Add Product row -->
            <hr />
            <label class="form-label fw-semibold">Add Products</label>
            <div class="d-flex gap-2 mb-3">
              <select class="form-select" v-model="selectedProductId">
                <option :value="null" disabled>Select product...</option>
                <option v-for="p in products" :key="p.id" :value="p.id">
                  {{ p.name }} ({{ p.sku }}) — ৳{{ p.price }}
                </option>
              </select>
              <input type="number" min="1" class="form-control" style="max-width:90px"
                v-model.number="selectedQty" placeholder="Qty" />
              <button class="btn btn-outline-primary" @click="addItem">
                <i class="bi bi-plus-lg"></i>
              </button>
            </div>

            <!-- Cart Items Table -->
            <div class="table-responsive" v-if="cartItems.length > 0">
              <table class="table table-sm table-bordered mb-0">
                <thead class="table-light">
                  <tr>
                    <th>Product</th>
                    <th class="text-center">Qty</th>
                    <th class="text-end">Unit Price</th>
                    <th class="text-end">Subtotal</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, idx) in cartItems" :key="idx">
                    <td class="fw-semibold">{{ item.name }}</td>
                    <td class="text-center">{{ item.quantity }}</td>
                    <td class="text-end">৳{{ item.price.toFixed(2) }}</td>
                    <td class="text-end text-success fw-bold">৳{{ (item.price * item.quantity).toFixed(2) }}</td>
                    <td class="text-center">
                      <button class="btn btn-sm btn-outline-danger" @click="removeItem(idx)">
                        <i class="bi bi-x-lg"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <p v-else class="text-muted text-center py-3">
              <i class="bi bi-cart-x fs-3 d-block mb-1"></i>No items added yet.
            </p>
          </div>
        </div>
      </div>

      <!-- RIGHT — Order Summary -->
      <div class="col-lg-5">
        <div class="card shadow-sm">
          <div class="card-header fw-bold bg-white">
            <i class="bi bi-receipt me-2 text-success"></i>Order Summary
          </div>
          <div class="card-body">
            <div class="d-flex justify-content-between mb-2">
              <span class="text-muted">Items</span>
              <span>{{ cartItems.length }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span class="text-muted">Total Qty</span>
              <span>{{ totalQty }}</span>
            </div>
            <hr />
            <div class="d-flex justify-content-between fw-bold fs-5">
              <span>Total Amount</span>
              <span class="text-success">৳{{ totalAmount.toFixed(2) }}</span>
            </div>
            <div class="mt-4 d-grid">
              <button class="btn btn-success btn-lg fw-bold"
                :disabled="submitting || cartItems.length === 0 || !form.branch_id"
                @click="submitSale">
                <span v-if="submitting" class="spinner-border spinner-border-sm me-2"></span>
                <i v-else class="bi bi-bag-check me-2"></i>
                Complete Sale
              </button>
            </div>
            <p class="text-muted small text-center mt-2">
              An invoice will be generated automatically.
            </p>
          </div>
        </div>

        <!-- Last Sale Receipt -->
        <div class="card shadow-sm mt-3 border-success" v-if="lastSale">
          <div class="card-header bg-success text-white fw-bold">
            <i class="bi bi-check-circle me-2"></i>Sale Completed!
          </div>
          <div class="card-body small">
            <p><strong>Invoice:</strong> {{ lastSale.invoice_number }}</p>
            <p><strong>Branch:</strong> {{ lastSale.branch?.name }}</p>
            <p><strong>Customer:</strong> {{ lastSale.customer?.name ?? 'Walk-in' }}</p>
            <p><strong>Total:</strong> <span class="text-success fw-bold">৳{{ lastSale.total_amount }}</span></p>
            <p><strong>Date:</strong> {{ lastSale.created_at }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import saleService from '../../../../services/saleService';
import productService, { type Product } from '../../../../services/productService';
import api from '../../../../config/config';

// ─── State ─────────────────────────────────────────────────────────────────
const products         = ref<Product[]>([]);
const customers        = ref<any[]>([]);
const branches         = ref<any[]>([]);
const cartItems        = ref<{ product_id: number; name: string; price: number; quantity: number }[]>([]);
const selectedProductId = ref<number | null>(null);
const selectedQty       = ref(1);
const submitting        = ref(false);
const successMsg        = ref('');
const errorMsg          = ref('');
const lastSale          = ref<any>(null);

const form = ref<{ branch_id: number | null; customer_id: number | null }>({
  branch_id: null,
  customer_id: null,
});

// ─── Computed ───────────────────────────────────────────────────────────────
const totalAmount = computed(() =>
  cartItems.value.reduce((sum, item) => sum + item.price * item.quantity, 0)
);
const totalQty = computed(() =>
  cartItems.value.reduce((sum, item) => sum + item.quantity, 0)
);

// ─── Lifecycle ──────────────────────────────────────────────────────────────
onMounted(async () => {
  const [prodRes, custRes, branchRes] = await Promise.all([
    productService.getAll(1, 100),
    api.get('customers').then(r => r.data),
    api.get('branches').then(r => r.data),
  ]);
  products.value  = prodRes.data;
  customers.value = custRes.data ?? [];
  branches.value  = branchRes.data ?? [];
});

// ─── Cart Actions ───────────────────────────────────────────────────────────
function addItem() {
  if (!selectedProductId.value || selectedQty.value < 1) return;
  const product = products.value.find(p => p.id === selectedProductId.value);
  if (!product) return;

  const existing = cartItems.value.find(i => i.product_id === selectedProductId.value);
  if (existing) {
    existing.quantity += selectedQty.value;
  } else {
    cartItems.value.push({
      product_id: product.id,
      name: product.name,
      price: product.price,
      quantity: selectedQty.value,
    });
  }
  selectedProductId.value = null;
  selectedQty.value = 1;
}

function removeItem(index: number) {
  cartItems.value.splice(index, 1);
}

// ─── Submit ─────────────────────────────────────────────────────────────────
async function submitSale() {
  if (cartItems.value.length === 0 || !form.value.branch_id) return;
  submitting.value = true;
  errorMsg.value = '';
  successMsg.value = '';

  try {
    const res = await saleService.create({
      branch_id:   form.value.branch_id!,
      customer_id: form.value.customer_id,
      items: cartItems.value.map(i => ({
        product_id: i.product_id,
        quantity:   i.quantity,
      })),
    });
    lastSale.value   = res.data;
    successMsg.value = `Sale completed! Invoice: ${res.data.invoice_number}`;
    cartItems.value  = [];
    form.value.customer_id = null;
  } catch (err: any) {
    errorMsg.value = err.response?.data?.message || 'Sale failed. Please try again.';
  } finally {
    submitting.value = false;
  }
}
</script>
