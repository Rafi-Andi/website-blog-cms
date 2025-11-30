import { createRouter, createWebHistory } from 'vue-router'

import Login from '../views/Login.vue'
import Register from '../views/Register.vue'
import DashboardAdmin from '../views/DashboardAdmin.vue'
import DashboardUser from '../views/DashboardUser.vue'
import LatestPosts from '../views/LatestPosts.vue'
import ManageCategories from '../views/ManageCategories.vue'
import CreatePost from '../views/CreatePost.vue'
import CreateCategory from '../views/CreateCategory.vue'
import PostDetail from '../views/PostDetail.vue'
import DashboardUserLayout from '@/layouts/DashboardUserLayout.vue'
import DashboardUserHome from '@/views/DashboardUserHome.vue'
import EditPost from '@/views/EditPost.vue'
const routes = [
  {
    path: '/login',
    name: 'Login',
    component: Login,
  },
  {
    path: '/register',
    name: 'Register',
    component: Register,
  },

  {
    path: '/admin/dashboard',
    name: 'AdminDashboard',
    component: DashboardAdmin,
    meta: { requiresAuth: true, role: 'Admin' },
  },
  {
    path: '/admin/categories',
    name: 'ManageCategories',
    component: ManageCategories,
    meta: { requiresAuth: true, role: 'Admin' },
  },
  {
    path: '/admin/categories/create',
    name: 'CreateCategory',
    component: CreateCategory,
    meta: { requiresAuth: true, role: 'Admin' },
  },

  {
    path: '/user/dashboard',
    name: 'UserDashboard',
    component: DashboardUserLayout,
    meta: { requiresAuth: true, role: 'User' },
    redirect: { name: 'UserDashboardMain' },
    children: [
      {
        path: '',
        name: 'UserDashboardMain',
        component: DashboardUser,
      },
      {
        path: 'homepage',
        name: 'UserDashboardHome',
        component: DashboardUserHome,
      },
      {
        path: 'myposts',
        name: 'MyPosts',
        component: LatestPosts,
        meta: { requiresAuth: true, role: 'User' },
      },
      {
        path: 'posts/edit/:id',
        name: 'EditPost',
        component: EditPost,
        meta: { requiresAuth: true, role: 'User' },
      },
      {
        path: 'posts/create',
        name: 'CreatePost',
        component: CreatePost,
        meta: { requiresAuth: true, role: 'User' },
      },
      {
        path: 'posts/:id',
        name: 'PostDetail',
        component: PostDetail,
      },
    ],
  },

  { path: '/', redirect: '/login' },
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
})

export default router
