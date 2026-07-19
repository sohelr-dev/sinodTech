import axios from "axios";
import { useAuthStore } from "../store/auth";

const baseApiUrl = "http://127.0.0.1:8000/api/";
const baseUrl = "http://127.0.0.1:8000/";

// server configuration (comment out for local)
// const baseApiUrl = "https://banking-system.sohelit.com/banking-system/api/";
// const baseUrl = "https://banking-system.sohelit.com/banking-system/";

export { baseUrl };

const api = axios.create({
  baseURL: baseApiUrl,
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
  },
});

// Request Interceptor: send token with every request if available
api.interceptors.request.use((config) => {
  const token = localStorage.getItem("token"); 
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
}, (error) => {
  return Promise.reject(error);
});

let isLoggingOut =false; // flag to prevent multiple logout attempts
// handle 401 unauthorized globally
api.interceptors.response.use(
  (response) => response,
  async (error) => {
    const auth = useAuthStore();
    // if unauthorized, clear localstorage and redirect to login
    if (error.response && error.response?.status === 401 && !isLoggingOut) {
      isLoggingOut = true;
      try{
        await auth.logout();
      }finally{
        isLoggingOut = false;
      }
   
    }
    return Promise.reject(error);
  }
);

export default api;