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
                    'js/src/vendor/socket.io.js': 'socket.io-client/socket.io.js'
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
            combine_css:{
                files:{
                    'web/assets/css/min-easyshop.sliderpreview.css':
                        ['web/assets/css/jquery.bxslider2.css',
                         'web/assets/css/new-homepage.css'],
                    'web/assets/css/min-easyshop.feeds.css':
                        ['web/assets/css/bootstrap.css', 
                        'web/assets/css/bootstrap-mods.css',
                        'web/assets/css/style.css',
                        'web/assets/css/responsive_css.css'],
                    'web/assets/css/min-easyshop.header-alt.css':
                        ['web/assets/css/main-style.css', 
                        'web/assets/css/bootstrap.css',
                        'web/assets/css/easy-icons/easy-icons.css',
                        'web/assets/css/bootstrap-mods.css',
                        'web/assets/css/font-awesome/css/font-awesome.min.css'],
                    'web/assets/css/min-easyshop.header-primary.css':
                        ['web/assets/css/main-style.css',
                         'web/assets/css/normalize.min.css',
                         'web/assets/css/header-css.css',
                         'web/assets/css/bootstrap.css',
                         'web/assets/css/responsive_css.css',
                         'web/assets/css/new-homepage.css'],
                    'web/assets/css/min-easyshop.vendor-banner.css':
                        ['web/assets/css/chosen.min.css', 
                        'web/assets/css/jquery.Jcrop.min.css'],
                    'web/assets/css/min-easyshop.cart.css':
                        ['web/assets/css/bootstrap.css',
                        'web/assets/css/my_cart_css.css', 
                        'web/assets/css/bootstrap-mods.css'],
                    'web/assets/css/min-easyshop.home-primary.css':
                        ['web/assets/css/jquery.bxslider2.css',
                        'web/assets/css/new-homepage.css', 
                        'web/assets/css/owl.carousel.css'],
                    'web/assets/css/min-easyshop.global-includes.css':
                        ['web/assets/css/style.css',
                        'web/assets/css/responsive_css.css', 
                        'web/assets/css/basic.css.css'],
                    'web/assets/css/min-easyshop.payment.css':
                        ['web/assets/css/bootstrap.css',
                        'web/assets/css/my_cart_css.css', 
                        'web/assets/css/payment_review.css',
                        'web/assets/css/bootstrap-mods.css'],
                    'web/assets/css/min-easyshop.product-promo-category.css':
                        ['web/assets/css/product_search_category.css',
                        'web/assets/css/style_new.css', 
                        'web/assets/css/jquery.bxslider.css.css'],
                    'web/assets/css/min-easyshop.product-search-by-category-final-responsive.css':
                        ['web/assets/css/bootstrap.css',
                        'web/assets/css/bootstrap-mods.css', 
                        'web/assets/css/product_search_category.css',
                        'web/assets/css/product_search_category_responsive.css', 
                        'web/assets/css/product_search_category.css',   
                        'web/assets/css/style_new.css',
                        'web/assets/css/jquery.bxslider.css',
                        'web/assets/css/jcarousel.css'],
                    'web/assets/css/min-easyshop.upload-step1.css':
                        ['web/assets/css/sell_item.css',
                        'web/assets/css/bootstrap.css', 
                        'web/assets/css/bootstrap-mods.css'],
                    'web/assets/css/min-easyshop.upload-step2.css':
                        ['web/assets/css/sell_item.css',
                        'web/assets/css/ion.rangeSlider.css', 
                        'web/assets/css/ion.rangeSlider.skinFlat.css',
                        'web/assets/css/bootstrap.css',
                        'web/assets/css/bootstrap-mods.css',
                        'web/assets/css/jquery.Jcrop.min.css'], 
                     
                    'web/assets/css/min-easyshop.upload-step3.css':
                        ['web/assets/css/bootstrap.css',
                         'web/assets/css/bootstrap-mods.css',
                         'web/assets/css/product_preview.css',
                         'web/assets/css/jquery.bxslider.css',
                         'web/assets/css/product_upload_tutorial.css',
                         'web/assets/css/responsive_css.css',
                         'web/assets/css/chosen.min.css'],  
                    'web/assets/css/min-easyshop.upload-step4.css':
                        ['web/assets/css/sell_item.css',
                         'web/assets/css/product_preview.css',
                         'web/assets/css/bootstrap.css',
                         'web/assets/css/bootstrap-mods.css'],
                    'web/assets/css/min-easyshop.upload-step4-preview.css':
                        ['web/assets/css/product-page-css.css',
                         'web/assets/css/step4-product-preview-css.css'],                  
                    'web/assets/css/min-easyshop.product-search-by-category.css':
                        ['web/assets/css/font-awesome/css/font-awesome.css',
                         'web/assets/css/easy-icons/easy-icons.css',
                         'web/assets/css/owl.carousel.css',
                         'web/assets/css/jquery.bxslider.css',
                         'web/assets/css/product-search-new.css'],
                    'web/assets/css/min-easyshop.product-image-gallery.css':
                        ['web/assets/css/jquery.jqzoom.css'],
                    'web/assets/css/min-easyshop.productpage-primary.css':
                        ['web/assets/css/product-page-css.css',
                         'web/assets/css/jquery.jqzoom.css',
                         'web/assets/css/owl.carousel.css'] 
                }
            },
        }
    });

    grunt.loadNpmTasks('grunt-bowercopy');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-uglify');

    grunt.registerTask('default', ['bowercopy', 'cssmin', 'uglify']);
};

