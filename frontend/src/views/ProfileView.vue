<template>
  <NavBar />
  <div class="container mt-5">
    <div class="row">
      <div class="col-md-4">
        <div class="card">
          <div class="card-body text-center">
            <img src="https://via.placeholder.com/150" class="rounded-circle mb-3" alt="Profile Picture">
            <h3 class="card-title">John Doe</h3>
            <p class="text-muted">Web Developer</p>
            <div class="d-grid gap-2">
              <button class="btn btn-primary">Edit Profile</button>
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
                John Doe
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-sm-3">
                <h6 class="mb-0">Email</h6>
              </div>
              <div class="col-sm-9 text-secondary">
                john.doe@example.com
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-sm-3">
                <h6 class="mb-0">Phone</h6>
              </div>
              <div class="col-sm-9 text-secondary">
                (123) 456-7890
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-sm-3">
                <h6 class="mb-0">Address</h6>
              </div>
              <div class="col-sm-9 text-secondary">
                123 Main St, City, Country
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import NavBar from "@/components/NavBarComponent.vue";
export default {
  name: "ProfileView",
  components: {
    NavBar,
  },
  created() {},
  data() {
    return {
      profile: {
        name: "John Doe",
        role: "Web Developer",
        email: "john.doe@example.com",
        phone: "(123) 456-7890",
        address: "123 Main St, City, Country"
      }
    };
  },
  props: {},
  methods: {
    editProfile() {
      // Add edit profile functionality here
      console.log("Edit profile clicked");
    }
  },
};
</script>

<style lang="css" scoped>

</style>