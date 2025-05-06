import { createRouter, createWebHistory } from 'vue-router';
import HomeView from '../views/HomeView.vue';
import LoginView from '../views/LoginView.vue';
import PostView from '../views/PostView.vue';
import CreatePostView from '../views/CreatePostView.vue';
import EditPostView from '../views/EditPostView.vue';
import SearchView from '../views/SearchView.vue';
import ProfileView from '../views/ProfileView.vue';
import UserManagementView from '../views/UserManagementView.vue';
import YearManagementView from '../views/YearManagementView.vue';
import PostManagementView from '@/views/PostManagementView.vue';
import { useCounterStore } from '@/stores/counter';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "Home",
      component: HomeView,
    },
    {
      path: "/:year",
      name: "Year",
      component: HomeView,
      props: true,
      children: [
        {
          path: "post/:postId",
          name: "Post",
          component: PostView,
          props: true,
        },
      ],
    },
    {
      path: '/login',
      name: 'login',
      component: LoginView,
      meta: { 
        requiresGuest: true // Túto stránku môžu vidieť iba neautentifikovaní používatelia
      }
    },
    {
      path: '/search',
      name: 'Search',
      component: SearchView,
    },
    {
      path: '/profile',
      name: 'Profile',
      component: ProfileView,
      meta: { 
        requiresAuth: true // Túto stránku môžu vidieť iba autentifikovaní používatelia
      }
    },
    {
      path: '/user-management',
      name: 'UserManagement',
      component: UserManagementView,
      meta: { 
        requiresAuth: true
      }
    },
    {
      path: '/year-management',
      name: 'YearManagement',
      component: YearManagementView,
      meta: { 
        requiresAuth: true
      }
    },
    {
      path: '/post-management',
      name: 'PostManagement',
      component: PostManagementView,
      meta: { 
        requiresAuth: true
      }
    },
    {
      path: '/createPost',
      name: 'CreatePost',
      component: CreatePostView,
      meta: { 
        requiresAuth: true
      }
    },
    {
      path: '/editPost/:id',
      name: 'EditPost',
      component: EditPostView,
      meta: { 
        requiresAuth: true
      }
    },
  ],
});

// Stráženie chránených stránok
router.beforeEach((to, from, next) => {
  const counterStore = useCounterStore();
  const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
  const requiresGuest = to.matched.some(record => record.meta.requiresGuest);

  // Ak stránka vyžaduje prihlásenie a používateľ nie je prihlásený
  if (requiresAuth && !counterStore.isAuthenticated) {
    next('/login');
  }
  // Ak stránka je určená pre neprihlásených a používateľ je prihlásený
  else if (requiresGuest && counterStore.isAuthenticated) {
    next('/');
  }
  // V ostatných prípadoch pokračuj normálne
  else {
    next();
  }
});

export default router;