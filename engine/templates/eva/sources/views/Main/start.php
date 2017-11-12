<div id="app">
<template>
  <v-app id="inspire"  >
    <v-navigation-drawer clipped persistent v-model="drawer" close-on-click="true" enable-resize-watcher app>
      <v-list dense>
          <v-list-tile>
                <v-list-tile-content>
                    <v-list-tile-title >
                        <span>Menu</span>
                    </v-list-tile-title>
                </v-list-tile-content>
            </v-list-tile>
        <v-divider></v-divider>
        <v-list-tile href="#/home" >
          <v-list-tile-action>
            <v-icon>home</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>Home</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
        <v-list-tile  href="#/contacts">
          <v-list-tile-action>
            <v-icon>contact_phone</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>Contacts</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
      </v-list>
    </v-navigation-drawer>
    <v-toolbar color="indigo" dark fixed app>
      <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
      <v-toolbar-title>EnVuetifyApp</v-toolbar-title>
    </v-toolbar>
    <main>
      <v-content>
            <v-fade-transition mode="out-in">
                <router-view></router-view>
            </v-fade-transition>
      </v-content>
    </main>
    <v-footer color="indigo" app fixed>
      <span class="white--text">&copy; 2017</span>
    </v-footer>
  </v-app>
</template>
    
</div>

<!-- ///////////// PAGES ////////////////-->

<script type="text/x-template" id="home">
    <v-container>
        <h3>Home</h3>
        <v-layout row wrap>
            <v-flex xs12 >
              <v-card>
                <v-card-media src="https://vuetifyjs.com/static/doc-images/cards/desert.jpg" height="200px">
                </v-card-media>
                <v-card-title primary-title>
                  <div>
                    <h3 class="headline mb-0">Kangaroo Valley Safari</h3>
                    <div>Located two hours south of Sydney in the <br>Southern Highlands of New South Wales, ...</div>
                    <div>{{message}}</div>
                  </div>
                </v-card-title>
                <v-card-actions>
                  <v-btn flat color="orange" @click="change()">Change</v-btn>
                  <v-btn flat color="orange" @click="restore()">Restore</v-btn>
                </v-card-actions>
              </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</script>

<script type="text/x-template" id="contacts">
    <v-layout row>
        <v-flex xs12>
            <v-list>
                <v-list-tile avatar @click="">
                    <v-list-tile-avatar>
                        <img src="https://randomuser.me/api/portraits/men/78.jpg">
                    </v-list-tile-avatar>
                    <v-list-tile-content>
                        <v-list-tile-title>Gordon Newman</v-list-tile-title>
                        <v-list-tile-sub-title>Lorem ipsum dolor sit amet</v-list-tile-sub-title>
                    </v-list-tile-content>
                </v-list-tile>
                <v-list-tile avatar @click="">
                    <v-list-tile-avatar>
                        <img src="https://randomuser.me/api/portraits/women/10.jpg">
                    </v-list-tile-avatar>
                    <v-list-tile-content>
                        <v-list-tile-title>Felecia Ruiz</v-list-tile-title>
                        <v-list-tile-sub-title>Lorem ipsum dolor sit amet</v-list-tile-sub-title>
                    </v-list-tile-content>
                </v-list-tile>
                <v-list-tile avatar @click="">
                    <v-list-tile-avatar>
                        <img src="https://randomuser.me/api/portraits/men/17.jpg">
                    </v-list-tile-avatar>
                    <v-list-tile-content>
                        <v-list-tile-title>Gregory Riley</v-list-tile-title>
                        <v-list-tile-sub-title>Lorem ipsum dolor sit amet</v-list-tile-sub-title>
                    </v-list-tile-content>
                </v-list-tile>
                <v-list-tile avatar @click="">
                    <v-list-tile-avatar>
                        <img src="https://randomuser.me/api/portraits/women/96.jpg">
                    </v-list-tile-avatar>
                    <v-list-tile-content>
                        <v-list-tile-title>Lisa Vargas</v-list-tile-title>
                        <v-list-tile-sub-title>Lorem ipsum dolor sit amet</v-list-tile-sub-title>
                    </v-list-tile-content>
                </v-list-tile>
            </v-list>
        </v-flex>
    </v-layout>
</script> 
