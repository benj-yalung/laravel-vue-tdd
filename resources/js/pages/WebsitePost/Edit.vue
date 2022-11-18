<template>
    <div>
        <card :title="$t('Websites List')">
            <div class="action-container mb-5">
                <router-link :to="{ name: 'admin.website' }">
                    <div class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Back
                    </div>
                </router-link>
                <div class="btn btn-primary" @click="saveWebsitePost">
                    <i class="fa-regular fa-floppy-disk"></i> Save
                </div>
            </div>
            <div class="form-container">
                <form @submit.prevent="saveWebsitePost" @keydown="websitePostForm.onKeydown($event)">
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Title</label>
                        <input type="text" class="form-control" v-model="websitePostForm.title" placeholder="Enter title.">
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">Description</label>
                        <textarea class="form-control" v-model="websitePostForm.description" rows="3"></textarea>
                    </div>
                </form>
            </div>
        </card>
    </div>
</template>
<script>
import { mapGetters } from 'vuex'
import Form from 'vform'
import { ToastSuccess, ToastError } from '~/config/alerts'

export default {
    name: 'admin-course-category',
    middleware: 'auth',

    data: () => ({
        websitePostForm: new Form({
            title: '',
            description: '',
        })
    }),

    computed: mapGetters({
        website_post: 'website-post/website_post',
    }),

    mounted() {
        this.$store.dispatch('website-post/fetchWebsitePost', { id: this.$route.params.id })
        .then(()=>{
            this.websitePostForm.keys().forEach(key => {
                this.websitePostForm[key] = this.website_post[key]
            })
        });
    },

    methods: {
        saveWebsitePost() {
            this.$store.dispatch('website-post/updateWebsitePost', {websitePostForm: this.websitePostForm, id: this.$route.params.id})
                .then(({ success, message }) => {
                    if (success) {
                        ToastSuccess('Success!', message);

                        this.$router.push({ name: 'website.post.single', params: { id: this.$route.params.id } })
                    }
                })
                .catch(err => {
                    ToastError();
                })
        }
    },
}

</script>
<style scoped>
.action-container {
    display: flex;
    justify-content: flex-end;
}
</style>