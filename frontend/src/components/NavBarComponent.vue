<template>
  <nav class="navbar navbar-expand-lg mx-0 my-0">
    <div class="container-fluid">
      <router-link class="navbar-brand" to="/">
        <img src="@/assets/logo.png" alt="Logo" height="80">
      </router-link>
      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarNav"
        aria-controls="navbarNav"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <router-link class="nav-link" to="/">Home</router-link>
          </li>
          <li class="nav-item">
            <router-link class="nav-link" to="/">About</router-link>
          </li>
          <li class="nav-item">
            <router-link class="nav-link" to="/">Contact</router-link>
          </li>
        </ul>
        <span>Používateľ: {{ counter.user?.name }}</span>
        <form class="d-flex me-2" @submit.prevent="searchPosts">
          <input
            class="form-control"
            type="search"
            placeholder="Search"
            aria-label="Search"
            v-model="searchPhrase"
          />
          <button class="btn btn-outline-success" type="submit">
            <font-awesome-icon icon="search" />
          </button>
        </form>
        <router-link v-if="!user" to="/login" class="btn btn-outline-primary">
          <font-awesome-icon icon="sign-in-alt" /> Login
        </router-link>
        <button v-if="user" class="btn btn-outline-danger ms-2" @click="logout">
          <font-awesome-icon icon="sign-out-alt" /> Logout
        </button>
      </div>
    </div>
  </nav>
</template>

<script>
import { useCounterStore } from '@/stores/counter';

export default {
  name: "NavBarComponent",
  data() {
    return {
      searchPhrase: "",
      counter: useCounterStore(),
    };
  },
  computed: {
    user() {
      return this.counter.user;
    },
  },
  methods: {
    searchPosts() {
      if (this.searchPhrase.trim() === "") {
        alert("Please enter a search phrase.");
        return;
      }
      this.$router.push({ name: "Search", query: { phrase: this.searchPhrase } });
    },
    logout() {
      try {
        this.counter.logout();
        this.$router.push('/login');
      } catch (error) {
        alert('Logout error: ', error);
      }
    },
  },
};
</script>