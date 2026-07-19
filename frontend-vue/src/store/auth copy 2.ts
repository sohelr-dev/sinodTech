import { defineStore } from "pinia";
import api from "../config/config";

export interface UserType {
  id: number;
  name: string;
  email: string;
  role: 'admin' | 'manager' | 'sales' | 'editor';
  photo?: string;
}

export interface AuthResponse {
  token: string;
  expires_at: string;
}

type AuthState = {
  user: UserType | null;
  token: string;
  expires_at: string | null;
  loading: boolean;
};

export const useAuthStore = defineStore("auth", {
  state: (): AuthState => ({
    user:  null,
    token: localStorage.getItem("token") || "",
    expires_at: localStorage.getItem("expires_at") || null,
    loading:false,
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
    userRole: (state) => state.user?.role ?? null,
  },

  actions: {
    setAuth(data: AuthResponse) {
      //save token only
      this.token = data.token;
      this.expires_at = data.expires_at;

      try {
        localStorage.setItem("token", data.token);
        localStorage.setItem("expires_at", data.expires_at);
      } catch (e) {
        console.error("localStorage set failed", e);
      }
    },

    //load user from backend
    async fetchUser(){
      if(!this.token) return;

      try{
        this.loading = true;
        const res = await api.get('user');
        console.log(res.data);
        this.user =res.data;
      }catch(error){
        this.clearAuthData();
        throw error;
      }finally{
        this.loading = false;
      }
    },

    clearAuthData(){
      this.user = null;
      this.token = "";
      this.expires_at = null;

      localStorage.removeItem('token');
      localStorage.removeItem('expires_at');
      delete api.defaults.headers.common['Authorization'];
    },

    //login user
    async Login(formData: {
      email: string;
      password: string;
      }){
        try{
          const res = await api.post('auth/login', formData);
          console.log(res.data);
          this.setAuth(res.data.data);
          await this.fetchUser();

        }catch(error){
          console.log(error)
          throw error;
        }
      },

    async logout() {
      try {
        if (this.token) {
          await api.post("logout");
        }
      } catch (e) {
        console.warn('Server Logout failed', e);
      } finally {
        this.clearAuthData();
        window.location.href="/login";
      }
    },
  },
});