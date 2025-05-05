<template>
  <div>
    <NavBar />
    <div class="container mt-5">
      <div class="mb-3 d-flex justify-content-between">
        <div class="search-container" style="width: 300px;">
          <div class="input-group">
            <span class="input-group-text">
              <i class="bi bi-search"></i>
            </span>
            <input type="text" class="form-control" placeholder="Search by title..." v-model="searchQuery">
          </div>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-striped table-bordered">
          <thead class="table-dark">
            <tr>
              <th>Year</th>
              <th>ID</th>
              <th>Title</th>
              <th>Content</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="post in filteredPosts" :key="post.id">
              <td>{{ post.year }}</td>
              <td>{{ post.id }}</td>
              <td>{{ post.title }}</td>
              <td>{{ post.body }}</td>
              <td>
                <button class="btn btn-sm btn-primary me-1" @click="editPost(post.id)">
                  <i class="bi bi-pencil"></i> Edit
                </button>
                <button class="btn btn-sm btn-danger" @click="deletePost(post.id)">
                  <i class="bi bi-trash"></i> Delete
                </button>
              </td>
            </tr>
            <tr v-if="filteredPosts.length === 0">
              <td colspan="5" class="text-center">No posts found</td>
            </tr>
          </tbody>
        </table>
      </div>
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