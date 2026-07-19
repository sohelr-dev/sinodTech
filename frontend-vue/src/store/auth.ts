import { defineStore } from "pinia";
import api from "../config/config";
import router from "../components/routes";

export interface UserType {
  id: number;
  name: string;
  email: string;
  role: string;
  photo?: string;
}

export interface AuthResponse {
  user: UserType;
  token: string;
  expires_at: string;
}

type AuthState = {
  user: UserType | null;
  token: string;
  expires_at: string | null;
};

export const useAuthStore = defineStore("auth", {
  state: (): AuthState => ({
    user: localStorage.getItem("user") ? JSON.parse(localStorage.getItem("user") as string) : null,
    token: localStorage.getItem("token") || "",
    expires_at: localStorage.getItem("expires_at") || null,
  }),

  getters: {
    isAuthenticated: (state) => !!state.token && state.user !== null,
    userRole: (state) => state.user?.role ?? null,
  },

  actions: {
    setAuth(data: AuthResponse) {
      // set state
      this.user = data.user;
      this.token = data.token;
      this.expires_at = data.expires_at;

      try {
        localStorage.setItem("user", JSON.stringify(data.user));
        localStorage.setItem("token", data.token);
        localStorage.setItem("expires_at", data.expires_at);
        
        // api default header set
        api.defaults.headers.common['Authorization'] = `Bearer ${data.token}`;
      } catch (e) {
        console.error("localStorage set failed", e);
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
        // cleanup regardless of api response
        this.user = null;
        this.token = "";
        this.expires_at = null;

        // clean up localstorage
        localStorage.removeItem('user');
        localStorage.removeItem('token');
        localStorage.removeItem('expires_at');
        
        delete api.defaults.headers.common['Authorization'];
        
        router.replace("/login");
      }
    },
  },
});