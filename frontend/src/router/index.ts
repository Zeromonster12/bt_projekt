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
    },
    {
      path: '/user-management',
      name: 'UserManagement',
      component: UserManagementView,
    },
    {
      path: '/year-management',
      name: 'YearManagement',
      component: YearManagementView,
    },
    {
      path: '/post-management',
      name: 'PostManagement',
      component: PostManagementView,
    },

    {
      path: '/createPost',
      name: 'CreatePost',
      component: CreatePostView,
    },
    {
      path: '/editPost/:id',
      name: 'EditPost',
      component: EditPostView,
    },

  ],
});

export default router;