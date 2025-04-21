<template>
  <div class="create-post container mt-5">
    <h1 class="mb-4">Create New Post</h1>
    <form @submit.prevent="submitPost">
      <input
        v-model="title"
        type="text"
        class="form-control mb-3"
        placeholder="Title"
        required
      />

      <!-- WYSIWYG editor -->
      <WysiwygEditor v-model="content" />

      <input
        v-model="image"
        type="text"
        class="form-control mt-3"
        placeholder="Image URL"
      />

      <button class="btn btn-primary mt-3" :disabled="isSubmitting">
        <span v-if="isSubmitting">Creating...</span>
        <span v-else>Create Post</span>
      </button>
    </form>
  </div>
</template>

<script>
import api from "@/api";
import WysiwygEditor from "@/components/WysiwygEditor.vue";

export default {
  components: { WysiwygEditor },
  data() {
    return {
      title: "",
      content: "",
      image: "",
      isSubmitting: false,
    };
  },
  methods: {
    async submitPost() {
      if (!this.title || !this.content) {
        alert("Please fill in both title and content.");
        return;
      }

      this.isSubmitting = true;
      try {
        const res = await api.post("/createPost", {
          title: this.title,
          body: this.content,
          image: this.image,
        });

        alert(res.data.message);
        this.$router.push("/"); // Redirect to homepage after successful post creation
      } catch (err) {
        console.error(err);
        alert("Failed to create post.");
      } finally {
        this.isSubmitting = false;
      }
    },
  },
};
</script>

<style scoped>
.create-post {
  max-width: 600px;
  margin: 0 auto;
}

form input,
form button {
  width: 100%;
}

form button:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}
</style>
