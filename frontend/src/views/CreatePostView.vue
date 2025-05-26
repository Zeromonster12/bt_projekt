<template>
  <Navbar />
  <div class="create-post container mt-5">
    <h1 class="mb-4">Create New Post</h1>
    <div>
      <div class="mb-3">
        <label for="yearSelect" class="form-label">Select Year</label>
        <select id="yearSelect" v-model="selectedYear" class="form-select">
          <option value="" disabled>Select a year</option>
          <option v-for="year in availableYears" :key="year.id" :value="year.id">
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
import { useCounterStore } from "@/stores/counter";
import { useNotificationsStore } from "@/stores/notificationsStore";

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
      counterStore: useCounterStore(),
      notificationsStore: useNotificationsStore()
    };
  },
  computed: {
    availableYears() {
      if (!this.postStore.yearsWithId) return [];
      
      if (this.counterStore.user?.role_id === 1) {
        return this.postStore.yearsWithId;
      }
      
      if (this.counterStore.user?.role_id === 2) {
        const userYears = this.counterStore.user.years || [];
        return this.postStore.yearsWithId.filter(yearObj => {
          return userYears.includes(yearObj.year.toString());
        });
      }
      
      return [];
    }
  },
  methods: {
    async submitPost() {
      if (this.isSubmitting) return;

      this.showValidation = true;

      if (!this.title.trim() || !this.content.trim() || !this.selectedYear) {
        this.notificationsStore.warning("Please fill all required fields");
        return;
      }
      
      this.isSubmitting = true;
      try {
        const res = await api.post("/createPost", {
          title: this.title,
          body: this.content,
          year_id: this.selectedYear
        });

        this.notificationsStore.success(res.data.message || 'Post created successfully');
        
        this.postStore.fetchPosts();
        this.$router.push("/postManagement");
      } catch (err) {
        console.error(err);
        
        if (err.response && err.response.data && err.response.data.message) {
          this.notificationsStore.error(err.response.data.message);
        } else if (err.response && err.response.data && err.response.data.errors) {
          const errorMessages = Object.values(err.response.data.errors).flat();
          this.notificationsStore.error(errorMessages.join(', '));
        } else {
          this.notificationsStore.error("Failed to create post. Please try again.");
        }
      } finally {
        this.isSubmitting = false;
      }
    },
  },  
  async mounted() {
    await this.postStore.fetchYearsWithId().catch(error => {
      console.error("Error loading years:", error);
      this.notificationsStore.error("Failed to load years. Please refresh the page.");
    });
    
    const queryYear = parseInt(this.$route.query.year);
    
    if (queryYear && this.availableYears.length > 0) {
      const yearObject = this.availableYears.find(y => parseInt(y.year) === queryYear);
      if (yearObject) {
        this.selectedYear = yearObject.id;
        return;
      }
    }
    
    if (this.postStore.currentYear && this.availableYears.length > 0) {
      const yearObject = this.availableYears.find(y => parseInt(y.year) === this.postStore.currentYear);
      if (yearObject) {
        this.selectedYear = yearObject.id;
      } else if (this.availableYears.length > 0) {
        this.selectedYear = this.availableYears[0].id;
      }
    }
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