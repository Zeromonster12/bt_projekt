<template>
  <div>
    <NavBar />    <div class="container my-5">
      <div class="d-flex justify-content-between mb-3">
        <button class="btn btn-primary" @click="$router.push('/createPost')">
          <i class="bi bi-plus-circle"></i> Create Post
        </button>
        <div class="search-container" style="width: 300px;">
          <div class="input-group">
            <span class="input-group-text">
              <i class="bi bi-search"></i>
            </span>
            <input type="text" class="form-control" placeholder="Search by title..." v-model="searchQuery">
          </div>
        </div>
      </div>      <table class="table table-hover table-borderless align-middle text-center rounded-5">
        <thead class="table-primary">
          <tr class="text-center">
            <th>Year</th>
            <th>Title</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="post in filteredPosts" :key="post.id">
            <td>{{ post.year }}</td>
            <td>{{ post.title }}</td>
            <td>
              <button class="btn btn-sm btn-warning me-2 rounded-4 px-3" @click="editPost(post.id)">
                <i class="bi bi-pencil"></i> Edit
              </button>
              <button class="btn btn-sm btn-danger rounded-4 px-3" @click="deletePost(post.id)">
                <i class="bi bi-trash"></i> Delete
              </button>
            </td>
          </tr>          <tr v-if="filteredPosts.length === 0">
            <td colspan="3" class="text-center text-muted">No posts found</td>
          </tr>
        </tbody>        <tfoot>
          <tr>
            <td colspan="3" class="text-center rounded-bottom-5">    
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</template>

<script>
import NavBar from "@/components/NavBarComponent.vue";
import { usePostStore } from "@/stores/postStore";
import api from "@/api"; 

export default {
  name: "PostManagementView",
  components: { NavBar },
  data() {
    return {
      postStore: usePostStore(),
      searchQuery: "",
    };
  },
  computed: {
    filteredPosts() {
      return this.postStore.posts.filter((post) =>
        post.title.toLowerCase().includes(this.searchQuery.toLowerCase())
      );
    },
  },
  methods: {
    editPost(postId) {
      this.$router.push(`/editPost/${postId}`);
    },
    async deletePost(postId) {
      try {
        const confirmed = confirm("Are you sure you want to delete this post?");
        if (!confirmed) return;

        await api.get(`/deletePost/${postId}`);
        this.postStore.posts = this.postStore.posts.filter((post) => post.id !== postId);

        alert("Post deleted successfully!");
      } catch (error) {
        console.error("Error deleting post:", error);
        alert("Failed to delete post. Please try again.");
      }
    },
  },
  mounted() {
    this.postStore.fetchPosts();
  },
};
</script>

<style scoped>
.table th,
.table td {
  vertical-align: middle;
}
</style>