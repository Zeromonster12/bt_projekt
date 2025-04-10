<template>
  <div class="create-post container mt-5">
    <h1 class="mb-4">Create New Post</h1>
    <form @submit.prevent="submitPost">
      <div class="form-group mb-3">
        <label for="title" class="form-label">Title</label>
        <input
          type="text"
          id="title"
          v-model="title"
          class="form-control"
          placeholder="Enter post title"
          required
        />
      </div>

      <div class="form-group mb-3">
        <label for="content" class="form-label">Content</label>
        <textarea
          id="content"
          v-model="content"
          class="form-control"
          rows="6"
          placeholder="Enter post content"
          required
        ></textarea>
      </div>

      <div class="form-group mb-3">
        <label for="image" class="form-label">Image URL</label>
        <input
          type="text"
          id="image"
          v-model="image"
          class="form-control"
          placeholder="Enter image URL (optional)"
        />
      </div>

      <button type="submit" class="btn btn-primary">Create Post</button>
    </form>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "CreatePostView",
  data() {
    return {
      title: "",
      content: "",
      image: "",
    };
  },
  methods: {
    async submitPost() {
      try {
        const response = await axios.post("http://127.0.0.1:8000/api/createPost", {
          title: this.title,
          body: this.content,
          image: this.image,
        });
        alert(response.data.message);
        this.$router.push("/");
      } catch (error) {
        console.error("Error creating post:", error);
        alert("Failed to create post. Please try again.");
      }
    },
  },
};
</script>

<style scoped>

</style>