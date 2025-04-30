<template>
  <NavBar />
  <div class="container mt-5">
    <div class="row">
      <div class="col-md-4">
        <div class="card">
          <div class="card-body text-center">
            <img src="https://via.placeholder.com/150" class="rounded-circle mb-3" alt="Profile Picture">
            <h3 class="card-title">{{ profile.name }}</h3>
            <p class="text-muted">{{ profile.role_id }}</p>
            <div class="d-grid gap-2">
              <button class="btn btn-primary" @click="editProfile">Edit Profile</button>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-8">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Profile Information</h4>
            <hr>
            <div class="row mb-3">
              <div class="col-sm-3">
                <h6 class="mb-0">Full Name</h6>
              </div>
              <div class="col-sm-9 text-secondary">
                {{ profile.name }}
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-sm-3">
                <h6 class="mb-0">Email</h6>
              </div>
              <div class="col-sm-9 text-secondary">
                {{ profile.email }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import api from "@/api";
import NavBar from "@/components/NavBarComponent.vue";
import axios from "axios";

export default {
  name: "ProfileView",
  components: {
    NavBar,
  },
  data() {
    return {
      profile: {
        name: null,
        role_id: null, // Môžete pridať rolu, ak je dostupná
        email: "",
      },
    };
  },
  created() {
    this.fetchProfile();
  },
  methods: {
    async fetchProfile() {
  // Debug: Výpis tokenu
  const token = sessionStorage.getItem('token');
  console.log('Token z localStorage:', token); // Debug
  
  try {
    const response = await api.get("/profile");
    this.profile = {
      name: response.data.name,
      email: response.data.email,
      role_id: response.data.role_id, // Pridajte rolu, ak je dostupná
    };
  } catch (error) {
    console.error('Detail chyby:', {
      status: error.response?.status,
      data: error.response?.data,
      headers: error.response?.headers
    });
    
    this.profile = {
      name: "Neprihlásený používateľ",
      email: "N/A"
    };
  }
},
    editProfile() {
      console.log("Edit profile clicked");
    },
  },
};
</script>

<style lang="css" scoped>
/* Pridajte vlastné štýly, ak je to potrebné */
</style>