<script setup lang="ts">
import { RouterLink, RouterView } from 'vue-router'
</script>

<template>
  
  <RouterView />
  
</template>

<style scoped>
.app-container {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

.top-navbar {
  position: sticky;
  top: 0;
  z-index: 1000;
  width: 100%;
}

.content-wrapper {
  display: flex;
  flex: 1;
}

.left-sidebar {
  width: 250px;
  flex-shrink: 0;
  height: calc(100vh - 56px);
  overflow-y: auto;
  position: sticky;
  top: 56px;
}

.main-content {
  flex-grow: 1;
  padding: 1rem;
  overflow-y: auto;
}

@media (max-width: 768px) {
  .content-wrapper {
    flex-direction: column;
  }
  
  .left-sidebar {
    width: 100%;
    height: auto;
    position: static;
  }
}
</style>