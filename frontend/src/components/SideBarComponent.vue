<template>
  <div class="sidebar border-end" id="sidebar-wrapper">
    <div class="sidebar-heading border-bottom bg-light text-center">Menu</div>
    <div class="list-group list-group-flush">
      <div class="p-3 text-center">
        <button class="btn btn-success" @click="createPost" v-if="isLoggedIn">
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
        <span>{{ post.title }}</span>

        <button
          class="btn btn-sm btn-outline-primary ms-2"
          @click.stop="editPost(post.id)"
          v-if="isLoggedIn"
        >
          <font-awesome-icon icon="edit" />
        </button>

        <button
          class="btn btn-sm btn-outline-danger ms-2"
          @click.stop="deletePost(post.id)"
          v-if="isLoggedIn"
        >
          <font-awesome-icon icon="trash" />
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { usePostStore } from "@/stores/postStore";
import { useCounterStore } from "@/stores/counter";

export default {
  name: "SideBarComponent",
  computed: {
    postStore() {
      return usePostStore();
    },
    isLoggedIn() {
      const counterStore = useCounterStore();
      return !!counterStore.token;
    },
    activePostId() {
      return parseInt(this.$route.params.id);
    },
  },
  watch: {
    $route: {
      immediate: true,
      handler() {
        const year = parseInt(this.$route.params.year) || new Date().getFullYear();
        this.postStore.filterPostsByYear(year);
      },
    },
  },
  methods: {
    async deletePost(postId) {
      const result = await this.postStore.deletePost(postId);
      if (result.success) {
        alert(result.message);
      }
    },
    goToPost(postId) {
      this.$router.push(`/${this.postStore.currentYear}/post/${postId}`);
    },
    createPost() {
      this.$router.push("/createPost");
    },
    editPost(postId) {
      this.$router.push(`/editPost/${postId}`);
    },
  },
};
</script>

<style scoped>

</style>