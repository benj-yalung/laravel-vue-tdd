import Vue from 'vue'
import vuetify from '~/plugins/vuetify' // path to vuetify export
import store from '~/store'
import router from '~/router'
import i18n from '~/plugins/i18n'
import App from '~/components/App'

import '~/plugins'
import '~/components'

Vue.config.productionTip = false
Vue.use(require('vue-moment')); // Moment JS

/* eslint-disable no-new */
new Vue({
  i18n,
  store,
  router,
  vuetify,
  ...App
})
