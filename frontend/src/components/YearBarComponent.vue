<template>
  <div class="year-bar">
    <div class="container">
      <div class="d-flex justify-content-center flex-wrap">
        <span 
          v-for="year in years" 
          :key="year" 
          class="year-item"
          :class="{ active: year === currentYear }"
          @click="selectYear(year)"
        >
          {{ year }}
        </span>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "YearBarComponent",
  data() {
    return {
      years: [],
    };
  },
  computed: {
    currentYear() {
      return parseInt(this.$route.params.year) || new Date().getFullYear();
    },
  },
  methods: {
    async fetchYears() {
      try {
        const response = await axios.get("http://127.0.0.1:8000/api/years");
        this.years = response.data
          .map((item) => parseInt(item.year))
          .sort((a, b) => b - a);
      } catch (error) {
        console.error("Error fetching years:", error);
      }
    },
    async selectYear(year) {
      try {
        const response = await axios.get("http://127.0.0.1:8000/api/posts");
        const posts = response.data;
        const filteredPosts = posts.filter((post) => post.year === year);

        if (filteredPosts.length > 0) {
          this.$router.push(`/${year}/post/${filteredPosts[0].id}`);
        } else {
          alert(`No posts available for the year ${year}`);
        }
      } catch (error) {
        console.error("Error fetching posts for the selected year:", error);
      }
    },
  },
  watch: {
    '$route.params.year'(newYear) {
      this.$emit("year-selected", parseInt(newYear) || new Date().getFullYear());
    },
  },
  mounted() {
    this.fetchYears();
  },
};
</script>

<style scoped>
</style>