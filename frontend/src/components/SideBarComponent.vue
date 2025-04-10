<template>
    <div class="sidebar border-end" id="sidebar-wrapper">
        <div class="sidebar-heading border-bottom bg-light text-center">Menu</div>
        <div class="list-group list-group-flush">
            <div class="p-3 text-center">
                <button class="btn btn-success" @click="createPost">
                    <font-awesome-icon icon="plus" /> Create Post
                </button>
            </div>
            <div
                class="list-group-item list-group-item-action list-group-item-light p-3"
                v-for="post in posts"
                :key="post.id"
                @click="goToPost(post.id)"
            >
                <span>{{ post.title }}</span>
                <button
                    class="btn btn-sm btn-outline-primary ms-2"
                    @click.stop="editPost(post.id)"
                >
                    <font-awesome-icon icon="edit" />
                </button>

                <button
                    class="btn btn-sm btn-outline-danger ms-2"
                    @click.stop="deletePost(post.id)"
                >
                    <font-awesome-icon icon="trash" />
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
  name: "SideBarComponent",
  data() {
    return {
      posts: [],
    };
  },
  methods: {
    async fetchPosts() {
      try {
        const response = await axios.get("http://127.0.0.1:8000/api/posts");
        this.posts = response.data;
      } catch (error) {
        console.error("Error fetching posts:", error);
      }
    },
    async deletePost(postId) {
      try {
        await axios.get(`http://127.0.0.1:8000/api/deletePost/${postId}`);
        this.posts = this.posts.filter((post) => post.id !== postId);
        alert("Post deleted successfully");
      } catch (error) {
        console.error("Error deleting post:", error);
      }
    },
    goToPost(postId) {
      this.$router.push(`/${postId}`);
    },
  },
  mounted() {
    this.fetchPosts();
  },
};
</script>

<style scoped>
.sidebar {
  min-height: calc(100vh - 56px);
}
</style>