<template>
  <transition name="fade">
    <div 
      v-if="notificationsStore.visible" 
      class="notification"
      :class="`notification-${notificationsStore.type}`"
    >
      {{ notificationsStore.message }}
    </div>
  </transition>
</template>

<script>
import { useNotificationsStore } from '@/stores/notificationsStore';

export default {
  name: 'NotificationComponent',
  data() {
    return {
      notificationsStore: useNotificationsStore()
    };
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

.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s, transform 0.3s;
}

.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>