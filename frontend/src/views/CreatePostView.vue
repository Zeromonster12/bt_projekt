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

      <WysiwygEditor v-model="content" />

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
      isSubmitting: false,
    };
  },
  methods: {
    async submitPost() {
      if (this.isSubmitting) return;

      if (!this.title.trim()) {
        alert("Please fill in the title.");
        return;
      }
      if (!this.content.trim()) {
        alert("Please fill in the content.");
        return;
      }

      this.isSubmitting = true;
      try {
        const res = await api.post("/createPost", {
          title: this.title,
          body: this.content,
        });

        alert(res.data.message);
        this.$router.push("/");
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
.editor-content {
  min-height: 300px;
  font-family: 'Roboto', sans-serif;
  font-size: 16px;
  line-height: 1.6;
  background-color: #ffffff;
  padding: 15px;
  border: 1px solid #ddd;
  border-radius: 8px;
  overflow-y: auto;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.toolbar {
  display: flex;
  gap: 8px;
  margin-bottom: 15px;
}

.toolbar button {
  font-size: 14px;
  padding: 8px 12px;
  border: 1px solid #ddd;
  border-radius: 5px;
  background-color: #f8f9fa;
  color: #272727;
  cursor: pointer;
  transition: background-color 0.3s ease, color 0.3s ease;
}

.toolbar button:hover {
  background-color: #fe761b;
  color: #ffffff;
}

.toolbar button:active {
  background-color: #e65c00;
  color: #ffffff;
}

.html-view {
  background-color: #f9f9f9;
  padding: 15px;
  border: 1px solid #ddd;
  border-radius: 8px;
  white-space: pre-wrap;
  font-family: 'Courier New', Courier, monospace;
  font-size: 14px;
  color: #333;
}

.editor-content::before {
  content: "Start typing here...";
  color: #aaa;
  font-style: italic;
  display: block;
  pointer-events: none;
  position: absolute;
  top: 15px;
  left: 15px;
}

.editor-content:focus::before {
  content: "";
}
</style>