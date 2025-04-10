<template>
  <div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title">{{ post.id }}</h5>
      <h5 class="card-title">{{ post.title }}</h5>
      <div>
        <button class="btn btn-sm btn-outline-primary me-2" @click="editPost">
          <i class="fas fa-edit"></i>
        </button>
        <button class="btn btn-sm btn-outline-danger" @click="deletePost">
          <i class="fas fa-trash"></i>
        </button>
      </div>
    </div>
    <div class="card-body">
      <p class="card-text">{{ post.body }}</p>
      <div class="d-flex justify-content-between mt-3">
        <small class="text-muted"><strong>Author:</strong> {{ post.author || 'Unknown' }}</small>
        <small class="text-muted"><strong>Date:</strong> {{ post.date || 'Unknown' }}</small>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'PostComponent',
  props: {
    post: {
      type: Object,
      required: true,
    },
  },
  methods: {
    editPost() {
      this.$emit('edit-post', this.post);
    },
    deletePost() {
      this.$emit('delete-post', this.post.id);
    },
  },
};
</script>

<style scoped>
</style>