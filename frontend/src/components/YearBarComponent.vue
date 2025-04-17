<template>
  <div class="year-bar">
    <div class="container">
      <div class="d-flex justify-content-center flex-wrap">
        <span 
          v-for="year in postStore.years" 
          :key="year" 
          class="year-item"
          :class="{ active: year === currentYear }"
          @click="postStore.selectYear(year, $router)"
        >
          {{ year }}
        </span>
      </div>
    </div>
  </div>
</template>

<script>
import { usePostStore } from "@/stores/postStore";

export default {
  name: "YearBarComponent",
  computed: {
    postStore() {
      return usePostStore();
    },
    currentYear() {
      return parseInt(this.$route.params.year) || new Date().getFullYear();
    },
  },
  watch: {
    '$route.params.year'(newYear) {
      this.$emit("year-selected", parseInt(newYear) || new Date().getFullYear());
    },
  },
  mounted() {
    this.postStore.fetchYears();
  },
};
</script>

<style scoped>
</style>