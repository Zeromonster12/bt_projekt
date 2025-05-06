import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import api from '@/api';

export const useCounterStore = defineStore('counter', {
  state: () => ({
    user: JSON.parse(localStorage.getItem('user')) || null,
    isAuthenticated: false, // Zmenené: začíname s false, overíme pomocou API
  }),

  actions: {
    async login(creds: { email: string, password: string, remember: boolean }) {
      try {
        const response = await api.post('/login', creds);
        
        // Uložíme len informácie o používateľovi do localStorage
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
        
        // Aktualizujeme localStorage
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
      // Aktívne overíme autentifikáciu volaním API
      try {
        await this.fetchUser();
        return true;
      } catch (error) {
        // Ak fetchUser zlyhá, nie sme autentifikovaní
        return false;
      }
    },
    
    async logout() {
      try {
        await api.post('/logout');
      } catch (error) {
        console.error('Logout error:', error);
      } finally {
        // Vždy vyčistíme localStorage a stav, aj keď server odpovie chybou
        localStorage.removeItem('user');
        this.user = null;
        this.isAuthenticated = false;
      }
    }
  },
  
  getters: {
    // Vráti rolu používateľa (môžete pridať podľa potreby)
    userRole: (state) => state.user ? state.user.role_id : null,
    userName: (state) => state.user ? state.user.name : null,
  }
});

