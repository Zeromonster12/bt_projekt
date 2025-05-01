<template>
  <NavBar />
  <div class="container mt-5">
    <div class="row">
      <div class="col-md-4">
        <div class="card">
          <div class="card-body text-center">
            <img src="https://via.placeholder.com/150" class="rounded-circle mb-3" alt="Profile Picture">
            <h3 class="card-title">{{ counterStore.user?.name || "Neprihlásený používateľ" }}</h3>
            <p class="text-muted">
              {{
                counterStore.user?.role_id === 1
                  ? "Admin"
                  : counterStore.user?.role_id === 2
                  ? "Editor"
                  : counterStore.user?.role_id === 3
                  ? "Anonym"
                  : "N/A"
              }}
            </p>
            <div class="d-grid gap-2">
              <button
                class="btn btn-primary"
                data-bs-toggle="modal"
                data-bs-target="#editProfileModal"
                @click="openEditProfileModal"
              >
                Edit Profile
              </button>
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
                {{ counterStore.user?.name || "Neprihlásený používateľ" }}
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-sm-3">
                <h6 class="mb-0">Email</h6>
              </div>
              <div class="col-sm-9 text-secondary">
                {{ counterStore.user?.email || "N/A" }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Profile Modal -->
  <div
    class="modal fade"
    id="editProfileModal"
    tabindex="-1"
    aria-labelledby="editProfileModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form>
            <div class="mb-3">
              <label for="name" class="form-label">Name</label>
              <input
                type="text"
                id="name"
                class="form-control"
                v-model="selectedUser.name"
                placeholder="Enter your name"
              />
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input
                type="email"
                id="email"
                class="form-control"
                v-model="selectedUser.email"
                placeholder="Enter your email"
              />
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input
                type="password"
                id="password"
                class="form-control"
                v-model="selectedUser.password"
                placeholder="Enter a new password"
              />
              <small class="text-danger" v-if="passwordError">
                Password must be at least 8 characters long.
              </small>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Close
          </button>
          <button type="button" class="btn btn-primary" @click="saveProfile">
            Save Changes
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { useCounterStore } from "@/stores/counter";
import NavBar from "@/components/NavBarComponent.vue";
import api from "@/api"; 

export default {
  name: "ProfileView",
  components: {
    NavBar,
  },
  data() {
    return {
      counterStore: useCounterStore(),
      selectedUser: { name: "", email: "", password: "" },
      passwordError: false, 
    };
  },
  methods: {
    openEditProfileModal() {
      this.selectedUser = {
        name: this.counterStore.user?.name || "",
        email: this.counterStore.user?.email || "",
        password: "", 
      };
      this.passwordError = false; 
    },
    async saveProfile() {
      if (this.selectedUser.password && this.selectedUser.password.length < 8) {
        this.passwordError = true;
        return;
      }

      try {
        const response = await api.put("/profile", {
          name: this.selectedUser.name,
          email: this.selectedUser.email,
          password: this.selectedUser.password || undefined, 
        });

        this.counterStore.user.name = response.data.name;
        this.counterStore.user.email = response.data.email;

        alert("Profile updated successfully!");


      } catch (error) {
        console.error("Error updating profile:", error);
        alert("Failed to update profile. Please try again.");
      }
    },
  },
};
</script>