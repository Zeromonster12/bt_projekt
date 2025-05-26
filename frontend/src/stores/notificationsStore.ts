import { defineStore } from 'pinia';

export const useNotificationsStore = defineStore('notifications', {
  state: () => ({
    message: '',
    type: 'success', // 'success', 'error', 'warning', 'info'
    duration: 2000,
    visible: false,
    timeout: null,
  }),

  actions: {
    show(message, type = 'success', duration = 2000) {
      // Zrušiť predchádzajúci timeout ak existuje
      if (this.timeout) {
        clearTimeout(this.timeout);
        this.timeout = null;
      }

      // Nastaviť správu a typ
      this.message = message;
      this.type = type;
      this.duration = duration;
      
      // Zobraziť notifikáciu
      this.visible = true;
      
      // Nastaviť časovač na skrytie
      this.timeout = setTimeout(() => {
        this.visible = false;
      }, this.duration);
    },

    hide() {
      this.visible = false;
      if (this.timeout) {
        clearTimeout(this.timeout);
        this.timeout = null;
      }
    },

    success(message, duration = 2000) {
      this.show(message, 'success', duration);
    },

    error(message, duration = 2000) {
      this.show(message, 'error', duration);
    },

    warning(message, duration = 2000) {
      this.show(message, 'warning', duration);
    },

    info(message, duration = 2000) {
      this.show(message, 'info', duration);
    }
  }
});