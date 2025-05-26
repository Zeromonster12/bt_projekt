<template>
  <div class="edit-post container mt-5">
    <h1 class="mb-4">Edit Post</h1>

    <form @submit.prevent="submitEdit">
      <div class="form-group mb-3">
        <label for="title">Title</label>
        <input
          type="text"
          id="title"
          v-model="title"
          class="form-control"
          required
        />
      </div>

      <div class="form-group mb-3">
        <label>Content</label>
        <WysiwygEditor v-model="content" />
      </div>

      <button type="submit" class="btn btn-success">Save Changes</button>
    </form>
  </div>
</template>

<script>
import api from "@/api";
import WysiwygEditor from "@/components/WysiwygEditor.vue";

export default {
  name: "EditPostView",
  components: {
    WysiwygEditor
  },
  data() {
    return {
      title: "",
      content: "",
      image: "",
    };
  },  async created() {
    const postId = this.$route.params.id;
    try {
      const response = await api.get(`/post/${postId}`);
      this.title = response.data.title;
      this.content = response.data.body;
      this.image = response.data.image || "";
    } catch (error) {
      console.error("Error fetching post:", error);
    }
  },  methods: {
    async submitEdit() {
      const postId = this.$route.params.id;
      try {
        await api.put(`/posts/${postId}`, {
          title: this.title,
          body: this.content,
          image: this.image
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
