import api from '../config/config';

export interface Employee {
  id: number;
  name: string;
  email: string;
  role: string;
  kpi_score: number;
  total_sales: number;
  total_revenue: number;
}

const employeeService = {
  getAll(): Promise<{ data: Employee[] }> {
    return api.get('employees').then(r => r.data);
  },
};

export default employeeService;
