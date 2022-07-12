import { loggedUser } from '../boot/user'

const redirectToDashboardIfLogged = (to, from, next) => {
  if (localStorage.getItem('isUserLogged')) {
    next('/')
  } else {
    next()
  }
}

const redirectToLoginIfNotLogged = (to, from, next) => {
  if (localStorage.getItem('isUserLogged')) {
    next()
  } else {
    next('/login')
  }
}

const checkPermission = (to, from, next) => {
  if ((to.meta.permission || []).includes(loggedUser.role)) {
    next()
  } else {
    next('/')
  }
}

const permissions = [
  {
    path: '/permissions',
    name: 'permissions',
    component: () => import('pages/Permissions/PermissionsList'),
    beforeEnter: checkPermission,
    meta: { permission: ['admin'] }
  },
  {
    path: '/permissions/create',
    name: 'permissions_create',
    component: () => import('pages/Permissions/PermissionsForm'),
    beforeEnter: checkPermission,
    meta: { permission: ['admin'] }
  },
  {
    path: '/permissions/update/:id',
    name: 'permissions_update',
    component: () => import('pages/Permissions/PermissionsForm'),
    beforeEnter: checkPermission,
    meta: { permission: ['admin'] }
  }
]

const subscriptions = [
  {
    path: '/subscriptions',
    name: 'subscriptions',
    component: () => import('pages/Subscriptions/SubscriptionsList'),
    beforeEnter: checkPermission,
    meta: { permission: ['admin', 'member'] }
  },
  {
    path: '/subscriptions/create',
    name: 'subscriptions_create',
    component: () => import('pages/Subscriptions/SubscriptionsForm'),
    beforeEnter: checkPermission,
    meta: { permission: ['admin', 'member'] }
  },
  {
    path: '/subscriptions/update/:id',
    name: 'subscriptions_update',
    component: () => import('pages/Subscriptions/SubscriptionsForm'),
    beforeEnter: checkPermission,
    meta: { permission: ['admin', 'member'] }
  }
]

const users = [
  {
    path: '/users',
    name: 'users',
    component: () => import('pages/Users/UsersList'),
    beforeEnter: checkPermission,
    meta: { permission: ['admin'] }
  },
  {
    path: '/users/create',
    name: 'users_create',
    component: () => import('pages/Users/UsersForm'),
    beforeEnter: checkPermission,
    meta: { permission: ['admin'] }
  },
  {
    path: '/users/update/:id',
    name: 'users_update',
    component: () => import('pages/Users/UsersForm'),
    beforeEnter: checkPermission,
    meta: { permission: ['admin'] }
  }
]

const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout'),
    beforeEnter: redirectToLoginIfNotLogged,
    children: [
      {
        path: '',
        name: 'home',
        redirect: '/dashboard'
      },
      {
        path: 'dashboard',
        name: 'dashboard',
        component: () => import('pages/Index')
      },
      ...permissions,
      ...users,
      ...subscriptions,
    ]
  },
  {
    path: '/login',
    name: 'login',
    beforeEnter: redirectToDashboardIfLogged,
    component: () => import('pages/Login/PageLogin')
  },
  {
    path: '/reset-password/:token',
    name: 'reset_password',
    beforeEnter: redirectToDashboardIfLogged,
    component: () => import('pages/Login/ResetPasswordPage')
  },
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/Error404')
  },
  {
    path: '/register',
    name: 'register',
    component: () => import('pages/Register/PageRegisterUser')
  },
]

export default routes
