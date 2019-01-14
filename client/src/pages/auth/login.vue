<template>
  <v-card class="elevation-12">
    <v-toolbar dark color="primary">
      <v-toolbar-title>Login</v-toolbar-title>
    </v-toolbar>
    <v-card-text>
      <v-alert
        v-if="error"
        :value="true"
        color="error"
        icon="warning"
      >
        The credentials do not match our records.
      </v-alert>

      <v-form>
        <v-text-field v-model="form.username" label="E-mail" />
        <v-text-field
          v-model="form.password"
          :append-icon="showPassword ? 'visibility' : 'visibility_off'"
          :type="showPassword ? 'text' : 'password'"
          label="Password"
          @click:append="(showPassword = !showPassword)"
        />
      </v-form>
    </v-card-text>
    <v-card-actions>
      <v-spacer />
      <v-btn color="primary" @click="login">
        Login
      </v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
export default {
  layout: 'auth',
  head: {
    title: 'Log In'
  },
  data() {
    return {
      form: {
        username: '',
        password: ''
      },
      error: false,
      showPassword: false
    }
  },
  methods: {
    login() {
      this.$auth.loginWith('local', {
        data: {
          ...this.form,
          grant_type: 'password',
          client_id: process.env.clientId,
          client_secret: process.env.clientSecret
        }
      }).then(() => {
        this.error = false
      }).catch(() => {
        this.error = true
      })
    }
  }
}
</script>
