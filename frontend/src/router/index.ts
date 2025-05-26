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
        requiresGuest: true
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
        requiresAuth: true
      }
    },
    {
      path: '/user-management',
      name: 'UserManagement',
      component: UserManagementView,
      meta: { 
        requiresAdmin: true
      }
    },
    {
      path: '/year-management',
      name: 'YearManagement',
      component: YearManagementView,
      meta: { 
        requiresAdmin: true
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
    },  ],
});

router.beforeEach(async (to, from, next) => {
  const counterStore = useCounterStore();
  const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
  const requiresGuest = to.matched.some(record => record.meta.requiresGuest);
  const requiresAdmin = to.matched.some(record => record.meta.requiresAdmin);

  if (requiresAuth && !counterStore.isAuthenticated && localStorage.getItem('user')) {
    try {
      await counterStore.checkAuth();
    } catch (error) {
      return next('/login');
    }
  }
  if (requiresAdmin && counterStore.user?.role_id !== 1) {
    return next('/');
    }

  if (requiresAuth && !counterStore.isAuthenticated) {
    return next('/login');
  } else if (requiresGuest && counterStore.isAuthenticated) {
    return next('/');
  } else {
    return next();
  }
  
  
});

export default router;