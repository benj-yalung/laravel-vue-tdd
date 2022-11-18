import axios from 'axios'
import * as types from '../mutation-types'

// state
export const state = {
    loading: false,
    website_subscribers: [],
    user_websites: [],
}

// getters
export const getters = {
    loading: state => state.loading,
    website_subscribers: state => state.website_subscribers,
    user_websites: state => state.user_websites
}

// mutations
export const mutations = {
    [types.FETCH_ALL_WEBSITE_SUBSCRIBER] (state, { data }) {
        state.website_subscribers = data        
        state.loading = false
    },

    [types.FETCH_USER_WEBSITES] (state, { data }) {
        state.user_websites = data        
        state.loading = false
    },
}

// actions
export const actions = {

    async fetchWebsiteSubscribers ({ commit }) {
        try {
            state.loading = true
            
            const { data } = await axios.get('/api/website-subscription/fetch-with-subscriber')
            
            commit(types.FETCH_ALL_WEBSITE_SUBSCRIBER, data)
        } catch (error) {
            console.log(error)
            state.loading = false
        }
    },

    async fetchUserWebsites ({ commit }) {
        try {
            state.loading = true
            
            const { data } = await axios.get('/api/website-subscription/fetch-user-websites')
            console.log(data)
            commit(types.FETCH_USER_WEBSITES, data)
        } catch (error) {
            console.log(error)
            state.loading = false
        }
    },

    async subscribeToWebsite ({ commit }, { websiteSubscriberForm }) {
        try {
            state.loading = true
            
            const { data } = await websiteSubscriberForm.post('/api/website-subscription')
            
            return data;
        } catch (error) {
            state.loading = false
        }
    },

    async unsubscribeToWebsite ({ commit }, id) {
        try {
            state.loading = true
            
            const { data } = await axios.delete('/api/website-subscription/unsubscribe/'+id)
            
            return data;
        } catch (error) {
            console.log(error)
            state.loading = false
        }
    },
}