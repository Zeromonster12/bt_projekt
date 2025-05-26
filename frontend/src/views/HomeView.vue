<template>
  <main>
    <div class="app-container">
      <Navbar class="top-navbar" />
      <div class="content-wrapper">
        <Sidebar
          class="left-sidebar"
          :posts="postStore.filteredPosts"
          :currentYear="currentYear"
          @post-deleted="postStore.handlePostDeleted"
        />
        <main class="main-content">
          <router-view v-if="$route.params.postId" />
          <PostView v-else-if="newestPost" :key="'newest-' + newestPostId" />
          <div v-else class="p-4 text-center">
            <h2>Vyberte príspevok alebo si pozrite najnovšie príspevky</h2>
          </div>
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
import PostView from "../views/PostView.vue";
import { usePostStore } from "../stores/postStore";

export default {
  name: "HomeView",
  components: {
    Navbar,
    Sidebar,
    Footer,
    PostView
  },
  data() {
    return {
      postStore: usePostStore(),
      newestPost: null,
    };
  },
  computed: {
    currentYear() {
      return parseInt(this.$route.params.year) || new Date().getFullYear();
    },
    newestPostId() {
      return this.newestPost?.id;
    }
  },
  watch: {
    currentYear: {
      immediate: true,
      handler(newYear) {
        this.postStore.filterPostsByYear(newYear);
        this.findNewestPostForYear();
      },
    },
    'postStore.filteredPosts': {
      handler() {
        this.findNewestPostForYear();
      },
      deep: true
    }
  },
  methods: {
    findNewestPostForYear() {
      if (this.postStore.filteredPosts && this.postStore.filteredPosts.length > 0) {
        // Zotrieďte príspevky podľa dátumu - najnovší najprv
        const sortedPosts = [...this.postStore.filteredPosts].sort(
          (a, b) => new Date(b.created_at) - new Date(a.created_at)
        );
        
        this.newestPost = sortedPosts[0];
        // Nastavte najnovší príspevok ako vybraný príspevok v store,
        // aby ho PostView mohol zobraziť
        this.postStore.selectedPost = this.newestPost;
      } else {
        this.newestPost = null;
        this.postStore.selectedPost = null;
      }
    }
  },
  mounted() {
    this.postStore.fetchPosts().then(() => {
      this.findNewestPostForYear();
    });
  },
};
</script>