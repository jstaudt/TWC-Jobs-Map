//browserify entry point
var Vue = require('vue');
var VueResource = require('vue-resource');
Vue.use(VueResource);
Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('content');

import jobs from './components/jobs.vue';
import providers from './components/providers.vue';

var mixin = {
    created: function () {
        this.hello()
    },
    methods: {
        hello: function () {
            console.log('hello from mixin!')
        }
    }
}
var jobsApp = window.jobsApp = new Vue({
    el: 'body',

    mixins: [mixin],

    components: {jobs,providers},

    methods: {
        initMap: function() {
            this.$broadcast('googleMapsLoaded');
        }
    }
})