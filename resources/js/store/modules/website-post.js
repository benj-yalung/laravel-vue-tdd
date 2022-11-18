import axios from 'axios'
import * as types from '../mutation-types'

// state
export const state = {
    loading: false,
    websites_post: [],
    website_post: {}
}

// getters
export const getters = {
    loading: state => state.loading,
    websites_post: state => state.websites_post,
    website_post: state => state.website_post
}

// mutations
export const mutations = {

    [types.SET_WEBSITE_POST] (state, { data }) {
        state.website_post = data
    },

    [types.FETCH_ALL_WEBSITE_POST] (state, { data }) {
        state.websites_post = data        
        state.loading = false
    },

    [types.SAVE_WEBSITE_POST] (state, { data }) {
        const index = state.websites_post.findIndex(hb => hb.id === data.id)

        if (index !== -1) {
            state.websites_post.splice(index, 1, data)
            state.website_post = data;
        } else {
            state.websites_post.unshift(data)
        }

        state.loading = false
    },
    [types.DELETE_WEBSITE_POST] (state, id) {
        const index = state.websites_post.findIndex(_course_category => _course_category.id === id)
        if (index !== -1) {
            state.websites_post.splice(index, 1)
        }

        state.website_post = {}
    },
}

// actions
export const actions = {

    async fetchWebsitesPost ({ commit }) {
        try {
            state.loading = true
            
            const { data } = await axios.get('/api/website-post/')

            commit(types.FETCH_ALL_WEBSITE_POST, data)
        } catch (error) {
            console.log(error)
            state.loading = false
        }
    },
    
    async fetchWebsitePost ({ commit }, payload) {
        try {
            state.loading = true
            
            const { data } = await axios.get('/api/website-post/'+payload.id)
            console.log(data)

            commit(types.SET_WEBSITE_POST, data)
        } catch (error) {
            state.loading = false
        }
    },
        
    async saveWebsitePost ({ commit }, websiteForm) {
        try {
            state.loading = true

            const saveURL = '/api/website-post/'
            const { data } = await (websiteForm.post(saveURL))

            commit(types.SAVE_WEBSITE_POST, { data: data.data })

            return data
        } catch (error) {
            console.log(error)
            state.loading = false
        }
    },

    async updateWebsitePost ({ commit }, { websitePostForm, id }) {
        try {
            state.loading = true

            const { data } = await (websitePostForm.put('/api/website-post/update/'+id))
            
            commit(types.SAVE_WEBSITE_POST, { data: data.data })

            return data
        } catch (error) {
            console.log(error)
            state.loading = false
        }
    },

    async deleteWebsitePost ({ commit }, id) {
        try {
            const { data } = await axios.delete('/api/website-post/'+id)
            console.log(data)
            commit(types.DELETE_WEBSITE_POST, id)

            return data
        } catch (error) {
            state.loading = false
            const { response } = error
            return response.data
        }
    },
}