<template>
  <div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="fw-bold mb-0">Products</h4>
        <small class="text-muted">Manage your product catalog</small>
      </div>
      <button class="btn btn-primary" @click="openCreateModal">
        <i class="bi bi-plus-lg me-1"></i> Add Product
      </button>
    </div>

    <!-- Alerts -->
    <div v-if="successMsg" class="alert alert-success alert-dismissible fade show">
      {{ successMsg }}
      <button type="button" class="btn-close" @click="successMsg = ''"></button>
    </div>
    <div v-if="errorMsg" class="alert alert-danger alert-dismissible fade show">
      {{ errorMsg }}
      <button type="button" class="btn-close" @click="errorMsg = ''"></button>
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
                <th>SKU</th>
                <th>Price</th>
                <th>Description</th>
                <th class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="loading">
                <td colspan="6" class="text-center py-4">
                  <div class="spinner-border text-primary spinner-border-sm"></div>
                  <span class="ms-2">Loading products...</span>
                </td>
              </tr>
              <tr v-else-if="products.length === 0">
                <td colspan="6" class="text-center py-4 text-muted">No products found.</td>
              </tr>
              <tr v-for="(product, index) in products" :key="product.id">
                <td>{{ (meta.current_page - 1) * meta.per_page + index + 1 }}</td>
                <td class="fw-semibold">{{ product.name }}</td>
                <td><span class="badge bg-secondary">{{ product.sku }}</span></td>
                <td class="text-success fw-bold">৳ {{ product.price.toFixed(2) }}</td>
                <td class="text-muted small">{{ product.description || '—' }}</td>
                <td class="text-center">
                  <button class="btn btn-sm btn-outline-primary me-1" @click="openEditModal(product)" title="Edit">
                    <i class="bi bi-pencil"></i>
                  </button>
                  <button class="btn btn-sm btn-outline-danger" @click="confirmDelete(product)" title="Delete">
                    <i class="bi bi-trash"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Pagination -->
      <div class="card-footer d-flex justify-content-between align-items-center" v-if="meta.last_page > 1">
        <small class="text-muted">Page {{ meta.current_page }} of {{ meta.last_page }} ({{ meta.total }} total)</small>
        <div>
          <button class="btn btn-sm btn-outline-secondary me-1" :disabled="meta.current_page === 1" @click="changePage(meta.current_page - 1)">
            <i class="bi bi-chevron-left"></i> Prev
          </button>
          <button class="btn btn-sm btn-outline-secondary" :disabled="meta.current_page === meta.last_page" @click="changePage(meta.current_page + 1)">
            Next <i class="bi bi-chevron-right"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Create/Edit Modal -->
    <div class="modal fade" id="productModal" tabindex="-1" ref="productModalRef">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title fw-bold">{{ isEditing ? 'Edit Product' : 'Add New Product' }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="submitForm">
              <div class="mb-3">
                <label class="form-label fw-semibold">Product Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" v-model="form.name" placeholder="e.g. Laptop Pro 15" required />
                <small class="text-danger" v-if="formErrors.name">{{ formErrors.name[0] }}</small>
              </div>
              <div class="mb-3">
                <label class="form-label fw-semibold">SKU <span class="text-danger">*</span></label>
                <input type="text" class="form-control" v-model="form.sku" placeholder="e.g. LAPTOP-PRO-15" :disabled="isEditing" required />
                <small class="text-danger" v-if="formErrors.sku">{{ formErrors.sku[0] }}</small>
              </div>
              <div class="mb-3">
                <label class="form-label fw-semibold">Price (৳) <span class="text-danger">*</span></label>
                <input type="number" step="0.01" min="0" class="form-control" v-model="form.price" placeholder="0.00" required />
                <small class="text-danger" v-if="formErrors.price">{{ formErrors.price[0] }}</small>
              </div>
              <div class="mb-3">
                <label class="form-label fw-semibold">Description</label>
                <textarea class="form-control" v-model="form.description" rows="3" placeholder="Optional description..."></textarea>
              </div>
              <div class="d-flex gap-2 justify-content-end">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" :disabled="submitting">
                  <span v-if="submitting" class="spinner-border spinner-border-sm me-1"></span>
                  {{ isEditing ? 'Update Product' : 'Create Product' }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Delete Confirm Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" ref="deleteModalRef">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-body text-center p-4">
            <i class="bi bi-exclamation-triangle-fill text-danger fs-1"></i>
            <h5 class="mt-2">Delete Product?</h5>
            <p class="text-muted small">Are you sure you want to delete <strong>{{ selectedProduct?.name }}</strong>? This cannot be undone.</p>
            <div class="d-flex gap-2 justify-content-center">
              <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
              <button class="btn btn-danger" @click="deleteProduct" :disabled="submitting">
                <span v-if="submitting" class="spinner-border spinner-border-sm me-1"></span>
                Yes, Delete
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Modal } from 'bootstrap';
import productService, { type Product } from '../../../../services/productService';

