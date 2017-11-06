var Home = Vue.component('home-page', {
    template: '#home',
    data: function(){
        return {
            message: "Message from component variable"
        };
    },
    methods: {
        change: function(){
            this.message = "Message was changed!";
        },
        restore: function(){
            this.message = "Message was restored!";
        },
    }
});
var Contacts = Vue.component('contacts-page', {
    template: '#contacts',
    data: function(){
        return {
            message: 'HEllo world!'
        };
    }
});

var routes = [
    {
        path: '/home',
        name: 'home',
        component: Home
    },
    {
        path: '/contacts',
        name: 'contacts',
        component: Contacts
    },
    {
        path: '*',
        redirect: {
            name: 'home'
        }
    }
];

var router = new VueRouter({
    routes: routes,
    root: '/home'
});

new Vue({
    el: '#app',
    router: router,
    data:{
        drawer: true
    }
});

