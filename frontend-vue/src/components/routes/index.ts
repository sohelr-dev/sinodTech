import { createRouter, createWebHistory } from "vue-router";
import Dashboard from "../view/pages/admin/Dashboard.vue";
import Products from "../view/pages/admin/Products.vue";
import Checkout from "../view/pages/admin/Checkout.vue";
import Customers from "../view/pages/admin/Customers.vue";
import CustomerDetails from "../view/pages/admin/CustomerDetails.vue";
import Employees from "../view/pages/admin/Employees.vue";
import Inventory from "../view/pages/admin/Inventory.vue";
import Promotions from "../view/pages/admin/Promotions.vue";
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

function homePath(role: string): string {
  switch (role) {
    case 'admin':   return '/dashboard';
    case 'manager': return '/manager/dashboard';
    case 'sales':   return '/sales/dashboard';
    case 'editor':  return '/editor/dashboard';
    default:        return '/login';
  }
}

const routes = [
  { path: '/login', component: Login, meta: { guestOnly: true } },
  { path: '/forget-password', component: ForgetPassword, meta: { guestOnly: true } },
  { path: '/varify-code', component: VarifyCode, meta: { guestOnly: true } },
  { path: '/change-password', component: ChangePassword, meta: { guestOnly: true } },

  //Admin
  {
    path: '/', component: DefaultLayout,
    children: [
      { path: '', redirect: '/dashboard' },
      { path: 'dashboard',   component: Dashboard,       meta: { requiresAuth: true, roles: ['admin'] } },
      { path: 'promotions',  component: Promotions,      meta: { requiresAuth: true, roles: ['admin'] } },
      { path: 'crm',         component: Customers,       meta: { requiresAuth: true, roles: ['admin', 'manager'] } },
      { path: 'crm/:id',     component: CustomerDetails, meta: { requiresAuth: true, roles: ['admin', 'manager'] } },
      { path: 'employees',   component: Employees,       meta: { requiresAuth: true, roles: ['admin', 'manager'] } },

      // Shared pages — multiple roles can access
      { path: 'products',    component: Products,        meta: { requiresAuth: true, roles: ['admin', 'editor'] } },
      { path: 'sales',       component: Checkout,        meta: { requiresAuth: true, roles: ['admin', 'sales'] } },
      { path: 'inventory',   component: Inventory,       meta: { requiresAuth: true, roles: ['admin', 'editor', 'manager'] } },
    ]
  },

  //Manager Dashboard
  {
    path: '/manager', component: DefaultLayout,
    children: [
      { path: '', redirect: 'dashboard' },
      { path: 'dashboard', component: ManagerDashboard, meta: { requiresAuth: true, roles: ['manager'] } },
    ]
  },

  //Sales Dashboard
  {
    path: '/sales', component: DefaultLayout,
    children: [
      { path: '', redirect: 'dashboard' },
      { path: 'dashboard', component: SalesDashboard, meta: { requiresAuth: true, roles: ['sales'] } },
    ]
  },

  // ─── Editor Dashboard ─────────────────────────────────────────────────
  {
    path: '/editor', component: DefaultLayout,
    children: [
      { path: '', redirect: 'dashboard' },
      { path: 'dashboard', component: EditorDashboard, meta: { requiresAuth: true, roles: ['editor'] } },
    ]
  },

  { path: '/:pathMatch(.*)*', name: 'NotFound', component: NotFound }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

router.beforeEach((to, _from, next) => {
  const auth = useAuthStore();
  const userRole = auth.user?.role || '';

  if (to.meta.requiresAuth) {
    if (!auth.isAuthenticated) return next('/login');

    const allowedRoles = to.meta.roles as string[] | undefined;
    if (allowedRoles && !allowedRoles.includes(userRole)) {
      return next(homePath(userRole));
    }
  }

  // Guest-only
  if (to.meta.guestOnly && auth.isAuthenticated) {
    return next(homePath(userRole));
  }

  next();
});

export default router;