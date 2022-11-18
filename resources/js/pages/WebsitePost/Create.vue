<template>
    <div>
        <card :title="$t('Publish new post')">
            <div class="action-container mb-5">
                <router-link :to="{ name: 'website.post.list', params: { id: this.$route.params.id } }">
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
            website_id: '',
            title: '',
            description: '',
        })
    }),

    computed: mapGetters({
        website: 'website/website',
        websites: 'website/websites',
    }),

    mounted() {
        this.$store.dispatch('website/fetchWebsites')
    },

    methods: {
        saveWebsitePost() {
            this.websitePostForm.website_id = this.$route.params.id;

            this.$store.dispatch('website-post/saveWebsitePost', this.websitePostForm)
                .then(({ success, message }) => {
                    if (success) {
                        ToastSuccess('Success!', message);

                        this.$router.push({ name: 'website.post.list', params: { id: this.$route.params.id } })
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