<template>
    <div class="sidebar border-end" id="sidebar-wrapper">
        <div class="sidebar-heading border-bottom bg-light">Menu</div>
        <div class="list-group list-group-flush">
            <router-link
                v-for="item in menuItems"
                :key="item.id"
                :to="item.path"
                class="list-group-item list-group-item-action list-group-item-light p-3"
                :class="{ active: isActive(item.path) }"
            >
                <font-awesome-icon v-if="item.icon" :icon="item.icon" /> {{ item.title }}
            </router-link>
        </div>
    </div>
</template>

<script>
export default {
    name: 'SideBarComponent',
    data() {
        return {
            menuItems: [
                { id: 1, title: 'Dashboard', path: '/', icon: 'gauge' },
                { id: 2, title: 'Users', path: '/users', icon: 'users' },
                { id: 3, title: 'Reports', path: '/reports', icon: 'file-lines' },
                { id: 4, title: 'Settings', path: '/settings', icon: 'gear' }
            ]
        };
    },
    methods: {
        isActive(path) {
            return this.$route.path === path;
        }
    }
};
</script>

<style scoped>
.sidebar {
    min-height: calc(100vh - 56px);
}
</style>