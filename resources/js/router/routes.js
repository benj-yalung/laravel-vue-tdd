function page (path) {
  return () => import(/* webpackChunkName: '' */ `~/pages/${path}`).then(m => m.default || m)
}

export default [
  { path: '/', name: 'welcome', component: page('welcome.vue') },

  { path: '/login', name: 'login', component: page('auth/login.vue') },
  { path: '/register', name: 'register', component: page('auth/register.vue') },
  { path: '/password/reset', name: 'password.request', component: page('auth/password/email.vue') },
  { path: '/password/reset/:token', name: 'password.reset', component: page('auth/password/reset.vue') },
  { path: '/email/verify/:id', name: 'verification.verify', component: page('auth/verification/verify.vue') },
  { path: '/email/resend', name: 'verification.resend', component: page('auth/verification/resend.vue') },

  { path: '/dashboard', name: 'user.dashboard', meta: { requiresAuth: true, adminAuth: false, userAuth: true }, component: page('users/dashboard.vue') },
  {
    path: '/website/post',
    component: page('WebsitePost/Index.vue'),
    children: [
      { path: '', redirect: { name: 'website.post.list' }, meta: { requiresAuth: true, adminAuth: true, userAuth: true } },
      { path: 'list/:id', name: 'website.post.list',  meta: { requiresAuth: true, adminAuth: true, userAuth: true }, component: page('WebsitePost/List.vue')},
      { path: 'view/:id', name: 'website.post.single',  meta: { requiresAuth: true, adminAuth: true, userAuth: true }, component: page('WebsitePost/Single.vue')},
      { path: 'create/:id', name: 'website.post.create',  meta: { requiresAuth: true, adminAuth: true, userAuth: false }, component: page('WebsitePost/Create.vue')},
      { path: 'edit/:id', name: 'website.post.edit',  meta: { requiresAuth: true, adminAuth: true, userAuth: false }, component: page('WebsitePost/Edit.vue')},
    ]
  },
  {
    path: '/user/website',
    component: page('users/Website/Index.vue'),
    children: [
      { path: '', redirect: { name: 'user.websites' }, meta: { requiresAuth: true, adminAuth: true, userAuth: false } },
      { path: 'list', name: 'user.websites',  meta: { requiresAuth: true, adminAuth: false, userAuth: true }, component: page('users/Website/List.vue')},
    ]
  },
  {
    path: '/user/website/subscription',
    component: page('users/WebsiteSubscription/Index.vue'),
    children: [
      { path: '', redirect: { name: 'user.website.subscriptions' }, meta: { requiresAuth: true, adminAuth: false, userAuth: true } },
      { path: 'list', name: 'user.website.subscriptions',  meta: { requiresAuth: true, adminAuth: false, userAuth: true }, component: page('users/WebsiteSubscription/List.vue')},
    ]
  },
  { path: '/admin/dashboard', name: 'admin.dashboard', meta: { requiresAuth: true, adminAuth: true, userAuth: false }, component: page('admin/dashboard.vue') },
  {
    path: '/admin/website',
    component: page('admin/Website/Index.vue'),
    children: [
      { path: '', redirect: { name: 'admin.website' }, meta: { requiresAuth: true, adminAuth: true, userAuth: false } },
      { path: 'list', name: 'admin.website',  meta: { requiresAuth: true, adminAuth: true, userAuth: false }, component: page('admin/Website/List.vue')},
      { path: 'create', name: 'admin.website.create',  meta: { requiresAuth: true, adminAuth: true, userAuth: false }, component: page('admin/Website/Create.vue')},
      { path: 'edit/:id', name: 'admin.website.edit',  meta: { requiresAuth: true, adminAuth: true, userAuth: false }, component: page('admin/Website/Edit.vue')},
    ]
  },
  {
    path: '/admin/website/subscribers',
    component: page('admin/WebsiteSubscriber/Index.vue'),
    children: [
      { path: '', redirect: { name: 'admin.website.subscribers' }, meta: { requiresAuth: true, adminAuth: true, userAuth: false } },
      { path: 'list', name: 'admin.website.subscribers',  meta: { requiresAuth: true, adminAuth: true, userAuth: false }, component: page('admin/WebsiteSubscriber/List.vue')}
    ]
  },
  {
    path: '/settings',
    component: page('settings/index.vue'),
    children: [
      { path: '', redirect: { name: 'settings.profile' } },
      { path: 'profile', name: 'settings.profile', component: page('settings/profile.vue') },
      { path: 'password', name: 'settings.password', component: page('settings/password.vue') }
    ]
  },

  { path: '*', component: page('errors/404.vue') }
]
