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

      <div class="form-group mb-3">
        <label for="image">Image URL</label>
        <input
          type="text"
          id="image"
          v-model="image"
          class="form-control"
        />
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
    WysiwygEditor,
  },
  data() {
    return {
      postId: null,
      title: "",
      content: "",
      image: "",
    };
  },
  methods: {
    async fetchPost() {
      try {
        const res = await api.get(`/posts/${this.postId}`);
        const post = res.data;
        this.title = post.title;
        this.content = post.body; // alebo `post.content` podľa backendu
        this.image = post.image;
      } catch (error) {
        console.error("Error loading post:", error);
        alert("Failed to load post data.");
      }
    },
    async submitEdit() {
      try {
        await api.put(`/posts/${this.postId}`, {
          title: this.title,
          body: this.content,
          image: this.image,
        });
        alert("Post updated successfully!");
        this.$router.push("/");
      } catch (error) {
        console.error("Error updating post:", error);
        alert("Failed to update post.");
      }
    },
  },
  mounted() {
    this.postId = this.$route.params.id;
    this.fetchPost();
  },
};
</script>
