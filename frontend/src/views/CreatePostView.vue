<template>
  <div class="create-post container mt-5">
    <h1 class="mb-4">Create New Post</h1>
    <div>
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

      <button class="btn btn-primary mt-3" :disabled="isSubmitting" @click="submitPost">
        <span v-if="isSubmitting">Creating...</span>
        <span v-else>Create Post</span>
      </button>
    </div>
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
      isSubmitting: false, // Used to disable the button temporarily
    };
  },
  methods: {
    async submitPost() {
      if (this.isSubmitting) return; // Prevent multiple clicks

      // Validate title and content
      if (!this.title.trim()) {
        alert("Please fill in the title.");
        return;
      }
      if (!this.content.trim()) {
        alert("Please fill in the content.");
        return;
      }

      this.isSubmitting = true; // Disable the button
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
        this.isSubmitting = false; // Re-enable the button
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