import { createRouter, createWebHistory } from 'vue-router';
import HomeView from '../views/HomeView.vue';
import LoginView from '../views/LoginView.vue';
import PostView from '../views/PostView.vue';
import CreatePostView from '../views/CreatePostView.vue';
import EditPostView from '../views/EditPostView.vue';
import SearchView from '../views/SearchView.vue';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
      children: [
        {
          path: ':id',
          name: 'PostView',
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
      path: '/about',
      name: 'about',
      component: LoginView,
    },
    {
      path: '/contact',
      name: 'contact',
      component: LoginView,
    },
    {
      path: '/createPost',
      name: 'createPost',
      component: CreatePostView,
    },
    {
      path: '/editPost/:id',
      name: 'editPost',
      component: EditPostView,
    },
    {
      path: '/Search',
      name: 'Search',
      component: SearchView,
    }


  ],
});

export default router;