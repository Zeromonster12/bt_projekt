import { defineStore } from 'pinia';
import api from '@/api';

export const useUserStore = defineStore('user', {
    state: () => ({
        users: [],
        editedUsers: null,
        user: null,
        years: [],
    }),

    actions: {
        async fetchUsers() {
            try {
                const response = await api.get('/fetchUsers');
                this.users = response.data;
            } catch (error) {
                throw error;
            }
        },
        async fetchYears() {
            try {
                const response = await api.get('/years');
                this.years = response.data
            } catch (error) {
                throw error;
            }
        },
        async updateUsers() {
            try {
                const response = await api.post('/updateUser', this.editedUsers);
                this.users = response.data;
                await this.fetchUsers();
                this.editedUsers = null;
            }
            catch (error) {
                throw error;
            }
        },
        async deleteUser(userId : number) {
            try {
                const response = await api.delete(`/deleteUser/${userId}`);
                this.users = response.data;
                await this.fetchUsers();
            } catch (error) {
                throw error;
            }
        }
    },
});
