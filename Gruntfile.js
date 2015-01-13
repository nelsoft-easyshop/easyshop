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
                    'js/src/vendor/bootstrap.js': 'bootstrap/dist/js/bootstrap.js'
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
            combine_js: {
                files: {
                    'web/assets/js/min/easyshop.header_primary.js': 
                            ['web/assets/js/src/vendor/jquery.scrollUp.min.js',
                             'web/assets/js/src/vendor/jquery.auto-complete.js',
                             'web/assets/js/src/header.js'],

                    'web/assets/js/min/easyshop.footer_full.js': 
                            ['web/assets/js/src/ws.js'],

                    'web/assets/js/min/easyshop.footer.js': 
                            ['web/assets/js/src/ws.js'],

                    'web/assets/js/min/easyshop.header_alt.js': 
                            ['web/assets/js/src/vendor/bootstrap.js',
                             'web/assets/js/src/vendor/jquery.auto-complete.js',
                             'web/assets/js/src/header_alt.js'],

                    'web/assets/js/min/easyshop.vendor_banner.js':
                            ['web/assets/js/src/vendor/jquery.easing.min.js',
                             'web/assets/js/src/vendor/jquery.scrollUp.min.js',
                             'web/assets/js/src/vendor/chosen.jquery.min.js',
                             'web/assets/js/src/vendor/jquery.Jcrop.min.js',
                             'web/assets/js/src/vendor/jquery.simplemodal.js',
                             'web/assets/js/src/vendor/jquery.numeric.js',
                             'web/assets/tinymce/plugins/jbimages/js/jquery.form.js',
                             'web/assets/js/src/vendor_header.js'],

                    'web/assets/js/min/easyshop.layoutF.js':
                            ['web/assets/js/src/feed.js'],

                    'web/assets/js/min/easyshop.countdown.js':
                            ['web/assets/js/src/vendor/jquery.plugin.min.js',
                             'web/assets/js/src/vendor/jquery.countdown.min.js',
                             'web/assets/js/src/promo/countdown-sale.js'],

                    'web/assets/js/min/easyshop.fixeddiscount.js':
                            ['web/assets/js/src/vendor/jquery.plugin.min.js',
                             'web/assets/js/src/vendor/jquery.countdown.min.js',
                             'web/assets/js/src/promo/fixed-discount.js'],

                    'web/assets/js/min/easyshop.genericWithCountdown.js':
                            ['web/assets/js/src/vendor/jquery.plugin.min.js',
                             'web/assets/js/src/vendor/jquery.countdown.min.js',
                             'web/assets/js/src/promo/generic-with-countdown.js'],

                    'web/assets/js/min/easyshop.partial_sliderpreview.js':
                            ['web/assets/js/src/vendor/jquery.bxslider1.min.js',
                             'web/assets/js/src/vendor/owl.carousel.min.js',
                             'web/assets/js/src/newhome.js'],

                    'web/assets/js/min/easyshop.partial_userdetails.js':
                            ['web/assets/js/src/usercontact.js',
                             'web/assets/js/src/vendor/jquery.numeric.js'],

                    'web/assets/js/min/easyshop.cart-responsive.js':
                            ['web/assets/js/src/vendor/numeral.min.js',
                             'web/assets/js/src/vendor/jquery.simplemodal.js',
                             'web/assets/js/src/cart.js'],

                    'web/assets/js/min/easyshop.home_primary.js':
                            ['web/assets/js/src/vendor/jquery.bxslider1.min.js',
                             'web/assets/js/src/vendor/owl.carousel.min.js',
                             'web/assets/js/src/newhome.js'],
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-bowercopy');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-uglify');

    grunt.registerTask('default', ['bowercopy', 'cssmin', 'uglify']);
};

