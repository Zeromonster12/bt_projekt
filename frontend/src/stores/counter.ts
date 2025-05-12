import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import api from '@/api';

export const useCounterStore = defineStore('counter', {
  state: () => ({
    user: JSON.parse(localStorage.getItem('user')) || null,
    isAuthenticated: !!localStorage.getItem('user'),
  }),

  actions: {
    async login(creds: { email: string, password: string, remember: boolean }) {
      try {
        const response = await api.post('/login', creds);
        
        if (response.data && response.data.user) {
          localStorage.setItem('user', JSON.stringify(response.data.user));
          this.user = response.data.user;
          this.isAuthenticated = true;
        }
        
        return true;
      } catch (error) {
        console.error('Login error:', error);
        return false;
      }
    },
    
    async fetchUser() {
      try {
        const response = await api.get('/user');
        this.user = response.data;
        this.isAuthenticated = true;
        
        localStorage.setItem('user', JSON.stringify(this.user));
        
        return this.user;
      } catch (error) {
        console.error('Failed to fetch user:', error);
        this.isAuthenticated = false;
        localStorage.removeItem('user');
        throw error;
      }
    },
    
    async checkAuth() {
      try {
        await this.fetchUser();
        return true;
      } catch (error) {
        return false;
      }
    },
    
    async logout() {
      try {
        await api.post('/logout');
      } catch (error) {
        console.error('Logout error:', error);
      } finally {
        localStorage.removeItem('user');
        this.user = null;
        this.isAuthenticated = false;
      }
    }
  },
  
  getters: {
    userRole: (state) => state.user ? state.user.role_id : null,
    userName: (state) => state.user ? state.user.name : null,
  }
});

