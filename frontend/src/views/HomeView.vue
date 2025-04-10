<script>
import axios from 'axios';
import Navbar from '../components/NavBarComponent.vue';
import Sidebar from '../components/SideBarComponent.vue';
import Footer from '../components/FooterComponent.vue';
import Post from '../components/PostComponent.vue';

export default {
  name: 'HomeView',
  components: {
    Navbar,
    Sidebar,
    Footer,
    Post,
  },
  data() {
    return {
      posts: [],
    };
  },
  methods: {
    async fetchPosts() {
      try {
        const response = await axios.get('http://127.0.0.1:8000/api/posts');
        this.posts = response.data;
      } catch (error) {
        console.error('Error fetching posts:', error);
      }
    },
    handleEditPost(post) {

    },
    async handleDeletePost(postId) {
      try {
        console.log('Post ID to delete:', postId);
        await axios.get(`http://127.0.0.1:8000/api/deletePost/${postId}`);
        this.posts = this.posts.filter(post => post.id !== postId);
      } catch (error) {
        console.error('Error deleting post:', error);
      }
    },
  },
  mounted() {
    this.fetchPosts();
  },
};
</script>

<template>
  <main>
    <div class="app-container">
      <Navbar class="top-navbar" />
      <div class="content-wrapper">
        <Sidebar class="left-sidebar" />
        <main class="main-content">
          <div class="posts-container">
            <Post
              v-for="(post, index) in posts"
              :key="index"
              :post="post"
              @edit-post="handleEditPost"
              @delete-post="handleDeletePost"
            />
          </div>
        </main>
      </div>
    </div>
    <footer class="footer">
      <Footer />
    </footer>
  </main>
</template>

<style scoped>
</style>