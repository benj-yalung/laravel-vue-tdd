<template>
    <div>
        <card :title="$t('Websites List')">
            <table class="table">
                <thead class="thead-dark table-striped">
                    <tr>
                        <th scope="col">Title</th>
                        <th scope="col" width="300">Date Created</th>
                        <th scope="col" width="50">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in user_websites" :key="index">
                        <td>{{ item.website.title }}</td>
                        <td>{{ item.created_at | moment('LL') }}</td>
                        <td>
                            <div class="action-container d-flex">
                                <router-link :to="{ name: 'website.post.list', params: { id: item.id }  }">
                                    <div class="btn btn-info">
                                        <i class="fa-solid fa-eye"></i>
                                    </div>
                                </router-link>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </card>
    </div>
</template>
<script>
import { mapGetters } from 'vuex'

export default {
    name: 'user-website-subscribed',
    middleware: 'auth',

    data: () => ({}),

    computed: mapGetters({
        user_websites: 'website-subscriber/user_websites',
    }),

    mounted() {
        this.$store.dispatch('website-subscriber/fetchUserWebsites')
    },

    methods: {
        
    },
}

</script>
<style scoped>
.actions-container {
    display: flex;
    justify-content: flex-end;
}
</style>