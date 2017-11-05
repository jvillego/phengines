<div id="app">
<template>
  <v-app id="inspire"  >
    <v-navigation-drawer persistent v-model="drawer" enable-resize-watcher app>
      <v-list dense>
        <v-list-tile @click="">
          <v-list-tile-action>
            <v-icon>home</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>Home</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
        <v-list-tile @click="">
          <v-list-tile-action>
            <v-icon>contact_phone</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>Contacts</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
        <v-list-tile @click="">
          <v-list-tile-action>
            <v-icon>settings</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>Settings</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
      </v-list>
    </v-navigation-drawer>
    <v-toolbar color="indigo" dark fixed app>
      <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
      <v-toolbar-title><?php echo Engine::getAppName();?></v-toolbar-title>
    </v-toolbar>
    <main>
      <v-content>
        <v-container fluid fill-height>
          <v-layout justify-center align-center >
            <v-tooltip right>
              <v-btn icon large:href="" target="_blank" slot="activator">
                <v-icon large>home</v-icon>
              </v-btn>
              <span>Main page</span>
            </v-tooltip>
          </v-layout>
        </v-container>
      </v-content>
    </main>
    <v-footer color="indigo" app>
      <span class="white--text">&copy; 2017</span>
    </v-footer>
  </v-app>
</template>
    
</div>