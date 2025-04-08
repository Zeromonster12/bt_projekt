<script setup lang="ts">
import Navbar from '../components/NavBarComponent.vue'
import Sidebar from '../components/SideBarComponent.vue'
import Footer from '../components/FooterComponent.vue'
</script>

<template>
  <main>
      <div class="app-container">
      <Navbar class="top-navbar" />
      
      <div class="content-wrapper">
        <Sidebar class="left-sidebar" />
        <main class="main-content">
          <RouterView />
        </main>
      </div>
    </div>

    <footer class="footer">
      <Footer />
    </footer>
  </main>
</template>
