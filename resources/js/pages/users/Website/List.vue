<template>
    <div>
        <card :title="$t('Websites List')">
            <table class="table">
                <thead class="thead-dark table-striped">
                    <tr>
                        <th scope="col">Author</th>
                        <th scope="col">Title</th>
                        <th scope="col" width="300">Date Created</th>
                        <th scope="col" width="50">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in websites" :key="index">
                        <td>{{ item.user.name }}</td>
                        <td>{{ item.title }}</td>
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
import { AlertQuestion, ToastSuccess, ToastError } from '~/config/alerts'

export default {
    name: 'admin-course-category',
    middleware: 'auth',

    data: () => ({}),

    computed: mapGetters({
        websites: 'website/websites',
    }),

    mounted() {
        this.$store.dispatch('website/fetchWebsites')
    },

    methods: {
        deleteReady(id) {
            AlertQuestion('Are you sure to delete ?', 'There is no undo for this action', true, 'Yes, Delete!')
                .then(res => {
                    this.delete(id)
                })
        },
        delete(id) {
            this.$store.dispatch('website/deleteWebsite', id)
                .then(res => {
                    if(res.success){
                        ToastSuccess('Deleted!', res.message)
                    }else{
                        ToastError("Can't Delete", "This category is attached one of the courses.")
                    }
                    
                })
                .catch(err => {
                    ToastError()
                })
        }
    },
}

</script>
<style scoped>
.actions-container {
    display: flex;
    justify-content: flex-end;
}
</style>