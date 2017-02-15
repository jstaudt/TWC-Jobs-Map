//browserify entry point
var Vue = require('vue');
var VueResource = require('vue-resource');

//necessary to pull in vue-resource
//window.Vue = Vue;
Vue.use(VueResource);
Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('content');

import drops from './components/drops.vue';

var jobsApp = window.jobsApp = new Vue({
    el: 'body',

    components: {drops},

    data: {
        test: "5151"
    },

    methods: {
        initMap: function() {
            this.$broadcast('googleMapsLoaded');
        }
    }
})