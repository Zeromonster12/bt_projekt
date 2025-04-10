<template>
  <div>
    <div v-if="post">
      <h2>{{ post.title }}</h2>
      <p>{{ post.body }}</p>
    </div>
    <div v-else>
      <p>Loading post...</p>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "PostView",
  props: {
    id: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      post: null,
    };
  },
  methods: {
    async fetchPost() {
      try {
        const response = await axios.get(
          `http://127.0.0.1:8000/api/post/${this.id}`
        );
        this.post = response.data;
      } catch (error) {
        console.error("Error fetching post:", error);
      }
    },
  },
  watch: {
    id: {
      immediate: true,
      handler() {
        this.fetchPost();
      },
    },
  },
};
</script>