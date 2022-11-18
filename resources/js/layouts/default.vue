<template>
  <div class="main-layout">
    <side-bar />

    <div :class="user ? 'main-container' : 'container-lg'">
      <navbar />
      <child class="mt-5"/>
    </div>
  </div>
</template>

<script>
import Navbar from '~/components/Navbar'
import SideBar from '~/components/SideBar'
import { mapGetters } from 'vuex'

export default {
  name: 'MainLayout',
  computed: mapGetters({
    user: 'auth/user'
  }),
  components: {
    Navbar,
    SideBar
  }
}
</script>
