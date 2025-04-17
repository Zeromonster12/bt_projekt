<template>
  <div>
    <div v-if="errorMessage">
      <p class="error">{{ errorMessage }}</p>
    </div>
    <div v-else-if="post">
      <h2>{{ post.title }}</h2>
      <p>{{ post.body }}</p>
    </div>
    <div v-else>
      <p>Loading post...</p>
    </div>
  </div>
</template>

<script>
import { usePostStore } from "../stores/postStore";

export default {
  name: "PostView",
  props: {
    id: {
      type: String,
      required: true,
    },
    year: {
      type: String,
      required: true,
    },
  },
  computed: {
    post() {
      return this.postStore.selectedPost;
    },
  },
  data() {
    return {
      postStore: usePostStore(),
      errorMessage: "",
    };
  },
  watch: {
    id: {
      immediate: true,
      async handler(newId) {
        this.errorMessage = "";
        const year = parseInt(this.year);

        const validation = await this.postStore.validatePost(newId, year);
        if (!validation.valid) {
          this.errorMessage = validation.message;
        }
      },
    },
  },
};
</script>

<style scoped>
</style>