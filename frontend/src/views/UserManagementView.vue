<template>
  <div>
    <NavBar />
    <div class="container mt-5">
      <div class="mb-3 d-flex justify-content-between">
        <button class="btn btn-primary" @click="addUser">
          <i class="bi bi-plus-circle"></i> Add New User
        </button>
        <div class="search-container" style="width: 300px;">
          <div class="input-group">
            <span class="input-group-text">
              <i class="bi bi-search"></i>
            </span>
            <input 
              type="text" 
              class="form-control" 
              placeholder="Search by name..." 
              v-model="searchQuery"
            >
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
                  'bg-danger': user.role === 'Admin',
                  'bg-success': user.role === 'Editor'
                }">{{ user.role }}</span>
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
                <button class="btn btn-sm btn-primary me-1" @click="editUser(user.id)">
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
    </div>
  </div>
</template>

<script>
import NavBar from "@/components/NavBarComponent.vue";

export default {
  name: "UserManagementView",
  components: {
    NavBar,
  },
  data() {
    return {
      users: [
        { id: 1, name: "John Doe", email: "john@example.com", role: "Admin", years: [2025, 2024, 2023, 2022, 2021, 2020] },
        { id: 2, name: "Jane Smith", email: "jane@example.com", role: "Editor", years: [2025, 2024, 2023] },
        { id: 3, name: "Alice Johnson", email: "alice@example.com", role: "Editor", years: [2025, 2024, 2022, 2020] },
        { id: 4, name: "Robert Williams", email: "robert@example.com", role: "Editor", years: [2023, 2022, 2021] },
        { id: 5, name: "Emily Brown", email: "emily@example.com", role: "Editor", years: [2024, 2023, 2022, 2021, 2020] },
        { id: 6, name: "Michael Davis", email: "michael@example.com", role: "Admin", years: [2025, 2024] },
      ],
      searchQuery: "",
      maxYearsToShow: 5,
    };
  },
  computed: {
    filteredUsers() {
      return this.users.filter((user) =>
        user.name.toLowerCase().includes(this.searchQuery.toLowerCase())
      );
    },
  },
  methods: {
    addUser() {
      alert("Add User functionality not implemented yet.");
    },
    editUser(userId) {
      alert(`Edit User functionality for user ID ${userId} not implemented yet.`);
    },
    deleteUser(userId) {
      const confirmed = confirm(`Are you sure you want to delete user ID ${userId}?`);
      if (confirmed) {
        this.users = this.users.filter((user) => user.id !== userId);
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
  },
  beforeUnmount() {
    window.removeEventListener("resize", this.updateMaxYearsToShow);
  },
};
</script>

<style>
.container {
  max-width: 80%;
}
</style>