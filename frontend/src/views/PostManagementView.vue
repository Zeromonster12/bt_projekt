<template>
  <div>
    <NavBar />
        <div class="container my-5">
         <div class="d-flex justify-content-between mb-3">
          <button class="btn btn-primary" @click="createPost">
            <i class="bi bi-plus-circle"></i> Create Post
          </button>
        <div class="search-container" style="width: 300px;">
          <div class="input-group">
            <span class="input-group-text">
              <i class="bi bi-search"></i>
            </span>
            <input type="text" class="form-control" placeholder="Search by title..." v-model="searchQuery">
          </div>
        </div>
      </div>
      
      <div class="filtering-options mb-3">
        <div class="row">
          <div class="col-md-4">
            <div class="input-group">
              <span class="input-group-text">Filter by Year</span>
              <select class="form-select" v-model="yearFilter">
                <option value="">All Years</option>
                <option v-for="year in visibleYears" :key="year" :value="year">{{ year }}</option>
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="input-group">
              <span class="input-group-text">Sort By</span>
              <select class="form-select" v-model="sortBy">
                <option value="created_desc">Newest First</option>
                <option value="created_asc">Oldest First</option>
                <option value="title_asc">Title (A-Z)</option>
                <option value="title_desc">Title (Z-A)</option>
              </select>
            </div>
          </div>
        </div>
      </div>      
      <table class="table table-hover table-borderless align-middle text-center rounded-5">
        <thead class="table-primary">
          <tr class="text-center">
            <th>Year</th>
            <th>Title</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="post in filteredPosts" :key="post.id">
            <td>{{ post.year }}</td>
            <td>{{ post.title }}</td>
            <td>
              <button class="btn btn-sm btn-warning me-2 rounded-4 px-3" @click="editPost(post.id)">
                <i class="bi bi-pencil"></i> Edit
              </button>
              <button class="btn btn-sm btn-danger rounded-4 px-3" @click="deletePost(post.id)">
                <i class="bi bi-trash"></i> Delete
              </button>
            </td>
          </tr>          <tr v-if="filteredPosts.length === 0">
            <td colspan="3" class="text-center text-muted">No posts found</td>
          </tr>
        </tbody>        <tfoot>
          <tr>
            <td colspan="3" class="text-center rounded-bottom-5">    
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</template>

<script>
import NavBar from "@/components/NavBarComponent.vue";
import { usePostStore } from "@/stores/postStore";
import { useCounterStore } from "@/stores/counter";
import api from "@/api"; 

export default {
  name: "PostManagementView",
  components: { NavBar },
  data() {
    return {
      postStore: usePostStore(),
      userStore: useCounterStore(),
      searchQuery: "",
      yearFilter: "",
      sortBy: "created_desc",
    };
  },
  computed: {
    availableYears() {
      const yearsSet = new Set(this.postStore.posts.map(post => post.year));
      return Array.from(yearsSet).sort((a, b) => parseInt(b) - parseInt(a));
    },
    
    visibleYears() {
      if (this.userStore.userRole === 1) {
        return this.availableYears;
      }
      
      if (this.userStore.userRole === 2) {
        const userYears = this.userStore.user.years || [];
        return this.availableYears.filter(year => userYears.includes(year));
      }
      
      return [];
    },
    
    filteredPosts() {
      let result = this.postStore.posts.filter((post) =>
        post.title.toLowerCase().includes(this.searchQuery.toLowerCase())
      );
      
      if (this.yearFilter) {
        result = result.filter(post => post.year === this.yearFilter);
      } else if (this.userStore.userRole === 2) {
        const userYears = this.userStore.user.years || [];
        result = result.filter(post => userYears.includes(post.year));
      }
      
      return this.sortPosts(result);
    },
  },
   methods: {
    createPost() {
      this.$router.push({
        path: "/createPost",
        query: { year: this.postStore.currentYear }
      });
    },
    editPost(postId) {
      this.$router.push(`/editPost/${postId}`);
    },    sortPosts(posts) {
      const sortedPosts = [...posts];
      
      const parseDate = (dateStr) => {
        if (!dateStr) return null;
        let date = new Date(dateStr);
        if (!isNaN(date.getTime())) return date;
        
        const regex = /(\d{4})-(\d{2})-(\d{2})[T ](\d{2}):(\d{2}):(\d{2})/;
        const parts = regex.exec(dateStr);
        if (parts) {
          return new Date(
            parseInt(parts[1]), 
            parseInt(parts[2]) - 1, 
            parseInt(parts[3]), 
            parseInt(parts[4]), 
            parseInt(parts[5]), 
            parseInt(parts[6])
          );
        }
        return null;
      };
      
      const compareDates = (a, b, ascending = true) => {
        const dateA = parseDate(a.created_at);
        const dateB = parseDate(b.created_at);
        
        if (!dateA && !dateB) return 0;
        if (!dateA) return ascending ? 1 : -1;
        if (!dateB) return ascending ? -1 : 1;
        
        return ascending 
          ? dateA.getTime() - dateB.getTime() 
          : dateB.getTime() - dateA.getTime();
      };
      
      switch (this.sortBy) {
        case 'created_desc':
          return sortedPosts.sort((a, b) => compareDates(a, b, false));
        case 'created_asc':
          return sortedPosts.sort((a, b) => compareDates(a, b, true));
        case 'title_asc':
          return sortedPosts.sort((a, b) => a.title.localeCompare(b.title));
        case 'title_desc':
          return sortedPosts.sort((a, b) => b.title.localeCompare(a.title));
        default:
          return sortedPosts;
      }
    },
    async deletePost(postId) {
      try {
        const confirmed = confirm("Are you sure you want to delete this post?");
        if (!confirmed) return;

        await api.get(`/deletePost/${postId}`);
        this.postStore.posts = this.postStore.posts.filter((post) => post.id !== postId);

        alert("Post deleted successfully!");
      } catch (error) {
        console.error("Error deleting post:", error);
        alert("Failed to delete post. Please try again.");
      }
    },
  },  mounted() {
    this.postStore.fetchPosts().then(() => {
      if (this.postStore.posts.length > 0) {
        const sample = this.postStore.posts[0];
        if (sample.created_at) {
        }
      }
    });
  },
};
</script>

<style scoped>
.table th,
.table td {
  vertical-align: middle;
}

.filtering-options {
  background-color: #f8f9fa;
  padding: 15px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.filtering-options .input-group {
  margin-bottom: 0;
}
</style>