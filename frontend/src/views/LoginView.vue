<template>
  <main>
    <Navbar class="top-navbar" />
    <div class="container mt-5">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="card p-5 shadow-sm border-0 rounded-5">
            <div class="card-header border-0 bg-transparent"> 
              <h3 class="text-center mb-0">Login</h3>
            </div>
            <div class="card-body p-5">
              <div v-if="errorMessage" class="alert alert-danger">
                {{ errorMessage }}
              </div>
              <form @submit.prevent="login">
                <div class="mb-3">
                  <label for="email" class="form-label">Email address</label>
                  <input type="email" class="form-control" id="email" v-model="creds.email" required>
                </div>
                <div class="mb-3">
                  <label for="password" class="form-label">Password</label>
                  <input type="password" class="form-control" id="password" v-model="creds.password" required>
                </div>
                <div class="mb-3 form-check">
                  <input type="checkbox" class="form-check-input" id="remember" v-model="creds.remember">
                  <label class="form-check-label" for="remember">Remember me</label>
                </div>
                <div class="d-grid">
                  <button type="submit" class="btn btn-primary rounded-4 px-3" :disabled="loading">
                    {{ loading ? 'Logging in...' : 'Login' }}
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div v-if="loading" class="overlay">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
  </main>
</template>

<script>
import { useCounterStore } from '../stores/counter';
import Navbar from '../components/NavBarComponent.vue';
import csrf from '@/csrf';

export default {
  components: {
    Navbar
  },

  data() {
    return {
      counter: useCounterStore(),
      creds: {
        email: '',
        password: '',
        remember: false,
      },
      loading: false,
      errorMessage: '',
    }
  },
  methods: {
    async login() {
      this.loading = true;
      this.errorMessage = '';
      
      try {
        // First, get a CSRF cookie
        await csrf.get('/sanctum/csrf-cookie');
        
        // Now try to login
        const success = await this.counter.login(this.creds);
        if (success) {
          this.$router.push('/');
        } else {
          this.errorMessage = 'Zlé prihlasovacie údaje';
        }
      } catch (error) {
        console.error('Login error:', error);
        this.errorMessage = error.response?.data?.message || 'Chyba pri prihlasovaní';
      } finally {
        this.loading = false;
      }
    }
  }
}
</script>

<style>
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
</style>

