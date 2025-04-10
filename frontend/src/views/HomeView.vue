<template>
  <main>
    <div class="app-container">
      <Navbar class="top-navbar" />
      <YearBar class="year-bar" />
      <div class="content-wrapper">
        <Sidebar class="left-sidebar" />
        <main class="main-content">
          <router-view />
        </main>
      </div>
    </div>
    <footer class="footer">
      <Footer />
    </footer>
  </main>
</template>

<script>
import Navbar from "../components/NavBarComponent.vue";
import Sidebar from "../components/SideBarComponent.vue";
import Footer from "../components/FooterComponent.vue";
import YearBar from "../components/YearBarComponent.vue";

export default {
  name: "HomeView",
  components: {
    Navbar,
    Sidebar,
    Footer,
    YearBar,
  },
};
</script>

<style scoped>
</style>