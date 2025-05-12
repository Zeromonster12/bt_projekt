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
        async createUser(newUser) {
            try {
                const response = await api.post('/createUser', newUser);
                await this.fetchUsers();
                return response.data;
            } catch (error) {
                console.error('Error creating user:', error);
                throw error;
            }
        },
        async updateUsers() {
            try {
                if (this.editedUsers) {
                    const response = await api.post('/updateUser', this.editedUsers);
                    
                    if (response && response.data) {
                        if (Array.isArray(response.data)) {
                            this.users = response.data;
                        } else {
                            await this.fetchUsers();
                        }
                    } else {
                        await this.fetchUsers();
                    }
                    
                    this.editedUsers = null;
                }
            }
            catch (error) {
                console.error('Error updating user:', error);
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
