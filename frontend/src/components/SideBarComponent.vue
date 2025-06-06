<template>
  <div class="sidebar border-end" id="sidebar-wrapper">
    <div class="sidebar-heading text-center">Menu</div>

    <div v-if="!currentYear" class="list-group list-group-flush">
      <div
        class="list-group-item list-group-item-action list-group-item-light p-3"
        v-for="year in postStore.years"
        :key="year"
        @click="goToYear(year)"
      >
        <span>{{ year }}</span>
      </div>
    </div>

    <div v-else class="list-group list-group-flush">
      <div
        class="list-group-item list-group-item-action list-group-item-light p-3"
        @click="$router.push('/')"
      >
        <font-awesome-icon icon="arrow-left" /> Back
      </div>
      <div v-if="canCreatePost" class="p-3">
        <button class="btn btn-success w-100" @click="createPost">
          <font-awesome-icon icon="plus" /> Create Post
        </button>
      </div>
      <div
        class="list-group-item list-group-item-action list-group-item-light p-3"
        v-for="post in postStore.filteredPosts"
        :key="post.id"
        :class="{ active: post.id === activePostId }"
        @click="goToPost(post.id)"
      >
        <span style="cursor: pointer;">{{ post.title }}</span>
      </div>
    </div>
  </div>
</template>

<script>
import { usePostStore } from "@/stores/postStore";
import { useCounterStore } from "@/stores/counter";

export default {
  name: "SideBarComponent",
  data() {
    return {
      postStore: usePostStore(),
      counterStore: useCounterStore(),
    };
  },
  computed: {
    isEditor() {
      return this.counterStore.user && [2, 1].includes(this.counterStore.user.role_id);
    },
    canCreatePost() {
      if (!this.counterStore.user) return false;
      
      if (this.counterStore.user.role_id === 1) return true;
      
      if (this.counterStore.user.role_id === 2 && this.currentYear) {
        const userYears = this.counterStore.user.years || [];
        return userYears.includes(this.currentYear.toString());
      }
      
      return false;
    },
    activePostId() {
      return parseInt(this.$route.params.postId);
    },
    currentYear() {
      return this.$route.params.year ? parseInt(this.$route.params.year) : null;
    },
  },
  watch: {
    $route: {
      immediate: true,
      handler() {
        if (!this.currentYear) {
          this.postStore.filteredPosts = [];
        } else {
          this.postStore.filterPostsByYear(this.currentYear);
        }
      },
    },
  },
  methods: {
    goToYear(year) {
      this.$router.push(`/${year}`);
    },
    goToPost(postId) {
      const year = this.currentYear || new Date().getFullYear();
      this.$router.push(`/${year}/post/${postId}`);
    },    createPost() {
      this.$router.push({
        path: "/createPost",
        query: { year: this.currentYear }
      });
    },

    async fetchYears() {
      if (!this.postStore.years.length) {
        await this.postStore.fetchYears();
      }
    },
  },
  mounted() {
    this.fetchYears();
  },
};
</script>

<style scoped>
.sidebar {
  width: 250px;
}

.list-group-item {
  cursor: pointer;
}

.list-group-item:hover {
  background-color: #f8f9fa;
}

.active {
  background-color: #007bff;
  color: white;
}
</style>