<template>
    <v-navigation-drawer v-model="drawer" permanent color="#F4F5F9" app v-if="user">
        <v-list nav dense>
            <v-list-item-group v-model="selectedItem" color="deep-purple" class="mt-3">
                <v-list-item v-for="(item, i) in user.is_admin ? items.admin : items.user" :key="i">
                    <router-link :to="{ name: item.route }" class="navbar-link">
                        <v-list-item-icon>
                            <v-icon v-text="item.icon"></v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                            <v-list-item-title v-text="item.text"></v-list-item-title>
                        </v-list-item-content>
                    </router-link>
                </v-list-item>
            </v-list-item-group>
        </v-list>
    </v-navigation-drawer>
</template>
<script>
import { mapGetters } from 'vuex'

export default {
    computed: mapGetters({
        user: 'auth/user'
    }),
    data: () => ({
        selectedItem: 0,
        drawer: null,
        items: {
            admin: [
                {icon: 'fas fa-home', text: 'Dashboard', route: 'admin.dashboard'},
                {icon: 'fas fa-home', text: 'My Websites', route: 'admin.website'},
                {icon: 'fas fa-money-check-alt', text: 'Subscribers', route: 'admin.website.subscribers'}
            ],
            user: [
                {icon: 'fas fa-home', text: 'Dashboard', route: 'user.dashboard'},
                {icon: 'fas fa-home', text: 'Websites', route: 'user.websites'},
                {icon: 'fas fa-money-check-alt', text: 'Subscription', route: 'user.website.subscriptions'}
            ]
        }
    })
}
</script>
<style scoped>
.v-list a {
    text-decoration: none;
    color: #000;
}
</style>