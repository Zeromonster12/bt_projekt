<template>
  <div class="post-page">
    <div class="post-container" v-if="post">
      <h1 class="post-title">{{ post.title }}</h1>      <div class="post-meta">
        <span>Naposledy upravené: {{ formatDateTime(post.updated_at) }}</span>
      </div>
      <div class="post-body wysiwyg-content" v-html="post.body"></div>
      <div v-if="post.image" class="post-image">
        <img :src="post.image" alt="Obrázok príspevku" />
      </div>
    </div>
    <div v-else class="loading">Načítavam...</div>
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
  },  data() {
    return {
      postStore: usePostStore(),
    };
  },
  methods: {
    formatDateTime(dateTimeString) {
      if (!dateTimeString) return '';
      
      
      const date = new Date(dateTimeString);
      
      
      const day = date.getDate().toString().padStart(2, '0');
      const month = (date.getMonth() + 1).toString().padStart(2, '0');
      const year = date.getFullYear();
      const hours = date.getHours().toString().padStart(2, '0');
      const minutes = date.getMinutes().toString().padStart(2, '0');
      
      return `${day}.${month}.${year} ${hours}:${minutes}`;
    }
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

<style scoped>
.post-page {
  display: flex;
  justify-content: center;
  background: #f8f9fa;
  min-height: 100vh;
}

.post-container {
  background: #f1f1f1;
  border-radius: 12px;
  padding: 2rem 2.5rem;
  width: 100%;
}

.post-title {
  font-size: 2.2rem;
  font-weight: bold;
  margin-bottom: 0.5rem;
  color: #2c3e50;
}

.post-meta {
  font-size: 0.95rem;
  color: #888;
  margin-bottom: 1.5rem;
  display: flex;
  flex-wrap: wrap;
  gap: 1.5rem;
}

.post-body {
  font-size: 1.1rem;
  color: #222;
  line-height: 1.7;
  margin-bottom: 2rem;
}
.wysiwyg-content img {
  max-width: 100%;
  border-radius: 8px;
  margin: 1rem 0;
  display: block;
}
.wysiwyg-content h1, .wysiwyg-content h2, .wysiwyg-content h3 {
  color: #2c3e50;
  margin-top: 1.5rem;
}
.wysiwyg-content ul, .wysiwyg-content ol {
  margin-left: 2rem;
}
.post-image {
  text-align: center;
  margin-top: 1.5rem;
}
.post-image img {
  max-width: 100%;
  border-radius: 10px;
  box-shadow: 0 2px 12px rgba(0,0,0,0.08);
}
.loading {
  text-align: center;
  font-size: 1.2rem;
  color: #888;
  margin-top: 3rem;
}
</style>