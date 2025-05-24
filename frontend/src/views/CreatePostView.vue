<template>
  <Navbar />
  <div class="create-post container mt-5">
    <h1 class="mb-4">Create New Post</h1>
    <div>
      <div class="mb-3">
        <label for="yearSelect" class="form-label">Select Year</label>
        <select id="yearSelect" v-model="selectedYear" class="form-select">
          <option value="" disabled>Select a year</option>
          <option v-for="year in postStore.yearsWithId" :key="year.id" :value="year.id">
            {{ year.year }}
          </option>
        </select>
        <div class="invalid-feedback" :class="{ 'd-block': !selectedYear && showValidation }">
          Please select a year
        </div>
      </div>

      <input
        v-model="title"
        type="text"
        class="form-control mb-3"
        placeholder="Title"
        required
        :class="{ 'is-invalid': !title.trim() && showValidation }"
      />
      <div class="invalid-feedback" :class="{ 'd-block': !title.trim() && showValidation }">
        Please enter a title
      </div>

      <WysiwygEditor v-model="content" />
      <div class="invalid-feedback" :class="{ 'd-block': !content.trim() && showValidation }">
        Please enter content
      </div>

      <button class="btn btn-primary mt-3" :disabled="isSubmitting" @click="submitPost">
        <span v-if="isSubmitting">Creating...</span>
        <span v-else>Create Post</span>
      </button>
    </div>
  </div>
</template>

<script>
import api from "@/api";
import Navbar from "../components/NavBarComponent.vue";
import WysiwygEditor from "@/components/WysiwygEditor.vue";
import { usePostStore } from "@/stores/postStore";

export default {
  components: {
    WysiwygEditor,
    Navbar,
     },
  data() {
    return {
      title: "",
      content: "",
      selectedYear: "",
      isSubmitting: false,
      showValidation: false,
      postStore: usePostStore(),
    };
  },  methods: {
    async submitPost() {
      if (this.isSubmitting) return;

      // Show validation messages
      this.showValidation = true;

      if (!this.title.trim() || !this.content.trim() || !this.selectedYear) {
        return;
      }      this.isSubmitting = true;
      try {
        const res = await api.post("/createPost", {
          title: this.title,
          body: this.content,
          year_id: this.selectedYear
        });

        // Success notification
        alert(res.data.message || 'Post created successfully');
        
        // Refresh posts in store and navigate back to posts list
        this.postStore.fetchPosts();
        this.$router.push("/");
      } catch (err) {
        console.error(err);
        
        // Provide more specific error message if available from the API
        if (err.response && err.response.data && err.response.data.message) {
          alert(err.response.data.message);
        } else if (err.response && err.response.data && err.response.data.errors) {
          // Handle validation errors from Laravel
          const errorMessages = Object.values(err.response.data.errors).flat();
          alert(errorMessages.join('\n'));
        } else {
          alert("Failed to create post. Please try again.");
        }
      } finally {
        this.isSubmitting = false;
      }},
  },
  mounted() {
    this.postStore.fetchYearsWithId();
  }
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