// ─── State ───────────────────────────────────────────────────────────────────
const products      = ref<Product[]>([]);
const loading       = ref(false);
const submitting    = ref(false);
const successMsg    = ref('');
const errorMsg      = ref('');
const meta          = ref({ current_page: 1, last_page: 1, per_page: 15, total: 0 });
const isEditing     = ref(false);
const selectedProduct = ref<Product | null>(null);
const formErrors    = ref<Record<string, string[]>>({});

const productModalRef = ref<HTMLElement | null>(null);
const deleteModalRef  = ref<HTMLElement | null>(null);

let productModal: Modal | null = null;
let deleteModal: Modal | null = null;

const form = ref({ name: '', sku: '', price: 0, description: '' });

// ─── Lifecycle ───────────────────────────────────────────────────────────────
onMounted(() => {
  fetchProducts();
  productModal = new Modal(productModalRef.value!);
  deleteModal  = new Modal(deleteModalRef.value!);
});

// ─── Fetch ───────────────────────────────────────────────────────────────────
async function fetchProducts(page = 1) {
  loading.value = true;
  try {
    const res = await productService.getAll(page, meta.value.per_page);
    products.value = res.data;
    meta.value = res.meta;
  } catch {
    errorMsg.value = 'Failed to load products.';
  } finally {
    loading.value = false;
  }
}

function changePage(page: number) {
  fetchProducts(page);
}

// ─── Modal Controls ──────────────────────────────────────────────────────────
function openCreateModal() {
  isEditing.value = false;
  form.value = { name: '', sku: '', price: 0, description: '' };
  formErrors.value = {};
  productModal?.show();
}

function openEditModal(product: Product) {
  isEditing.value = true;
  selectedProduct.value = product;
  form.value = { name: product.name, sku: product.sku, price: product.price, description: product.description ?? '' };
  formErrors.value = {};
  productModal?.show();
}

function confirmDelete(product: Product) {
  selectedProduct.value = product;
  deleteModal?.show();
}

// ─── Submit ──────────────────────────────────────────────────────────────────
async function submitForm() {
  submitting.value = true;
  formErrors.value = {};
  try {
    if (isEditing.value && selectedProduct.value) {
      await productService.update(selectedProduct.value.id, form.value);
      successMsg.value = 'Product updated successfully!';
    } else {
      await productService.create(form.value);
      successMsg.value = 'Product created successfully!';
    }
    productModal?.hide();
    fetchProducts(meta.value.current_page);
  } catch (err: any) {
    if (err.response?.status === 422) {
      formErrors.value = err.response.data.errors;
    } else {
      errorMsg.value = err.response?.data?.message || 'Something went wrong.';
    }
  } finally {
    submitting.value = false;
  }
}

async function deleteProduct() {
  if (!selectedProduct.value) return;
  submitting.value = true;
  try {
    await productService.delete(selectedProduct.value.id);
    successMsg.value = 'Product deleted successfully!';
    deleteModal?.hide();
    fetchProducts(meta.value.current_page);
  } catch {
    errorMsg.value = 'Failed to delete product.';
  } finally {
    submitting.value = false;
  }
}
</script>
