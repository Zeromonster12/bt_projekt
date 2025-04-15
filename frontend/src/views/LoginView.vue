<template>
  <main>
    <Navbar class="top-navbar" />
    <div class="container mt-5">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              <h3 class="text-center">Login</h3>
            </div>
            <div class="card-body">
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
                  <input type="checkbox" class="form-check-input" id="remember" v-model="remember">
                  <label class="form-check-label" for="remember">Remember me</label>
                </div>
                <div class="d-grid">
                  <button type="submit" class="btn btn-primary">Login</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<script>
import { useCounterStore } from '../stores/counter';
import Navbar from '../components/NavBarComponent.vue'

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
      },
    }
  },
  methods: {
    async login() {
      try {
        await this.counter.login(this.creds);
        if (this.counter.token) {
          this.$router.push('/');
        }else{
          alert('Zle prihlasovacie údaje');
        }
      } catch (error) {
        alert('error');
      }
    }
  },
}

</script>
