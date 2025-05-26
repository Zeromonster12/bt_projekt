<template>
  <transition name="fade">
    <div 
      v-if="notificationsStore.visible" 
      class="notification"
      :class="`notification-${notificationsStore.type}`"
    >
      {{ notificationsStore.message }}
      <div class="progress-container">
        <div class="progress-bar" :style="{ width: `${progress}%` }"></div>
      </div>
    </div>
  </transition>
</template>

<script>
import { useNotificationsStore } from '@/stores/notificationsStore';

export default {
  name: 'NotificationComponent',
  data() {
    return {
      notificationsStore: useNotificationsStore(),
      progress: 100,
      interval: null
    };
  },
  watch: {
    'notificationsStore.visible': {
      immediate: true,
      handler(newVal) {
        if (newVal) {
          this.startProgressBar();
        } else {
          this.stopProgressBar();
        }
      }
    }
  },
  methods: {
    startProgressBar() {
      this.stopProgressBar();
      this.progress = 100;
      
      const duration = this.notificationsStore.duration || 3000;
      const steps = 100;
      const interval = duration / steps;
      
      this.interval = setInterval(() => {
        this.progress -= 100 / steps;
        if (this.progress <= 0) {
          this.stopProgressBar();
        }
      }, interval);
    },
    stopProgressBar() {
      if (this.interval) {
        clearInterval(this.interval);
        this.interval = null;
      }
    }
  },
  beforeUnmount() {
    this.stopProgressBar();
  }
};
</script>

<style scoped>
.notification {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    padding: 15px 20px;
    border-radius: 20px;
    font-weight: 500;
    font-size: 14px;
    z-index: 9999;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    min-width: 200px;
    max-width: 400px;
    text-align: center;
}

.notification-success {
  background-color: #4caf50;
  color: #fff;
}

.notification-error {
  background-color: #f44336;
  color: #fff;
}

.notification-warning {
  background-color: #ff9800;
  color: #fff;
}

.notification-info {
  background-color: #2196f3;
  color: #fff;
}

.progress-container {
  position: absolute;
  bottom: 0;
  left: 50%;
  transform: translateX(-50%);
  height: 4px;
  background-color: rgba(255, 255, 255, 0.3);
  border-radius: 2px;
  overflow: hidden;
  width: 80%;
  box-sizing: border-box;
}

.progress-bar {
  height: 100%;
  background-color: rgba(255, 255, 255, 0.7);
  transition: width 0.1s linear;
  border-radius: 2px;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s, transform 0.3s;
}

.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>