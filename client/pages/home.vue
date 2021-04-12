<template>
  <card :title="$t('home')">
    <mail-list></mail-list>

    <router-link :to="{ name: 'mail.create' }" class="floating-button">
      <fa icon="plus" fixed-width />
    </router-link>
  </card>
</template>

<script>
import MailList from './mail/list'
export default {
  components: { MailList },
  middleware: 'auth',
  head () {
    return { title: this.$t('home') }
  }
}
</script>
