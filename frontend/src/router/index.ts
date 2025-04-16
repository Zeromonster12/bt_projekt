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
      component: HomeView,
      children: [
        {
          path: '',
          redirect: '/2025/post/1',
        },
        {
          path: ':year/post/:id',
          name: 'PostView',
          component: PostView,
          props: true,
        },
        {
          path: 'createPost',
          name: 'createPost',
          component: CreatePostView,
        },
        {
          path: 'editPost/:id',
          name: 'editPost',
          component: EditPostView,
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
    }

  ],
});

export default router;