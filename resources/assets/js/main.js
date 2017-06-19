//browserify entry point
var Vue = require('vue');
var VueResource = require('vue-resource');
Vue.use(VueResource);
Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('content');

import jobs from './components/jobs.vue';
import home from './components/home.vue';
import providers from './components/providers.vue';

var jobsApp = window.jobsApp = new Vue({
    el: 'body',

    components: {jobs,providers,home},

    methods: {
        initMap: function() {
            this.$broadcast('googleMapsLoaded');
        }
    }
})