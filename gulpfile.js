var elixir = require('laravel-elixir');

require('laravel-elixir-vueify');

//workaround for gulp -watch bug
elixir.config.js.browserify.watchify = {
    enabled: true,
    options: {
        poll: true
    }
};

elixir(function(mix) {
    mix.sass('app.scss');
    mix.copy('node_modules/bootstrap-sass/assets/fonts/bootstrap/','public/fonts/bootstrap'); 
    mix.browserify('main.js');
});
