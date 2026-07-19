import api from '../config/config';

export interface Product {
  id: number;
  name: string;
  sku: string;
  price: number;
  description: string | null;
  created_at: string;
  updated_at: string;
}

export interface PaginatedProducts {
  data: Product[];
  meta: {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
  };
}

export interface ProductPayload {
  name: string;
  sku: string;
  price: number;
  description?: string;
}

const productService = {
  getAll(page = 1, perPage = 15): Promise<PaginatedProducts> {
    return api.get(`products?page=${page}&per_page=${perPage}`).then(r => r.data);
  },

  getById(id: number): Promise<{ data: Product }> {
    return api.get(`products/${id}`).then(r => r.data);
  },

  create(payload: ProductPayload): Promise<{ message: string; data: Product }> {
    return api.post('products', payload).then(r => r.data);
  },

  update(id: number, payload: Partial<ProductPayload>): Promise<{ message: string; data: Product }> {
    return api.put(`products/${id}`, payload).then(r => r.data);
  },

  delete(id: number): Promise<{ message: string }> {
    return api.delete(`products/${id}`).then(r => r.data);
  },
};

export default productService;
