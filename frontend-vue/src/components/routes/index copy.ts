import { createRouter, createWebHistory } from "vue-router";
import Dashboard from "../view/pages/admin/Dashboard.vue";
import Login from "../view/auth/Login.vue";
import Registration from "../view/auth/Registration.vue";
import DefaultLayout from "../view/layouts/DefaultLayout.vue";
import { useAuthStore } from "../../store/auth";
import ManagerDashboard from "../view/pages/manager/ManagerDashboard.vue";
import SalesDashboard from "../view/pages/sales/SalesDashboard.vue";
import EditorDashboard from "../view/pages/editor/EditorDashboard.vue";
import NotFound from "../view/errors/NotFound.vue";


const routes = [
  { path: '/login', component: Login, meta: { guestOnly: true } },
  { path: '/register', component: Registration, meta: { guestOnly: true } },

  { 
    path: '/', component: DefaultLayout,
    children: [
      {path: '', redirect: 'admin/dashboard'},
      { path: 'dashboard', component: Dashboard, meta: { requiresAuth: true, role: 'admin' } },
      
    ]
  },
  {
    path:'/manager', component: DefaultLayout,
    children: [
      { path: 'dashboard', component: ManagerDashboard, meta: { requiresAuth: true, role: 'manager' } },
      
    ]
  },
  {
    path:'/sales', component: DefaultLayout,
    children: [
      { path: 'dashboard', component: SalesDashboard, meta: { requiresAuth: true, role: 'sales' } },
      
    ]
  },
  {
    path:'/editor', component: DefaultLayout,
    children: [
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

router.beforeEach(async(to, from, next) => {
  const auth = useAuthStore();
  //refresh and token validation handling
  if(auth.token && !auth.user ){
    try{
      // await auth.fetchUser(); //wait for user data loading before proceeding
    }catch(error){
      return next('/login'); // if fetching user fails, redirect to login
    }
  }

  //role based access control
  const isAuthenticated = !!auth.token;
  const userRole = auth.user?.role || '';
  const roleRedirects: Record<string, string> = {
    'admin' : '/admin/dashboard',
    'manager': '/manager/dashboard',
    'sales': '/sales/dashboard',
    'editor': '/editor/dashboard'
  }

  if (to.meta.requiresAuth) {
    if (!isAuthenticated) return next('/login');

    //check role
    if (to.meta.role && userRole !== to.meta.role) {
      // redirect to own dashboard
      const correctPath = roleRedirects[userRole as keyof typeof roleRedirects] || '/login';
      return next(correctPath);
    }
  }

  // guest only
  if (to.meta.guestOnly && isAuthenticated) {
    // logged in user -> redirect
    const dashboardPath = roleRedirects[userRole] || '/dashboard';
    return next(dashboardPath);
  }

  next()
})

export default router;