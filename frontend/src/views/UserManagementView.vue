<template>
  <div>
    <NavBar />
    <div class="container mt-5">
      <div class="mb-3 d-flex justify-content-between">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
          <i class="bi bi-plus-circle"></i> Add New User
        </button>
        <div class="search-container" style="width: 300px;">
          <div class="input-group">
            <span class="input-group-text">
              <i class="bi bi-search"></i>
            </span>
            <input type="text" class="form-control" placeholder="Search by name..." v-model="searchQuery">
          </div>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-striped table-bordered" ref="userTable">
          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Role</th>
              <th>Years</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in filteredUsers" :key="user.id">
              <td>{{ user.id }}</td>
              <td>{{ user.name }}</td>
              <td>{{ user.email }}</td>
              <td>
                <span class="badge" :class="{
                  'bg-danger': user.role_id === 1,
                  'bg-success': user.role_id === 2,
                  'bg-info': user.role_id === 3
                }">
                  {{ user.role_id === 3 ? 'Anonym' : user.role_id === 2 ? 'Editor' : 'Admin' }}
                </span>
              </td>
              <td>
                <span>
                  <span v-for="year in user.years.slice(0, maxYearsToShow)" :key="year" class="badge bg-secondary me-1">
                    {{ year }}
                  </span>
                  <span v-if="user.years.length > maxYearsToShow" class="badge bg-secondary">...</span>
                </span>
              </td>
              <td>
                <button class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#editUserModal" @click="editUser(user)">
                  <i class="bi bi-pencil"></i> Edit
                </button>
                <button class="btn btn-sm btn-danger" @click="deleteUser(user.id)">
                  <i class="bi bi-trash"></i> Delete
                </button>
              </td>
            </tr>
            <tr v-if="filteredUsers.length === 0">
              <td colspan="6" class="text-center">No users found</td>
            </tr>
          </tbody>
        </table>
      </div>
      <!-- Edit User Modal - presunuť do componentu -->
      <div class="modal fade" id="editUserModal" ref="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <input type="text" class="form-control mb-2" v-model="selectedUser.name" placeholder="Name">
              <input type="email" class="form-control mb-2" v-model="selectedUser.email" placeholder="Email">
              <select class="form-select mb-2" v-model="selectedUser.role_id">
                <option :value="1">Admin - 1</option>
                <option :value="2">Editor - 2</option>
              </select>
              <div class="mb-2">
                <label class="form-label">Years</label>
                <select multiple class="form-select" v-model="selectedUser.years" size="5">
                  <option v-for="year in store.years" :key="year.id" :value="year.id">{{ year.year }}</option>
                </select>
              </div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button class="btn btn-primary" @click="saveUser">Save</button>
            </div>
          </div>
        </div>
      </div>
      <!-- End of Edit User Modal -->
      <!-- User add modal -->
      <div class="modal fade" id="addUserModal" ref="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <input type="text" class="form-control mb-2" v-model="newUser.name" placeholder="Name">
              <input type="email" class="form-control mb-2" v-model="newUser.email" placeholder="Email">
              <select class="form-select mb-2" v-model="newUser.role_id">
                <option :value="1">Admin - 1</option>
                <option :value="2">Editor - 2</option>
              </select>
            </div>
            <div class="modal-footer">
              <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button class="btn btn-primary" @click="createNewUser">Save</button>
            </div>
          </div>
        </div>
      </div>
      <!-- End of User add modal -->
    </div>
  </div>
</template>

<script>
import api from "@/api";
import NavBar from "@/components/NavBarComponent.vue";
import { useUserStore } from "@/stores/userStore";

export default {
  name: "UserManagementView",
  components: { NavBar },
  data() {
    return {
      store: useUserStore(),
      searchQuery: "",
      maxYearsToShow: 5,
      selectedUser: { id: null, name: "", email: "", role_id: null, years: null },
      newUser: { name: "", email: "", role_id: null, years: [] },
    };
  },
  computed: {
    filteredUsers() {
      return this.store.users.filter((user) => user.name.toLowerCase().includes(this.searchQuery.toLowerCase()));
    },
  },
  methods: {
    createNewUser() {
      if (this.newUser.name && this.newUser.email && this.newUser.role_id) {
        api.post('/createUser', this.newUser)
        .then(response => {
          this.store.users.push(response.data);
          this.store.fetchUsers();
        })
        .catch(error => {
          console.error("Error creating user:", error);
          alert("Failed to create user. Please try again.");
        });
        this.newUser = { name: "", email: "", role_id: null, years: [] };
      } else {
        alert("Please fill in all fields.");
      }
    },
    editUser(user) {
      this.selectedUser = {
        id: user.id,
        name: user.name,
        email: user.email,
        role_id: user.role_id,
        years: [...user.years],
      };
    },
    saveUser() {
      this.store.editedUsers = this.selectedUser;
      this.store.updateUsers();
    },
    deleteUser(userId) {
      const confirmed = confirm(`Are you sure you want to delete user ID ${userId}?`);
      if (confirmed) {
        this.store.users = this.store.users.filter((user) => user.id !== userId);
        this.store.deleteUser(userId);
      }
    },
    updateMaxYearsToShow() {
      const tableWidth = this.$refs.userTable.offsetWidth;
      this.maxYearsToShow = Math.floor((tableWidth - 300) / 50);
      if (this.maxYearsToShow < 1) this.maxYearsToShow = 1;
    },
  },
  mounted() {
    this.updateMaxYearsToShow();
    window.addEventListener("resize", this.updateMaxYearsToShow);
    this.store.fetchYears();
    this.store.fetchUsers();
  },
  beforeUnmount() {
    window.removeEventListener("resize", this.updateMaxYearsToShow);
  },
};
</script>