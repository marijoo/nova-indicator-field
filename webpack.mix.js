let mix = require('laravel-mix')
let path = require('path')

mix.js('resources/js/field.js', 'dist/js')
    .alias({
        'laravel-nova': path.join(__dirname, '../../vendor/laravel/nova/resources/js/mixins/packages.js'),
        'laravel-nova-performs-searches': path.join(__dirname, '../../vendor/laravel/nova/resources/js/mixins/PerformsSearches.js'),
    })
    .vue({version: 3})
    .webpackConfig({
        externals: {
            vue: 'Vue',
        },
        output: {
            uniqueName: 'vendor/package',
        }
    })
    .sass('resources/sass/field.scss', 'dist/css')
    .webpackConfig({
        resolve: {
            symlinks: false
        }
    })
