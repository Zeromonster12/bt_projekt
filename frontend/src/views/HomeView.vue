<template>
  <main>
    <div class="app-container">
      <Navbar class="top-navbar" />
      <YearBar class="year-bar" @year-selected="filterPostsByYear" />
      <div class="content-wrapper">
        <Sidebar
          class="left-sidebar"
          :posts="filteredPosts"
          :currentYear="currentYear"
          @post-deleted="handlePostDeleted"
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
import axios from "axios";

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
      posts: [],
      filteredPosts: [],
      currentYear: new Date().getFullYear(),
    };
  },
  methods: {
    async fetchPosts() {
      try {
        const response = await axios.get("http://127.0.0.1:8000/api/posts");
        this.posts = response.data;
        this.filterPostsByYear(this.currentYear);
      } catch (error) {
        console.error("Error fetching posts:", error);
      }
    },
    filterPostsByYear(year) {
      this.currentYear = year;
      this.filteredPosts = this.posts.filter((post) => post.year === year);
    },
    handlePostDeleted(postId) {
      this.filteredPosts = this.filteredPosts.filter((post) => post.id !== postId);
    },
  },
  watch: {
    '$route'(to) {
      if (to.params.year) {
        const year = parseInt(to.params.year);
        if (year !== this.currentYear) {
          this.filterPostsByYear(year);
        }
      }
    }
  },
  mounted() {
    this.fetchPosts();
    if (this.$route.params.year) {
      const year = parseInt(this.$route.params.year);
      this.filterPostsByYear(year);
    }
  },
};
</script>

<style scoped>
</style>