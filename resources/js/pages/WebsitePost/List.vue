<template>
    <div>
        <div class="actions-container mb-3">
            <router-link :to="{ name: user.is_admin ? 'admin.website' : 'user.websites' }">
                <div class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Back
                </div>
            </router-link>
            <router-link :to="{ name: 'website.post.create', params: { id: website.id } }" v-if="user.is_admin">
                <div class="btn btn-primary">
                    <i class="fa-solid fa-plus"></i> Add new post
                </div>
            </router-link>
        </div>
        <card :title="website.title">
            <h3>Posts</h3>
            <div class="posts-container mt-5">
                <div class="action-container mb-3" v-if="!user.is_admin">
                    <div class="btn btn-danger" @click="unsubscribe" v-if="is_subscribed">
                        Unsubscribe
                    </div>
                    <div class="btn btn-primary" @click="subscribe" v-else>
                        Subscribe Now
                    </div>
                </div>
                <div class="row" v-if="website.post && website.post.length > 0">
                    <div class="col-lg-8 col-md-10 col-sm-12 mb-3 pb-2 border-bottom" v-for="(post, index) in website.post" :key="index">
                        <div class="post-title mb-3">
                            <router-link :to="{ name: 'website.post.single', params: { id: post.id }  }">
                                <h5 class="mb-0"><b>{{ post.title }}</b></h5>
                            </router-link>
                            <small>By: {{ website.user.name }}</small>
                        </div>
                        <div class="post-details">
                            <p>Sample detail;s</p>
                        </div>
                    </div>
                </div>
                <div class="no-data-container" v-else>
                    This Website has no Post yet
                </div>
            </div>
        </card>
    </div>
</template>
<script>
import { mapGetters } from 'vuex'
import Form from 'vform'
import { ToastSuccess, ToastError } from '~/config/alerts'

export default {
    name: 'admin-website-post-list',
    middleware: 'auth',

    data: () => ({
        is_subscribed: false,
        subscriber_data: {},
        websiteSubscriberForm: new Form({
            website_id: '',
        })
    }),

    computed: mapGetters({
        website: 'website/website',
        user: 'auth/user',
    }),

    mounted() {
        this.$store.dispatch('website/fetchWebsite', { id: this.$route.params.id}).then(()=>{
            this.website.subscribers.forEach(element => {
                if (element.user_id == this.user.id) {
                    this.is_subscribed = true;
                    this.subscriber_data = element;
                }
            });
        })
    },

    methods: {
        subscribe() {
            this.websiteSubscriberForm.website_id = this.$route.params.id;

            this.$store.dispatch('website-subscriber/subscribeToWebsite', { websiteSubscriberForm: this.websiteSubscriberForm })
            .then(res => {
                if(res.success){
                    ToastSuccess('Subscribed!', res.message)

                    this.is_subscribed = true;
                }else{
                    ToastError()
                }
                
            })
            .catch(err => {
                ToastError()
            })
        },
        unsubscribe() {
            this.$store.dispatch('website-subscriber/unsubscribeToWebsite', this.subscriber_data.id)
            .then(res => {
                if(res.success){
                    ToastSuccess('Successfull!', res.message)

                    this.is_subscribed = false;
                }else{
                    ToastError()
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