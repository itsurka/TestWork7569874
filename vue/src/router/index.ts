import { createRouter, createWebHashHistory, RouteRecordRaw } from 'vue-router'
import BlogIndex from "@/components/blog/views/BlogIndex.vue";

import Login from "@/components/site/Login.vue";
import Registration from "@/components/site/Registration.vue";
import RegistrationConfirmEmail from "@/components/site/RegistrationConfirmEmail.vue";

import CarsIndex from "@/components/cars/views/CarsIndex.vue";

import ProfileCarsIndex from "@/components/profile/views/ProfileCarsIndex.vue";
import ProfileCarsCreate from "@/components/profile/views/ProfileCarCreate.vue";

const routes: Array<RouteRecordRaw> = [
  {
    path: '/',
    name: 'blog.index',
    component: BlogIndex,
  },
  {
    path: '/login',
    name: 'login.index',
    component: Login,
  },
  {
    path: '/registration',
    name: 'registration.index',
    component: Registration,
  },
  {
    path: '/registration/confirm-email',
    name: 'registration.confirm-email',
    component: RegistrationConfirmEmail,
  },
  {
    path: '/cars',
    name: 'cars.index',
    component: CarsIndex
  },
  {
    path: '/profile/cars/add',
    name: 'profile.cars.create',
    component: ProfileCarsCreate,
  },
  {
    path: '/profile/cars',
    name: 'profile.cars.index',
    component: ProfileCarsIndex,
  },
  {
    path: '/post/:slug',
    name: 'blog.show',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "blog-show" */ '../components/blog/views/SinglePost.vue')
  },
  {
    path: '/add',
    name: 'blog.create',
    component: () => import(/* webpackChunkName: "blog-create" */ '../components/blog/views/AddPost.vue')
  }
]

const router = createRouter({
  history: createWebHashHistory(),
  routes
})

export default router
