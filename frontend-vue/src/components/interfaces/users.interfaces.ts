export interface UserType {
  id: number;
  name: string;
  email: string;

  password?: string
  password_confirmation?: string;
  phone?: string;
  role?: string;
  kyc_status: 'pending' | 'verified' | 'rejected';
  meta?: Record<string, any>;
}

export const defaultUser: UserType = {
  id: 0,
  name: '',
  email: '',
  password:'',
  password_confirmation:'',
  phone: '',
  role: '',
  kyc_status: 'pending',
  meta: {}
}