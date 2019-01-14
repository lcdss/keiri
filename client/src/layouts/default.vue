<template>
  <v-app>
    <v-navigation-drawer v-model="drawer" :clipped="$vuetify.breakpoint.mdAndUp" app fixed>
      <v-list dense>
        <template v-for="(item, index) in items">
          <v-layout v-if="item.heading" :key="index" row align-center>
            <v-flex xs6>
              <v-subheader v-if="item.heading">
                {{ item.heading }}
              </v-subheader>
            </v-flex>
            <v-flex xs6 class="text-xs-center">
              <a href="#!" class="body-2 black--text">
                EDIT
              </a>
            </v-flex>
          </v-layout>
          <v-list-group v-else-if="item.children" :key="index" v-model="item.model" no-action>
            <v-list-tile slot="item">
              <v-list-tile-action>
                <v-icon>{{ item.model ? item.icon : item['icon-alt'] }}</v-icon>
              </v-list-tile-action>
              <v-list-tile-content>
                <v-list-tile-title>{{ item.text }}</v-list-tile-title>
              </v-list-tile-content>
            </v-list-tile>
            <v-list-tile v-for="(child, i) in item.children" :key="i" :to="child.to" router>
              <v-list-tile-action v-if="child.icon">
                <v-icon>{{ child.icon }}</v-icon>
              </v-list-tile-action>
              <v-list-tile-content>
                <v-list-tile-title>{{ child.text }}</v-list-tile-title>
              </v-list-tile-content>
            </v-list-tile>
          </v-list-group>
          <v-list-tile v-else :key="index" :to="item.to" router exact>
            <v-list-tile-action>
              <v-icon>{{ item.icon }}</v-icon>
            </v-list-tile-action>
            <v-list-tile-content>
              <v-list-tile-title>{{ item.text }}</v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>
        </template>
      </v-list>
    </v-navigation-drawer>
    <v-toolbar :clipped-left="$vuetify.breakpoint.mdAndUp" app dark fixed color="primary">
      <v-toolbar-title style="width: 300px" class="ml-0 pl-3">
        <v-toolbar-side-icon @click.stop="drawer = !drawer" />
        <span class="hidden-sm-and-down">
          Keiri
        </span>
      </v-toolbar-title>
      <v-spacer />
      <v-menu :close-on-content-click="false" :nudge-width="200" offset-x>
        <v-avatar slot="activator" tile size="32px">
          <img src="https://cdn.vuetifyjs.com/images/logos/v-alt.svg" alt="Vuetify">
        </v-avatar>
        <v-card>
          <v-list>
            <v-list-tile avatar>
              <v-list-tile-avatar>
                <img src="https://cdn.vuetifyjs.com/images/logos/v-alt.svg" alt="John">
              </v-list-tile-avatar>
              <v-list-tile-content>
                <v-list-tile-title>Lucas Silva</v-list-tile-title>
                <v-list-tile-sub-title>Desenvolvedor</v-list-tile-sub-title>
              </v-list-tile-content>
            </v-list-tile>
          </v-list>
          <v-divider />
          <v-list>
            <v-list-tile>
              <v-list-tile-action>
                <v-icon>settings</v-icon>
              </v-list-tile-action>
              <v-list-tile-title>Settings</v-list-tile-title>
            </v-list-tile>
            <v-list-tile @click="logout">
              <v-list-tile-action>
                <v-icon>exit_to_app</v-icon>
              </v-list-tile-action>
              <v-list-tile-title>Exit</v-list-tile-title>
            </v-list-tile>
          </v-list>
        </v-card>
      </v-menu>
    </v-toolbar>
    <v-content>
      <v-container fluid fill-height grid-list-md>
        <v-layout>
          <nuxt />
        </v-layout>
      </v-container>
    </v-content>
  </v-app>
</template>

<script>
import md5 from 'blueimp-md5'

export default {
  middleware: 'auth',
  data: () => ({
    dialog: false,
    drawer: null,
    items: [
      { icon: 'dashboard', text: 'Dashboard', to: '/' },
      { icon: 'swap_horiz', text: 'Transactions', to: '/transactions' }
    ]
  }),
  computed: {
    avatarURL() {
      const email = this.$store.state.authUser ? this.$store.state.authUser.email : ''

      return `https://gravatar.com/avatar/${md5(email)}?s=23`
    }
  },
  methods: {
    async logout() {
      await this.$auth.logout()
    }
  }
}
</script>
