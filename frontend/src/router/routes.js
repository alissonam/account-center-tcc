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
    meta: { permission: ['admin', 'member'] }
  }
]

const products = [
  {
    path: '/products',
    name: 'products',
    component: () => import('pages/Products/ProductsListPage'),
    beforeEnter: checkPermission,
    meta: { permission: ['admin'] }
  },
  {
    path: '/products/create',
    name: 'products_create',
    component: () => import('pages/Products/ProductFormPage'),
    beforeEnter: checkPermission,
    meta: { permission: ['admin'] }
  },
  {
    path: '/products/update/:id',
    name: 'products_update',
    component: () => import('pages/Products/ProductFormPage'),
    beforeEnter: checkPermission,
    meta: { permission: ['admin'] }
  }
]

const plans = [
  {
    path: '/plans',
    name: 'plans',
    component: () => import('pages/Plans/PlansList'),
    beforeEnter: checkPermission,
    meta: { permission: ['admin'] }
  },
  {
    path: '/plans/create',
    name: 'plans_create',
    component: () => import('pages/Plans/PlansForm'),
    beforeEnter: checkPermission,
    meta: { permission: ['admin'] }
  },
  {
    path: '/plans/update/:id',
    name: 'plans_update',
    component: () => import('pages/Plans/PlansForm'),
    beforeEnter: checkPermission,
    meta: { permission: ['admin'] }
  }
]

const clientPlans = [
  {
    path: '/client-plans/:code',
    name: 'client_plans',
    component: () => import('pages/ClientPlans/ClientPlansList'),
    beforeEnter: checkPermission,
    meta: { permission: ['member', 'admin'] }
  }
]

const clientProducts = [
  {
    path: '/client-products',
    name: 'client_products',
    component: () => import('components/client-products/ClientProducts'),
    beforeEnter: checkPermission,
    meta: { permission: ['member', 'admin'] }
  }
]

const sugestions = [
  {
    path: '/sugestions/:product_id',
    name: 'sugestions',
    component: () => import('pages/Sugestions/SugestionList'),
    beforeEnter: checkPermission,
    meta: { permission: ['member', 'admin']}
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
      ...products,
      ...users,
      ...plans,
      ...subscriptions,
      ...clientPlans,
      ...clientProducts,
      ...sugestions,
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
