<script>
import { useCounterStore } from './stores/counter'
import { useNotificationsStore } from './stores/notificationsStore'
import NotificationComponent from '@/components/NotificationComponent.vue'

export default {
  components: {
    NotificationComponent
  },
  data() {
    return {
      counter: useCounterStore(),
      notificationsStore: useNotificationsStore(),
      loading: false,
    }
  },
  async created() {
    if (localStorage.getItem('user') && !this.counter.isAuthenticated) {
      this.loading = true;
      try {
        await this.counter.checkAuth();
      } catch (error) {
        console.error('Failed to restore authentication:', error);
      } finally {
        this.loading = false;
      }
    }
  }
}
</script>

<template>
  <div v-if="loading" class="overlay">
    <div class="spinner-border text-primary" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
  </div>
  <NotificationComponent />
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

.overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
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
