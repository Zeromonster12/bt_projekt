<template>
  <div>
    <h1>Post Details</h1>
    <div v-if="post" class="post-details">
      <h2>{{ post.title }}</h2>
      <p>{{ post.content }}</p>
      <img v-if="post.image" :src="post.image" alt="Post Image" />
      <p><strong>Created At:</strong> {{ post.created_at }}</p>
      <p><strong>Updated At:</strong> {{ post.updated_at }}</p>
      <p><strong>Year:</strong> {{ post.year }}</p>
      <p><strong>User ID:</strong> {{ post.user_id }}</p>
      <p><strong>Year ID:</strong> {{ post.year_id }}</p>
      <p><strong>ID:</strong> {{ post.id }}</p>
    </div>
    <div v-else class="loading">Loading...</div>
  </div>
</template>

<script>
import { usePostStore } from "@/stores/postStore";

export default {
  name: "PostView",
  computed: {
    post() {
      return this.postStore.selectedPost;
    },
  },
  data() {
    return {
      postStore: usePostStore(),
    };
  },
  watch: {
    "$route.params.postId": {
      immediate: true,
      handler(newPostId) {
        this.postStore.fetchPost(parseInt(newPostId));
      },
    },
  },
};
</script>