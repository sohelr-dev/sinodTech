import api from '../config/config';

export interface Customer {
  id: number;
  name: string;
  email: string | null;
  phone: string | null;
  last_purchase_at: string | null;
  is_lost: boolean;
  purchase_frequency: number;
  total_spent: number;
  created_at: string;
  sales?: any[];
}

const customerService = {
  getAll(lost = false): Promise<{ data: Customer[] }> {
    return api.get(`customers?lost=${lost ? 1 : 0}`).then(r => r.data);
  },

  getById(id: number): Promise<{ data: Customer }> {
    return api.get(`customers/${id}`).then(r => r.data);
  },
};

export default customerService;
