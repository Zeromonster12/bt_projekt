import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import api from '@/api';

export const useCounterStore = defineStore('counter', {
  state: () => ({
    token: null,
    user: null,
    response: null,
  }),

  actions: {
    async login(creds: { email: string, password: string }) {
      try {
        const response = await api.post('/login', creds);
        this.token = response.data.token;
        api.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        sessionStorage.setItem('token', response.data.token)
        await this.fetchUser();
        return true;
      } catch (error) {
        alert(error);
      }
    },
    async fetchUser() {
      try {
        const response = await api.get('/user');
        this.user = response.data;
      } catch (error) {
        throw error;
      }
    },
    logout() {
      this.token = null;
      this.user = null;
      sessionStorage.removeItem('token')
      delete api.defaults.headers.common['Authorization'];
    }
  },
  getters: {
  }
});

