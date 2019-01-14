<template>
  <span class="subheader">
    Dashboard
  </span>
</template>

<script>
export default {
  head: {
    title: 'Dashboard'
  },
  created() {
    this.$router.push({ name: 'transactions' })
  }
}
</script>
