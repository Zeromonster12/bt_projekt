import { defineStore } from 'pinia';
import api from '@/api';

export const usePostStore = defineStore('post', {
  state: () => ({
    posts: [],
    filteredPosts: [],
    currentYear: new Date().getFullYear(),
    selectedPost: null,
    years: [],
    yearsWithId: [],
  }),

  actions: {
    async deletePost(postId) {
      try {
        await api.delete(`/posts/${postId}`);
        this.posts = this.posts.filter((post) => post.id !== postId);
      } catch (error) {
        console.error("Error deleting post:", error);
      }
    },    
    async fetchPosts() {
      try {
        const response = await api.get('/posts');
        this.posts = response.data;
        this.filterPostsByYear(this.currentYear);
        return this.posts;
      } catch (error) {
        console.error('Error fetching posts:', error);
        return [];
      }
    },

    async fetchPost(id) {
      try {
        const response = await api.get(`/post/${id}`);
        this.selectedPost = response.data;
      } catch (error) {
        console.error('Error fetching post:', error);
      }
    },

    filterPostsByYear(year) {
      this.currentYear = year;
      this.filteredPosts = this.posts.filter((post) => parseInt(post.year) === parseInt(year));
    },

    handlePostDeleted(postId) {
      this.filteredPosts = this.filteredPosts.filter((post) => post.id !== postId);
    },

    async fetchYears() {
      try {
        const response = await api.get('/years');
        this.years = response.data
          .map((item) => parseInt(item.year))
          .sort((a, b) => b - a);
      } catch (error) {
        console.error('Error fetching years:', error);
      }
    },

    async fetchYearsWithId() {
      try {
        const response = await api.get('/years');
        this.yearsWithId = response.data;
      } catch (error) {
        console.error('Error fetching years with ID:', error);
      }
    },

    async selectYear(year, router) {
      try {
        const response = await api.get('/posts');
        const posts = response.data;
        const filteredPosts = posts.filter((post) => parseInt(post.year) === parseInt(year));
        if (filteredPosts.length > 0) {
          const firstPostId = filteredPosts[0].id;
          router.push(`/${year}/post/${firstPostId}`);
        } else {
          alert(`No posts available for the year ${year}`);
        }
      } catch (error) {
        console.error('Error fetching posts for the selected year:', error);
      }
    },

    async newestPost() {
      try {
      const response = await api.get('/posts');
      return response.data;
      } catch (error) {
      console.error('Error fetching newest post:', error);
      return null;
      }
    },

    async validatePost(postId, year) {
      try {
        await this.fetchPost(postId);
        const post = this.selectedPost;

        if (!post) {
          return { valid: false, message: `Post with ID ${postId} does not exist.` };
        } else if (parseInt(post.year) !== parseInt(year)) {
          return { valid: false, message: `Post with ID ${postId} does not exist in year ${year}.` };
        }

        return { valid: true, post };
      } catch (error) {
        return { valid: false, message: "An error occurred while fetching the post." };
      }
    }
  },
});