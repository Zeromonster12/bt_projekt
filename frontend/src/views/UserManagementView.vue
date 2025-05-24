<template>
  <div>
    <NavBar />    <div class="container my-5">
      <div class="d-flex justify-content-between mb-3">
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

      <table class="table table-hover table-borderless align-middle text-center rounded-5" ref="userTable">
        <thead class="table-primary">
          <tr class="text-center">
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
                  <span v-for="year in user.years.slice(0, maxYearsToShow)" :key="year.id" class="badge bg-secondary me-1">
                    {{ year }}
                  </span>
                  <span v-if="user.years.length > maxYearsToShow" class="badge bg-secondary">...</span>
                </span>
              </td>              <td>                <button class="btn btn-sm btn-warning me-2 rounded-4 px-3" data-bs-toggle="modal" data-bs-target="#editUserModal" @click="editUser(user)">
                  <i class="bi bi-pencil"></i> Edit
                </button>
                <button class="btn btn-sm btn-danger rounded-4 px-3" @click="confirmDelete(user)">
                  <i class="bi bi-trash"></i> Delete
                </button>
              </td>
            </tr>
            <tr v-if="filteredUsers.length === 0">
              <td colspan="6" class="text-center text-muted">No users found</td>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="6" class="text-center rounded-bottom-5">    
              </td>
            </tr>
          </tfoot>
        </table>      <!-- Edit User Modal -->
      <div class="modal fade" id="editUserModal" ref="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content border-0 shadow p-4 rounded-4">
            <div class="modal-header border-0">
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
                <div class="selected-years mb-2 p-2 border rounded" style="min-height: 40px;">
                  <span v-if="selectedUser.years && selectedUser.years.length === 0" class="text-muted small">No years selected</span>
                  <span v-for="yearId in selectedUser.years" :key="yearId" class="badge bg-primary me-1 mb-1 d-inline-flex align-items-center">
                    <span>{{ getYearLabel(yearId) }}</span>
                    <span class="remove-year-btn ms-1" @click="removeYear(yearId)" title="Remove year">
                      <font-awesome-icon icon="times" />
                    </span>
                  </span>
                </div>
                <div class="available-years border rounded p-2" style="max-height: 150px; overflow-y: auto;">
                  <div class="mb-1 small text-muted">Click to add:</div>
                  <div class="d-flex flex-wrap gap-1">
                    <span v-for="year in store.years" 
                          :key="year.id" 
                          class="badge me-1 mb-1" 
                          :class="isYearSelected(year.id) ? 'bg-secondary' : 'bg-light text-dark'"
                          style="cursor: pointer;"
                          @click="toggleYear(year.id)">
                      {{ year.year }}
                    </span>
                  </div>
                  <div v-if="store.years.length === 0" class="text-muted small">No years available</div>
                </div>
              </div>
            </div>            <div class="modal-footer border-0">
              <button class="btn btn-secondary rounded-4 px-3" data-bs-dismiss="modal">Close</button>
              <button class="btn btn-primary rounded-4 px-3" @click="saveUser">Save</button>
            </div>
          </div>
        </div>
      </div>
      <!-- End of Edit User Modal -->      <!-- Add User Modal -->
      <div class="modal fade" id="addUserModal" ref="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content border-0 shadow p-4 rounded-4">
            <div class="modal-header border-0">
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
            </div>            <div class="modal-footer border-0">
              <button class="btn btn-secondary rounded-4 px-3" data-bs-dismiss="modal">Close</button>
              <button class="btn btn-primary rounded-4 px-3" @click="createNewUser">Save</button>
            </div>
          </div>
        </div>      </div>
      <!-- End of Add User Modal -->
      
      <!-- Delete User Modal -->
      <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content border-0 shadow p-4 rounded-4">
            <div class="modal-header border-0">
              <h5 class="modal-title" id="deleteUserModalLabel">Delete User</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              Are you sure you want to delete user <strong>{{ selectedUser.name }}</strong>?
              <p class="text-danger mt-3 mb-0"><small>This action cannot be undone.</small></p>
            </div>
            <div class="modal-footer border-0">
              <button class="btn btn-secondary rounded-4 px-3" data-bs-dismiss="modal">Cancel</button>
              <button class="btn btn-danger rounded-4 px-3" @click="deleteUser">Delete</button>
            </div>
          </div>
        </div>
      </div>
      <!-- End of Delete User Modal -->
    </div>
  </div>
</template>

