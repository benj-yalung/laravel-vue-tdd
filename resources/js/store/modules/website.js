import axios from 'axios'
import * as types from '../mutation-types'

// state
export const state = {
    loading: false,
    websites: [],
    website: {}
}

// getters
export const getters = {
    loading: state => state.loading,
    websites: state => state.websites,
    website: state => state.website
}

// mutations
export const mutations = {

    [types.SET_WEBSITE] (state, { data }) {
        state.website = data
    },

    [types.FETCH_ALL_WEBSITE] (state, { data }) {
        state.websites = data        
        state.loading = false
    },

    [types.SAVE_WEBSITE] (state, { data }) {
        const index = state.websites.findIndex(hb => hb.id === data.id)

        if (index !== -1) {
            state.websites.splice(index, 1, data)
            state.website = data;
        } else {
            state.websites.unshift(data)
        }

        state.loading = false
    },
    [types.DELETE_WEBSITE] (state, id) {
        const index = state.websites.findIndex(_course_category => _course_category.id === id)
        if (index !== -1) {
            state.websites.splice(index, 1)
        }

        state.website = {}
    },
}

// actions
export const actions = {

    async fetchWebsites ({ commit }) {
        try {
            state.loading = true
            
            const { data } = await axios.get('/api/websites/')
            console.log(data)
            commit(types.FETCH_ALL_WEBSITE, data)
        } catch (error) {
            console.log(error)
            state.loading = false
        }
    },
    
    async fetchWebsite ({ commit }, payload) {
        try {
            state.loading = true
            
            const { data } = await axios.get('/api/websites/'+payload.id)
            console.log(data)

            commit(types.SET_WEBSITE, data)
        } catch (error) {
            state.loading = false
        }
    },
        
    async saveWebsite ({ commit }, websiteForm) {
        try {
            state.loading = true

            const saveURL = '/api/websites/'
            const { data } = await (websiteForm.post(saveURL))

            commit(types.SAVE_WEBSITE, { data: data.data })

            return data
        } catch (error) {
            console.log(error)
            state.loading = false
        }
    },

    async updateWebsite ({ commit }, { websiteForm, id }) {
        try {
            state.loading = true

            const { data } = await (websiteForm.put('/api/websites/update/'+id))
            
            commit(types.SAVE_WEBSITE, { data: data.data })

            return data
        } catch (error) {
            console.log(error)
            state.loading = false
        }
    },

    async deleteWebsite ({ commit }, id) {
        try {
            const { data } = await axios.delete('/api/websites/'+id)

            commit(types.DELETE_WEBSITE, id)

            return data
        } catch (error) {
            state.loading = false
            const { response } = error
            return response.data
        }
    },
}