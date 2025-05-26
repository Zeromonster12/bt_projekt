<template>
  <NavBar />
  <div class="container my-5">
    <div class="row g-4 align-items-stretch">
      <!-- Left Profile Summary -->
      <div class="col-md-4">
        <div class="card shadow-sm border-0 text-center p-5 rounded-4 h-100">
          <!-- Profilová ikona s hover efektom -->
          <label for="profilePictureUpload" class="profile-icon-label position-relative d-inline-block">
            <template v-if="counterStore.user?.pfp">
              <img
                :src="counterStore.user.pfp"
                alt="Profile Picture"
                class="rounded-circle mb-3 profile-img"
                style="width: 100px; height: 100px; object-fit: cover;"
              />
              <span class="edit-overlay d-flex justify-content-center align-items-center">
                <font-awesome-icon icon="pencil" size="2x" />
              </span>
            </template>
            <template v-else>
              <font-awesome-icon
                icon="user-circle"
                size="5x"
                class="mb-3 text-secondary profile-icon"
              />
              <span class="edit-overlay d-flex justify-content-center align-items-center">
                <font-awesome-icon icon="arrow-left" size="2x" />
              </span>
            </template>
          </label>
          <input
            type="file"
            id="profilePictureUpload"
            class="d-none"
            @change="uploadProfilePicture"
          />
          <h5 class="fw-semibold mb-1">{{ counterStore.user?.name || "Neprihlásený používateľ" }}</h5>
          <small class="text-muted mb-3 d-block">
            {{
              counterStore.user?.role_id === 1
                ? "Admin"
                : counterStore.user?.role_id === 2
                ? "Editor"
                : counterStore.user?.role_id === 3
                ? "Anonym"
                : "N/A"
            }}
          </small>
          <button
            class="btn btn-success rounded-4 mx-auto d-block mt-1 px-3"
            data-bs-toggle="modal"
            data-bs-target="#editProfileModal"
            @click="openEditProfileModal"
          >
            Edit Profile
          </button>
        </div>
      </div>
  
      <!-- Right Profile Info -->
      <div class="col-md-8">
        <div class="card shadow-sm border-0 p-5 rounded-4 h-100">
          <h5 class="fw-semibold mb-4 mt-2">Profile Information</h5>
          <div class="mb-3">
            <label class="form-label fw-bold mb-1">Full Name</label>
            <div class="text-muted">{{ counterStore.user?.name || "Neprihlásený používateľ" }}</div>
          </div>
          <div>
            <label class="form-label fw-bold mb-1">Email</label>
            <div class="text-muted">{{ counterStore.user?.email || "N/A" }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <button @click="test()">Test API</button>
  <button @click="test2()">Test API 2</button>
  <button @click="test3()">Test API 3</button>

  <!-- Modal -->
  <div
    class="modal fade"
    id="editProfileModal"
    tabindex="-1"
    aria-labelledby="editProfileModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog">
      <div class="modal-content border-0 shadow p-4 rounded-4">
        <div class="modal-header border-0">
          <h5 class="modal-title fw-semibold" id="editProfileModalLabel">Edit Profile</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" />
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
                placeholder="New password"
              />
              <small class="text-danger" v-if="passwordError">
                Password must be at least 8 characters.
              </small>
            </div>
          </form>
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-light rounded-4 px-3" data-bs-dismiss="modal">
            Cancel
          </button>
          <button
            type="button"
            class="btn btn-primary rounded-4 px-3"
            data-bs-dismiss="modal"
            @click="saveProfile"
          >         
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
    async uploadProfilePicture(event) {
  const file = event.target.files[0];
  if (!file) {
    alert("No file selected.");
    return;
  }

  const formData = new FormData();
  formData.append("file", file);

  try {
    await api.post("/upload-pfp", formData, {
      headers: {
        "Content-Type": "multipart/form-data",
      },
    });
    await this.counterStore.fetchUser(); // toto načíta nové údaje vrátane pfp
  } catch (error) {
    console.error("Error uploading profile picture:", error);
  }
},
    test() {
      api.get("/admin-route")
        .then((response) => {
          console.log("Response from admin route:", response.data);
        })
        .catch((error) => {
          console.error("Error accessing admin route:", error);
          alert("Access denied or error occurred.");
        });
    },
    test2() {
      api.get("/editor-route")
        .then((response) => {
          console.log("Response from editor route:", response.data);
        })
        .catch((error) => {
          console.error("Error accessing editor route:", error);
          alert("Access denied or error occurred.");
        });
    },
    test3() {
      api.get("/shared-route")
        .then((response) => {
          console.log("Response from user route:", response.data);
        })
        .catch((error) => {
          console.error("Error accessing user route:", error);
          alert("Access denied or error occurred.");
        });
    },
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

  } catch (error) {
    console.error("Error updating profile:", error);
    alert("Failed to update profile. Please try again.");
      }
    },
  },
};
</script>

<style scoped>
.profile-icon-label {
  cursor: pointer;
  position: relative;
  display: inline-block;
}
.profile-img {
  transition: filter 0.3s;
}
.profile-icon-label:hover .profile-img {
  filter: brightness(0.5);
}
.edit-overlay {
  pointer-events: none;
  position: absolute;
  top: 0;
  left: 35%;
  width: 100px;
  height: 100px;
  opacity: 0;
  transition: opacity 0.3s;
  color: #fff;
}
.profile-icon-label:hover .edit-overlay {
  opacity: 1;
}
</style>