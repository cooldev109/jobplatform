import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/',
      redirect: () => (useAuthStore().isAuthenticated ? '/dashboard' : '/login'),
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('../views/auth/Login.vue'),
      meta: { guestOnly: true },
    },
    {
      path: '/register',
      name: 'register',
      component: () => import('../views/auth/Register.vue'),
      meta: { guestOnly: true },
    },
    {
      path: '/dashboard',
      name: 'dashboard',
      component: () => import('../views/Dashboard.vue'),
      meta: { requiresAuth: true },
    },
    {
      path: '/profile',
      name: 'candidate-profile',
      component: () => import('../views/CandidateProfile.vue'),
      meta: { requiresAuth: true, userType: 'candidate' },
    },
    {
      path: '/companies/new',
      name: 'company-create',
      component: () => import('../views/CompanyForm.vue'),
      meta: { requiresAuth: true, userType: 'company_owner' },
    },
    {
      path: '/companies/:id/edit',
      name: 'company-edit',
      component: () => import('../views/CompanyForm.vue'),
      meta: { requiresAuth: true, userType: 'company_owner' },
    },
  ],
})

router.beforeEach((to) => {
  const auth = useAuthStore()
  if (to.meta.requiresAuth && !auth.isAuthenticated) return { name: 'login' }
  if (to.meta.guestOnly && auth.isAuthenticated) return { name: 'dashboard' }
  if (to.meta.userType && auth.user?.user_type !== to.meta.userType) return { name: 'dashboard' }
})

export default router
