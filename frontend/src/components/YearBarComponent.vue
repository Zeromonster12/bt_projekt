<template>
  <div class="year-bar">
    <div class="container">
      <div class="d-flex justify-content-center flex-wrap">
        <span 
          v-for="year in reversedYears" 
          :key="year" 
          class="year-item"
          :class="{ active: year === currentYear }"
        >
          {{ year }}
        </span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "YearBarComponent",
  data() {
    return {
      years: [2020, 2021, 2022, 2023, 2024, 2025],
      currentYear: new Date().getFullYear(),
    };
  },
  computed: {
    reversedYears() {
      return [...this.years].reverse();
    },
  },
};
</script>

<style scoped>
.year-bar {
  background-color: #f8f9fa;
  border-bottom: 1px solid #eaeaea;
  padding: 0.5rem 0;
  text-align: center;
}

.year-item {
  margin: 0 1rem;
  color: #6c757d;
  font-size: 0.9rem;
  cursor: pointer;
}

.year-item:hover {
  color: #fe761b;
}

.year-item.active {
  font-weight: bold;
  color: #fe761b;
}
</style>