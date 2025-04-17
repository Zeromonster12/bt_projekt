<template>
  <main>
    <div class="app-container">
      <Navbar class="top-navbar" />
      <YearBar class="year-bar" @year-selected="postStore.filterPostsByYear" />
      <div class="content-wrapper">
        <h1>{{ user }}</h1>
        <Sidebar
          class="left-sidebar"
          :posts="postStore.filteredPosts"
          :currentYear="postStore.currentYear"
          @post-deleted="postStore.handlePostDeleted"
        />
        <main class="main-content">
          <router-view />
        </main>
      </div>
    </div>
    <footer class="footer">
      <Footer />
    </footer>
  </main>
</template>

<script>
import Navbar from "../components/NavBarComponent.vue";
import Sidebar from "../components/SideBarComponent.vue";
import Footer from "../components/FooterComponent.vue";
import YearBar from "../components/YearBarComponent.vue";
import api from "../api";
import { usePostStore } from "../stores/postStore";

export default {
  name: "HomeView",
  components: {
    Navbar,
    Sidebar,
    Footer,
    YearBar,
  },
  data() {
    return {
      postStore: usePostStore(),
    };
  },
  mounted() {
    this.postStore.fetchPosts();
  },
};
</script>

<style scoped>
</style>