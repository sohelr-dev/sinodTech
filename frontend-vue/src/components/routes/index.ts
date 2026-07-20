import { createRouter, createWebHistory } from "vue-router";
import Dashboard from "../view/pages/admin/Dashboard.vue";
import Products from "../view/pages/admin/Products.vue";
import Checkout from "../view/pages/admin/Checkout.vue";
import Customers from "../view/pages/admin/Customers.vue";
import CustomerDetails from "../view/pages/admin/CustomerDetails.vue";
import Login from "../view/auth/Login.vue";
import DefaultLayout from "../view/layouts/DefaultLayout.vue";
import { useAuthStore } from "../../store/auth";
import ManagerDashboard from "../view/pages/manager/ManagerDashboard.vue";
import SalesDashboard from "../view/pages/sales/SalesDashboard.vue";
import EditorDashboard from "../view/pages/editor/EditorDashboard.vue";
import NotFound from "../view/errors/NotFound.vue";
import ForgetPassword from "../view/auth/ForgetPassword.vue";
import VarifyCode from "../view/auth/VarifyCode.vue";
import ChangePassword from "../view/auth/ChangePassword.vue";


const routes = [
  { path: '/login', component: Login, meta: { guestOnly: true } },
  { path: '/forget-password', component: ForgetPassword, meta: { guestOnly: true } },
  { path: '/varify-code', component: VarifyCode, meta: { guestOnly: true } },
  { path: '/change-password', component: ChangePassword, meta: { guestOnly: true } },

  { 
    path: '/', component: DefaultLayout,
    children: [
      {path: '', redirect: '/dashboard'},
      { path: 'dashboard', component: Dashboard, meta: { requiresAuth: true, role: 'admin' } },
      { path: 'products', component: Products, meta: { requiresAuth: true, role: 'admin' } },
      { path: 'sales', component: Checkout, meta: { requiresAuth: true, role: 'admin' } },
      { path: 'crm', component: Customers, meta: { requiresAuth: true, role: 'admin' } },
      { path: 'crm/:id', component: CustomerDetails, meta: { requiresAuth: true, role: 'admin' } },
      
    ]
  },
  {
    path:'/manager', component: DefaultLayout,
    children: [
      { path: '', redirect: 'dashboard'},
      { path: 'dashboard', component: ManagerDashboard, meta: { requiresAuth: true, role: 'manager' } },
      
    ]
  },
  {
    path:'/sales', component: DefaultLayout,
    children: [
      { path: '', redirect: 'dashboard'},
      { path: 'dashboard', component: SalesDashboard, meta: { requiresAuth: true, role: 'sales' } },
      
    ]
  },
  {
    path:'/editor', component: DefaultLayout,
    children: [
      { path: '', redirect: 'dashboard'},
      { path: 'dashboard', component: EditorDashboard, meta: { requiresAuth: true, role: 'editor' } },
      
    ]
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'NotFound',
    component: NotFound
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach((to, from, next) => {
  const auth = useAuthStore();
  const userRole = auth.user?.role || '';

  if (to.meta.requiresAuth) {
    if (!auth.isAuthenticated) return next('/login');

    if (to.meta.role && userRole !== to.meta.role) {
      // redirect to own dashboard
      switch(userRole) {
        case 'admin': return next('/dashboard'); // admin
        case 'manager': return next('/manager/dashboard');
        case 'sales': return next('/sales/dashboard');
        case 'editor': return next('/editor/dashboard');
      }
    }
  }

  // guest only
  if (to.meta.guestOnly && auth.isAuthenticated) {
    // logged in user -> redirect
    switch(auth.userRole) {
      case 'admin': return next('/dashboard');
      case 'manager': return next('/manager/dashboard');
      case 'sales': return next('/sales/dashboard');
      case 'editor': return next('/editor/dashboard');
    }
  }

  next()
})

export default router;