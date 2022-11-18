<template>
    <div class="row">
      <div class="col-lg-10 m-auto">
        <card :title="$t('Dashboard')">
          {{ $t('you_are_logged_in') }} as User
        </card>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    middleware: 'auth',
  
    metaInfo () {
      return { title: this.$t('home') }
    }
  }
  </script>
  