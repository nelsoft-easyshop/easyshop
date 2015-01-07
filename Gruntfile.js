module.exports = function (grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        bowercopy: {
            options: {
                srcPrefix: 'bower_components',
                destPrefix: 'web/assets'
            },
            scripts: {
                files: {
                    'js/src/vendor/jquery.js': 'jquery/jquery.js',
                    'js/src/vendor/bootstrap.js': 'bootstrap/dist/js/bootstrap.js',
                    'js/src/vendor/bootstrap-typeahead.min.js': 'bs-typeahead/js/bootstrap-typeahead.min.js'
                }
            },
            stylesheets: {
                files: {
                    'css/bootstrap.css': 'bootstrap/dist/css/bootstrap.css'
                }
            },
        },
        cssmin : {
            bootstrap:{
                src: 'web/assets/css/bootstrap.css',
                dest: 'web/assets/css/bootstrap.min.css'
            },
        },
        uglify : {
            js: {
                cwd: 'web/assets/js/src',
                src: '**/*.js',
                dest: 'web/assets/js/min/', 
                expand: true, 
                flatten: false
            }
        }        
    });

    grunt.loadNpmTasks('grunt-bowercopy');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-uglify');

    grunt.registerTask('default', ['bowercopy', 'cssmin', 'uglify']);
};