<script>
import api from "@/api";
import NavBar from "@/components/NavBarComponent.vue";
import { useUserStore } from "@/stores/userStore";
import { Modal } from 'bootstrap';

export default {
  name: "UserManagementView",
  components: { NavBar },
  data() {
    return {
      store: useUserStore(),
      searchQuery: "",
      maxYearsToShow: 5,
      selectedUser: { id: null, name: "", email: "", role_id: null, years: [] },
      newUser: { name: "", email: "", role_id: null, years: [] },
    };
  },  computed: {
    filteredUsers() {
      return Array.isArray(this.store.users) 
        ? this.store.users.filter((user) => user.name.toLowerCase().includes(this.searchQuery.toLowerCase()))
        : [];
    },
  },
  methods: {
    getYearLabel(yearId) {
      const year = this.store.years.find(y => y.id === yearId);
      return year ? year.year : yearId;
    },
    isYearSelected(yearId) {
      return this.selectedUser.years.includes(yearId);
    },
    isNewYearSelected(yearId) {
      return this.newUser.years.includes(yearId);
    },
    toggleYear(yearId) {
      if (this.isYearSelected(yearId)) {
        this.selectedUser.years = this.selectedUser.years.filter(id => id !== yearId);
      } else {
        this.selectedUser.years.push(yearId);
      }
    },
    toggleNewYear(yearId) {
      if (this.isNewYearSelected(yearId)) {
        this.newUser.years = this.newUser.years.filter(id => id !== yearId);
      } else {
        this.newUser.years.push(yearId);
      }
    },
    removeYear(yearId) {
      this.selectedUser.years = this.selectedUser.years.filter(id => id !== yearId);
    },
    removeNewYear(yearId) {
      this.newUser.years = this.newUser.years.filter(id => id !== yearId);
    },
    async createNewUser() {
      if (this.newUser.name && this.newUser.email && this.newUser.role_id) {
        try {
          await this.store.createUser(this.newUser);
          this.newUser = { name: "", email: "", role_id: null, years: [] };
          this.closeModal('addUserModal');
        } catch (error) {
          alert("Failed to create user. Please try again.");
        }
      } else {
        alert("Please fill in all fields.");
      }
    },
    closeModal(modalId) {
      const closeBtn = document.querySelector(`#${modalId} .btn-close`);
      if (closeBtn) {
        closeBtn.click();
      }
    },
    editUser(user) {
      const yearIds = user.years.map(year => {
        if (typeof year === "object" && year !== null && "id" in year) {
          return year.id;
        } else {
          const yearObj = this.store.years.find(y => y.year == year);
          return yearObj ? yearObj.id : null;
        }
      }).filter(id => id !== null);

      this.selectedUser = {
        id: user.id,
        name: user.name,
        email: user.email,
        role_id: user.role_id,
        years: yearIds,
      };
    },
    saveUser() {
      const cleanUser = {
        ...this.selectedUser,
        years: this.selectedUser.years,
      };
      this.store.editedUsers = cleanUser;
      this.store.updateUsers()
        .then(() => {
          this.closeModal('editUserModal');
        })
        .catch(error => {
          alert('Failed to update user: ' + (error.response?.data?.error || 'Unknown error'));
        });
    },    confirmDelete(user) {
      this.selectedUser = { ...user };
<<<<<<< HEAD
      const deleteModalEl = document.getElementById('deleteUserModal');
      if (!deleteModalEl) return;
      
      const deleteModal = document.querySelector('[data-bs-target="#deleteUserModal"]');
      if (deleteModal) {
        deleteModal.click();
      } else {
        const tempButton = document.createElement('button');
        tempButton.setAttribute('data-bs-toggle', 'modal');
        tempButton.setAttribute('data-bs-target', '#deleteUserModal');
        document.body.appendChild(tempButton);
        tempButton.click();
        document.body.removeChild(tempButton);
=======
      const deleteModalEl = document.getElementById('deleteUserModal');
      if (deleteModalEl) {
        const deleteModal = new Modal(deleteModalEl);
        deleteModal.show();
>>>>>>> 06b55bb9a7818af2cdba9427d837f0c76f129b1d
      }
    },
      async deleteUser() {
      try {
        await this.store.deleteUser(this.selectedUser.id);
        this.closeModal('deleteUserModal');
      } catch (error) {
        console.error("Error deleting user:", error);
        alert("Failed to delete user. Please try again.");
      }
    },
  },  mounted() {
    this.store.fetchYears();
    this.store.fetchUsers();
  },
};
</script>

<style scoped>
.table th,
.table td {
  vertical-align: middle;
}
</style>