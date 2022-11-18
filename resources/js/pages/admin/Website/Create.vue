<template>
    <div>
        <card :title="$t('Websites List')">
            <div class="action-container mb-5">
                <router-link :to="{ name: 'admin.website' }">
                    <div class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Back
                    </div>
                </router-link>
                <div class="btn btn-primary" @click="saveWebsite">
                    <i class="fa-regular fa-floppy-disk"></i> Save
                </div>
            </div>
            <div class="form-container">
                <form @submit.prevent="saveWebsite" @keydown="websiteForm.onKeydown($event)">
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Title</label>
                        <input type="text" class="form-control" v-model="websiteForm.title" placeholder="Enter title.">
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">Description</label>
                        <textarea class="form-control" v-model="websiteForm.description" rows="3"></textarea>
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
        websiteForm: new Form({
            title: '',
            description: '',
        })
    }),

    computed: mapGetters({
        websites: 'website/websites',
    }),

    mounted() {
        this.$store.dispatch('website/fetchWebsites')
    },

    methods: {
        saveWebsite() {
            this.$store.dispatch('website/saveWebsite', this.websiteForm)
                .then(({ success, message }) => {
                    if (success) {
                        ToastSuccess('Success!', message);

                        this.$router.push({ name: 'admin.website' })
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