import api from '../config/config';

export interface SaleItem {
  product_id: number;
  product_name?: string;
  quantity: number;
  unit_price: number;
  subtotal: number;
}

export interface Sale {
  id: number;
  invoice_number: string;
  total_amount: number;
  status: string;
  branch: { id: number; name: string };
  customer: { id: number; name: string; email: string } | null;
  employee: { id: number; name: string } | null;
  items: SaleItem[];
  created_at: string;
}

export interface SalePayload {
  customer_id?: number | null;
  branch_id: number;
  employee_id?: number | null;
  items: { product_id: number; quantity: number }[];
}

const saleService = {
  getAll(limit = 20): Promise<{ data: Sale[] }> {
    return api.get(`sales?limit=${limit}`).then(r => r.data);
  },

  getById(id: number): Promise<{ data: Sale }> {
    return api.get(`sales/${id}`).then(r => r.data);
  },

  create(payload: SalePayload): Promise<{ message: string; data: Sale }> {
    return api.post('sales', payload).then(r => r.data);
  },
};

export default saleService;
