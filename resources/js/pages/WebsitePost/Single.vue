<template>
    <div>
        <div class="actions-container mb-3" v-if="website_post.website">
            <router-link :to="{ name: 'website.post.list', params: { id: website_post.website.id } }">
                <div class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Back
                </div>
            </router-link>
            <router-link :to="{ name: 'website.post.edit', params: { id: website_post.id } }" v-if="user.is_admin">
                <div class="btn btn-warning">
                    <i class="fa-solid fa-pen-to-square"></i> Edit
                </div>
            </router-link>
            <div class="btn btn-danger" @click="deleteReady(website_post.id)" v-if="user.is_admin">
                <i class="fa-solid fa-trash"></i> Delete
            </div>
            
        </div>
        <div class="single-post-container" v-if="website_post.user">
            <div class="card">
                <div class="card-body">
                    <h2>{{ website_post.title }}</h2>
                    <small>By: {{ website_post.user.name }}</small>
                    <p class="mt-5">{{ website_post.description }}</p>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { mapGetters } from 'vuex'
import { AlertQuestion, ToastSuccess, ToastError } from '~/config/alerts'

export default {
    name: 'admin-website-post-view',
    middleware: 'auth',

    data: () => ({}),

    computed: mapGetters({
        website_post: 'website-post/website_post',
        website: 'website/website',
        user: 'auth/user',
    }),

    mounted() {
        this.$store.dispatch('website-post/fetchWebsitePost', { id: this.$route.params.id})
    },

    methods: {
        deleteReady(id) {
            AlertQuestion('Are you sure to delete ?', 'There is no undo for this action', true, 'Yes, Delete!')
                .then(res => {
                    this.delete(id)
                })
        },
        delete(id) {
            this.$store.dispatch('website-post/deleteWebsitePost', id)
                .then(res => {
                    if(res.success){
                        ToastSuccess('Deleted!', res.message)

                        this.$router.push({ name: 'website.post.list', params: { id: this.website.id } })
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