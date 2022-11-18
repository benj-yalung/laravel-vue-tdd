<template>
    <div>
        <card :title="$t('Websites Subscribers')">
            <table class="table">
                <thead class="thead-dark table-striped">
                    <tr>
                        <th scope="col" width="100">#</th>
                        <th scope="col">Website</th>
                        <th scope="col">Subscriber</th>
                        <th scope="col" width="300">Date Subscribed</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in website_subscribers" :key="index">
                        <th scope="row">{{ item.id }}</th>
                        <td>{{ item.website.title }}</td>
                        <td>{{ item.user.name }}</td>
                        <td>{{ item.created_at | moment('LL') }}</td>
                    </tr>
                </tbody>
            </table>
        </card>
    </div>
</template>
<script>
import { mapGetters } from 'vuex'

export default {
    name: 'admin-course-category',
    middleware: 'auth',

    data: () => ({}),

    computed: mapGetters({
        website_subscribers: 'website-subscriber/website_subscribers',
    }),

    mounted() {
        this.$store.dispatch('website-subscriber/fetchWebsiteSubscribers')
    },
}

</script>
<style scoped>
.actions-container {
    display: flex;
    justify-content: flex-end;
}
</style>