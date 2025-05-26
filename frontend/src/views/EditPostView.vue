<template>
    <div class="edit-post container mt-5">
    <h1 class="mb-4">Edit Post</h1>

    <form @submit.prevent="submitEdit">
      <div class="form-group mb-3">
        <label for="year">Year</label>
        <select
          id="year"
          v-model="selectedYear"
          class="form-control"
          required
        >
          <option v-for="year in availableYears" :key="year.id" :value="year.id">
            {{ year.year }}
          </option>
        </select>
      </div>

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
import { useCounterStore } from "@/stores/counter"; 

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
      years: [],
      selectedYear: null,
      counterStore: useCounterStore(), 
      post: null, 
    };
  },
  computed: {
    availableYears() {
      if (this.counterStore.user?.role_id === 1) {
        return this.years;
      }
      
      if (this.counterStore.user?.role_id === 2) {
        const userYears = this.counterStore.user.years || [];
        return this.years.filter(year => {
          return userYears.includes(year.year.toString());
        });
      }
      
      return [];
    }
  },  
  async created() {
    const postId = this.$route.params.id;
    try {
      const yearsResponse = await api.get("/years");
      this.years = yearsResponse.data;

      const response = await api.get(`/post/${postId}`);
      this.post = response.data; 
      this.title = response.data.title;
      this.content = response.data.body;
      this.image = response.data.image || "";
      
      if (this.counterStore.user?.role_id === 2) {
        const userYears = this.counterStore.user.years || [];
        const postYear = this.years.find(y => y.id === response.data.year_id)?.year;
        
        if (postYear && !userYears.includes(postYear.toString())) {
          alert("You don't have permission to edit posts from this year.");
          this.$router.push("/postManagement");
          return;
        }
      }
      
      this.selectedYear = response.data.year_id;
    } catch (error) {
      console.error("Error fetching post or years:", error);
      alert("Failed to load post. Redirecting to Post Management.");
      this.$router.push("/postManagement");
    }
  },
  methods: {
    async submitEdit() {
      if (this.counterStore.user?.role_id === 2) {
        const userYears = this.counterStore.user.years || [];
        const selectedYearValue = this.years.find(y => y.id === this.selectedYear)?.year;
        
        if (!userYears.includes(selectedYearValue?.toString())) {
          alert("You don't have permission to assign posts to this year.");
          return;
        }
      }
      
      const postId = this.$route.params.id;
      try {       
        const response = await api.put(`/posts/${postId}`, {
          title: this.title,
          body: this.content,
          image: this.image,
          year_id: this.selectedYear, 
        });
        alert("Post updated successfully!");
        this.$router.push("/postManagement");
      } catch (error) {
        console.error("Error updating post:", error);
        if (error.response) {
          console.error("Error details:", error.response.data);
        }
        alert("Failed to update post. Please try again.");
      }
    },
  },
};
</script>