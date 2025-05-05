<template>
  <div class="edit-post container mt-5">
    <h1 class="mb-4">Edit Post</h1>
    <form @submit.prevent="updatePost">
      <div class="mb-3">
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

      <div class="mb-3">
        <label for="content" class="form-label">Content</label>
        <textarea
          id="content"
          v-model="content"
          class="form-control"
          rows="5"
          placeholder="Enter post content"
          required
        ></textarea>
      </div>

      <div class="d-flex justify-content-between">
        <button type="submit" class="btn btn-primary">Save</button>
        <button type="button" class="btn btn-secondary" @click="$router.go(-1)">Cancel</button>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  name: "EditPostView",
  data() {
    return {
      title: "",
      content: "",
    };
  },
  async created() {
    const postId = this.$route.params.postId;
    try {
      const response = await api.get(`/posts/${postId}`);
      this.title = response.data.title;
      this.content = response.data.body;
    } catch (error) {
      console.error("Error fetching post:", error);
    }
  },
  methods: {
    async updatePost() {
      const postId = this.$route.params.postId;
      try {
        await api.put(`/posts/${postId}`, {
          title: this.title,
          body: this.content,
        });
        alert("Post updated successfully!");
        this.$router.push("/postManagement");
      } catch (error) {
        console.error("Error updating post:", error);
        alert("Failed to update post. Please try again.");
      }
    },
  },
};
</script>

<style lang="scss" scoped></style>