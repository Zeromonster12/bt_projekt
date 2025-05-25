<template>
  <nav class="navbar navbar-expand-lg mx-0 my-0 border-0 px-3">
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
            <router-link class="nav-link px-4" to="/">Home</router-link>
          </li>
          <li class="nav-item">
            <router-link class="nav-link px-4" to="/">About</router-link>
          </li>
          <li class="nav-item">
            <router-link class="nav-link px-4" to="/">Contact</router-link>
          </li>
        </ul>
        <form class="d-flex me-3" @submit.prevent="searchPosts">
          <input
            class="form-control
            me-0 rounded-end-0 rounded-start-5"
            type="search"
            placeholder="Search"
            aria-label="Search"
            v-model="searchPhrase"
          />
            <button class="btn btn-outline-primary rounded-end-5" type="submit">
            <font-awesome-icon icon="search" />
          </button>
        </form>

        <div v-if="user" class="dropdown">
          <div
            class="profile-circle"
            id="profileDropdown"
            data-bs-toggle="dropdown"
            aria-expanded="false"
          >
            <font-awesome-icon icon="user-circle" size="2x" />
          </div>
          <ul class="dropdown-menu dropdown-menu-end p-4 border-1 rounded-4" aria-labelledby="profileDropdown">
            <li>
              <div class="dropdown-item user-profile-header py-2 mb-2">
                <span class="fw-bold">{{ user.name }}</span>
              </div>
            </li>
            <li>
              <router-link class="dropdown-item py-2" to="/profile">Profile Settings</router-link>
            </li>
            <li>
              <router-link class="dropdown-item py-2" to="/user-management">User Management</router-link>
            </li>
            <li>
              <router-link class="dropdown-item py-2" to="/year-management">Year Management</router-link>
            </li>
            <li>
              <router-link class="dropdown-item py-2" to="/post-management">Post Management</router-link>
            </li>
            <li>
                <button class="dropdown-item logout-btn mt-3 py-2" @click="logout">
                <font-awesome-icon icon="sign-out-alt" /> Logout
                </button>
            </li>
          </ul>
        </div>
        <router-link v-else to="/login" class="btn btn-primary rounded-4 px-3">
          <font-awesome-icon icon="sign-in-alt" /> Login
        </router-link>
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
    async logout() {
      try {
        await this.counter.logout();
        window.location.href = '/login';
      } catch (error) {
        alert('Logout error: ', error);
      }
    },
  },
};
</script>

<style scoped>
.profile-circle {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background-color: #f0f0f0;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  position: relative;
}

.profile-circle:hover {
  background-color: #e0e0e0;
}

.dropdown-menu {
  min-width: 200px;
}
</style>