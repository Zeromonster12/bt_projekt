<template>
  <NavBarComponent />
  <div class="search-results container mt-5">
    <h1 class="mb-4">Search Results</h1>
    <div v-if="loading" class="text-center">
      <p>Loading...</p>
    </div>
    <div v-else-if="results.length > 0">
      <div class="post-list">
        <router-link 
          v-for="post in results" 
          :key="post.id" 
          :to="'/' + post.id"
          class="post-item"
        >
          <h3>{{ post.title }}</h3>
          <p>{{ post.body.length > 150 ? post.body.substring(0, 150) + '...' : post.body }}</p>
        </router-link>
      </div>
    </div>
    <div v-else>
      <p>No results found for "{{ searchPhrase }}".</p>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import NavBarComponent from "@/components/NavBarComponent.vue";

export default {
  name: "SearchView",
  data() {
    return {
      searchPhrase: this.$route.query.phrase || "",
      results: [],
      loading: true,
    };
  },
  components: {
    NavBarComponent,
  },
  methods: {
    async fetchSearchResults() {
      try {
        const response = await axios.get("http://127.0.0.1:8000/api/searchPost", {
          params: { phrase: this.searchPhrase },
        });
        this.results = response.data;
      } catch (error) {
        console.error("Error fetching search results:", error);
        this.results = [];
      } finally {
        this.loading = false;
      }
    },
  },
  watch: {
    $route(to, from) {
      if (to.query.phrase !== from.query.phrase) {
        this.searchPhrase = to.query.phrase;
        this.fetchSearchResults();
      }
    },
  },
  mounted() {
    this.fetchSearchResults();
  },
};
</script>

<style scoped>
</style>