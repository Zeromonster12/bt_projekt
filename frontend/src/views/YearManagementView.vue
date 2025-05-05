<template>
  <div>
    <NavBar />
    <div class="container my-5">
      <!-- Add Year Button -->
      <button
        class="btn btn-primary mb-3"
        data-bs-toggle="modal"
        data-bs-target="#addYearModal"
      >
        <i class="bi bi-plus-circle"></i> Add Year
      </button>

      <!-- Year List Table -->
      <table class="table table-hover table-borderless align-middle text-center rounded-5 ">
        <thead class="table-primary">
          <tr class="text-center">
            <th>#</th>
            <th>Year</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(year, index) in this.postStore.yearsWithId" :key="year.id">
            <td>{{ index + 1 }}</td>
            <td>{{ year.year }}</td>
            <td>
              <button
                class="btn btn-sm btn-warning me-2 rounded-4 px-3"
                data-bs-toggle="modal"
                data-bs-target="#editYearModal"
                @click="editYear(year)"
              >
                <i class="bi bi-pencil"></i> Edit
              </button>
              <button
                class="btn btn-sm btn-danger rounded-4 px-3"
                data-bs-toggle="modal"
                data-bs-target="#deleteYearModal"
                @click="confirmDelete(year)"
              >
                <i class="bi bi-trash"></i> Delete
              </button>
            </td>
          </tr>
          <tr v-if="this.postStore.yearsWithId.length === 0">
            <td colspan="3" class="text-center text-muted">No years found</td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="3" class="text-center rounded-bottom-5">    
            </td>
          </tr>
        </tfoot>
      </table>
    </div>

    <!-- Add Year Modal -->
    <div
      class="modal fade"
      id="addYearModal"
      tabindex="-1"
      aria-labelledby="addYearModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content border-0 shadow p-4 rounded-4">
          <div class="modal-header border-0">
            <h5 class="modal-title" id="addYearModalLabel">Add Year</h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            <form>
              <div class="mb-3">
                <label for="yearInput" class="form-label">Year</label>
                <input
                  type="number"
                  id="yearInput"
                  class="form-control"
                  v-model="newYear"
                  placeholder="Enter year"
                />
              </div>
            </form>
          </div>
          <div class="modal-footer border-0">
            <button
              type="button"
              class="btn btn-secondary rounded-4 px-3"
              data-bs-dismiss="modal"
            >
              Close
            </button>
            <button
              type="button"
              class="btn btn-primary rounded-4 px-3"
              @click="addYear"
              data-bs-dismiss="modal"
            >
              Save
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Edit Year Modal -->
    <div
      class="modal fade"
      id="editYearModal"
      tabindex="-1"
      aria-labelledby="editYearModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content border-0 shadow p-4 rounded-4">
          <div class="modal-header border-0">
            <h5 class="modal-title" id="editYearModalLabel">Edit Year</h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            <form>
              <div class="mb-3">
                <label for="editYearInput" class="form-label">Year</label>
                <input
                  type="number"
                  id="editYearInput"
                  class="form-control"
                  v-model="selectedYear.year"
                  placeholder="Enter year"
                />
              </div>
            </form>
          </div>
          <div class="modal-footer border-0">
            <button
              type="button"
              class="btn btn-secondary rounded-4 px-3"
              data-bs-dismiss="modal"
            >
              Close
            </button>
            <button
              type="button"
              class="btn btn-primary rounded-4 px-3"
              @click="updateYear"
              data-bs-dismiss="modal"
            >
              Save Changes
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Delete Year Modal -->
    <div
      class="modal fade"
      id="deleteYearModal"
      tabindex="-1"
      aria-labelledby="deleteYearModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content border-0 shadow p-4 rounded-4">
          <div class="modal-header border-0">
            <h5 class="modal-title" id="deleteYearModalLabel">Delete Year</h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            Are you sure you want to delete the year <strong>{{ selectedYear.year }}</strong>?
          </div>
          <div class="modal-footer border-0">
            <button
              type="button"
              class="btn btn-secondary rounded-4 px-3"
              data-bs-dismiss="modal"
            >
              Cancel
            </button>
            <button
              type="button"
              class="btn btn-danger rounded-4 px-3"
              @click="deleteYear"
              data-bs-dismiss="modal"
            >
              Delete
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import NavBar from "@/components/NavBarComponent.vue";
import { usePostStore } from "@/stores/postStore";
import api from "@/api";
import { useCounterStore } from "@/stores/counter";

export default {
  name: "YearManagementView",
  components: { NavBar },
  data() {
    return {
      newYear: "",
      selectedYear: "",
      counterStore: useCounterStore(),
    };
  },
    computed: {
    postStore() {
      return usePostStore();
    },
  },

  methods: {
    async fetchYears() {
      try {
        await this.postStore.fetchYearsWithId();
      } catch (error) {
        console.error("Error fetching years:", error);
      }
    },

    async addYear() {
      try {
        if (this.newYear) {
          await api.post("/years", {
            year: this.newYear,
            user_id: this.counterStore.user.id,
          });
          this.newYear = "";
          this.fetchYears();
        }
      } catch (error) {
        if (error.response && error.response.status === 400) {
          alert(error.response.data.message);
        } else {
          console.error("Error adding year:", error);
          alert("Nastala chyba pri pridávaní ročníka.");
        }
      }
    },

    editYear(year) {
      this.selectedYear = { ...year };
    },

    async updateYear() {
      try {
        if (this.selectedYear.id) {
          await api.put(`/years/${this.selectedYear.id}`, {
            year: this.selectedYear.year,
          });
          this.fetchYears();
        }
      } catch (error) {
        if (error.response && error.response.status === 400) {
          alert(error.response.data.message);
        } else {
          console.error("Error updating year:", error);
          alert("Nastala chyba pri aktualizácii ročníka.");
        }
      }
    },

    confirmDelete(year) {
      this.selectedYear = { ...year };
    },

    async deleteYear() {
      try {
        if (this.selectedYear.id) {
          await api.delete(`/years/${this.selectedYear.id}`);
          this.fetchYears();
        } else {
          console.error("No selected year to delete.");
        }
      } catch (error) {
        console.error("Error deleting year:", error);
      }
    },
  },
  mounted() {
    this.fetchYears();
  },
};
</script>

<style scoped>
.table th,
.table td {
  vertical-align: middle;
}
</style>