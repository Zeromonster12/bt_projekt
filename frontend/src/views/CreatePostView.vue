<template>
  <NavBar />
  <div class="create-post container mt-5">
    <h1 class="mb-4">Create New Post ROLE: {{ user?.role_id }}</h1>
    <form @submit.prevent="submitPost">
      <div class="form-group mb-3">
        <label for="title" class="form-label"></label>
        <input
          type="text"
          id="title"
          v-model="title"
          class="form-control"
          placeholder="Enter post title"
          required
        />
      </div>

      <div class="form-group mb-3">
        <label for="content" class="form-label">Content</label>
        <textarea
          id="content"
          v-model="content"
          class="form-control"
          rows="6"
          placeholder="Enter post content"
          required
        ></textarea>
      </div>

      <div class="form-group mb-3">
        <label for="image" class="form-label">Image URL</label>
        <input
          type="text"
          id="image"
          v-model="image"
          class="form-control"
          placeholder="Enter image URL (optional)"
        />
      </div>

      <button type="submit" class="btn btn-primary">Create Post</button>
    </form>
  </div>
</template>

<script>
import api from "@/api";
import { useCounterStore } from "@/stores/counter";
import NavBar from "@/components/NavBarComponent.vue";

export default {
  name: "CreatePostView",
  components: {
    NavBar,
  },
  data() {
    return {
      title: "",
      content: "",
      image: "",
      store: useCounterStore(),
      user: null,
    };
  },
  methods: {
    async submitPost() {
      try {
        const response = await api.post("/createPost", {
          title: this.title,
          body: this.content,
          image: this.image,
          user_id: this.user.id,
        });
        alert(response.data.message);
        this.$router.push("/");
      } catch (error) {
        console.error("Error creating post:", error);
        alert("Failed to create post. Please try again.");
      }
    },
  },
  mounted() {
    this.user = this.store.user;
  },
};
</script>

<style scoped>

</style>