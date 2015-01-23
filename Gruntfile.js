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
                    'js/src/socket.io.js': 'socket.io-client/socket.io.js'
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

                    'web/assets/js/min/easyshop.inbox_view.js':
                            ['web/assets/js/src/messaging.js',
                            'web/assets/js/src/node_modules/socket.io/node_modules/socket.io-client/socket.io.js'],

                    'web/assets/js/min/easyshop.payment_review_responsive.js':
                            ['web/assets/js/src/vendor/jquery.idTabs.min.js',
                             'web/assets/js/src/payment.js',
                             'web/assets/js/src/vendor/bootstrap.js',
                             'web/assets/js/src/vendor/jquery.numeric.js'],

                    'web/assets/js/min/easyshop.product_image_gallery.js':
                            ['web/assets/js/src/vendor/jquery.jcarousel.min.js',
                             'web/assets/js/src/product-page-image-gallery.js'],

                    'web/assets/js/min/easyshop.product_promo_category.js':
                            ['web/assets/js/src/vendor/jquery.plugin.min.js',
                             'web/assets/js/src/vendor/jquery.countdown.min.js'],

                    'web/assets/js/min/easyshop.product_search_by_category_final_responsive.js':
                            ['web/assets/js/src/vendor/bootstrap.js',
                             'web/assets/js/src/vendor/jquery.easing.min.js',
                             'web/assets/js/src/vendor/jquery.scrollUp.min.js',
                             'web/assets/js/src/vendor/jquery.bxslider.min.js',
                             'web/assets/js/src/categorynavigation.js',
                             'web/assets/js/src/advsearch.js'],

                    'web/assets/js/min/easyshop.product_upload_step1_view.js':
                            ['web/assets/js/src/productUpload_step1.js',
                             'web/assets/js/src/vendor/jquery.simplemodal.js'],

                    'web/assets/js/min/easyshop.product_upload_step2_view.js':
                            ['web/assets/js/src/vendor/ion.rangeSlider.min.js',
                             'web/assets/js/src/vendor/chosenwithcreate.jquery.min.js',
                             'web/assets/js/src/vendor/jquery.Jcrop.min.js',
                             'web/assets/js/src/vendor/jquery.simplemodal.js',
                             'web/assets/js/src/productUpload_step2.js',
                             'web/assets/tinymce/plugins/jbimages/js/jquery.form.js',
                             'web/assets/js/src/vendor/jquery.validate.js'],

                    'web/assets/js/min/easyshop.product_upload_step3_view.js':
                            ['web/assets/js/src/vendor/jquery.idTabs.min.js',
                             'web/assets/js/src/productUpload_step3.js',
                             'web/assets/js/src/vendor/jquery.numeric.js',
                             'web/assets/js/src/vendor/jquery-ui.js',
                             'web/assets/js/src/vendor/jquery.jqpagination.min.js',
                             'web/assets/js/src/vendor/jquery.simplemodal.js',
                             'web/assets/js/src/vendor/chosen.jquery.min.js'],

                    'web/assets/js/min/easyshop.productpage_primary.js':
                            ['web/assets/js/src/vendor/jquery.jqzoom-core.js',
                             'web/assets/js/src/vendor/jquery.bxslider1.min.js',
                             'web/assets/js/src/vendor/jquery.numeric.js',
                             'web/assets/js/src/vendor/owl.carousel.min.js',
                             'web/assets/js/src/vendor/bootstrap.js',
                             'web/assets/js/src/product-page.js',
                             'web/assets/js/src/social_media_share.js'],

                    'web/assets/js/min/easyshop.christmas-promo.js':
                            ['web/assets/js/src/vendor/jquery-1.9.1.js',
                             'web/assets/js/src/plugins.js',
                             'web/assets/js/src/christmas-promo.js',
                             'web/assets/js/src/vendor/modernizr-2.6.2.min.js',
                             'web/assets/js/src/vendor/jquery.plugin.min.js',
                             'web/assets/js/src/vendor/jquery.countdown.min.js',
                             'web/assets/js/src/promo/countdown-sale.js'],

                    'web/assets/js/min/easyshop.scratch_to_win.js':
                            ['web/assets/js/src/vendor/jquery.plugin.min.js',
                             'web/assets/js/src/vendor/jquery.simplemodal.js',
                             'web/assets/js/src/vendor/jquery.plugin.min.js',
                             'web/assets/js/src/scratchwinpromo.js'],

                    'web/assets/js/min/easyshop.advance_search_main.js':
                            ['web/assets/js/src/vendor/bootstrap.js',
                             'web/assets/js/src/vendor/jquery.easing.min.js',
                             'web/assets/js/src/vendor/jquery.scrollUp.min.js',
                             'web/assets/js/src/advsearch.js'],

                    'web/assets/js/min/easyshop.product_search_by_searchbox.js':
                            ['web/assets/js/src/vendor/bootstrap.js',
                             'web/assets/js/src/vendor/jquery.easing.min.js',
                             'web/assets/js/src/vendor/jquery.scrollUp.min.js',
                             'web/assets/js/src/advsearch.js'],

                    'web/assets/js/min/easyshop.dashboard-primary.js':
                            ['web/assets/js/src/vendor/jquery-1.9.1.js',
                             'web/assets/js/src/vendor/jquery-ui.js',
                             'web/assets/js/src/vendor/jquery.validate.js',
                             'web/assets/js/src/vendor/jquery.raty.min.js',
                             'web/assets/js/src/vendor/image.js',
                             'web/assets/js/src/vendor/jquery.idTabs.min.js',
                             'web/assets/js/src/vendor/jquery.idTabs.dashboard.home.js',
                             'web/assets/js/src/vendor/chosen.jquery.min.js',
                             'web/assets/js/src/vendor/jquery.sortable.js',
                             'web/assets/js/src/vendor/jquery.simplemodal.js',
                             'web/assets/js/src/vendor/jquery.numeric.js',
                             'web/assets/js/src/vendor/pwstrength.js',
                             'web/assets/js/src/dashboard.js',
                             'web/assets/js/src/dashboard-myaccount.js'],

                    'web/assets/js/min/easyshop.product_search_by_searchbox.js':
                            ['web/assets/js/src/vendor/bootstrap.js',
                             'web/assets/js/src/vendor/jquery.easing.min.js',
                             'web/assets/js/src/vendor/jquery.scrollUp.min.js',
                             'web/assets/js/src/advsearch.js'],

                    'web/assets/js/min/easyshop.MemberPageAccountActivate.js':
                            ['web/assets/js/src/vendor/jquery-1.9.1.js',
                             'web/assets/js/src/vendor/jquery.validate.js',
                             'web/assets/js/src/vendor/jquery.simplemodal.js',
                             'web/assets/js/src/accountactivation.js'],

                    'web/assets/js/min/easyshop.SocialMediaMerge.js':
                            ['web/assets/js/src/SocialMediaMerge.js',
                             'web/assets/js/src/vendor/jquery.simplemodal.js'],

                    'web/assets/js/min/easyshop.SocialMediaRegistration.js':
                            ['web/assets/js/src/SocialMediaRegistration.js',
                             'web/assets/js/src/vendor/jquery.simplemodal.js'],

                    'web/assets/js/min/easyshop.user_about.js':
                            ['web/assets/js/src/vendor/jquery.easing.min.js',
                             'web/assets/js/src/vendor/jquery.scrollUp.min.js',
                             'web/assets/js/src/userabout.js'],

                    'web/assets/js/min/easyshop.user_contact.js':
                            ['web/assets/js/src/vendorpage_contact.js'],

                    'web/assets/js/min/easyshop.user_follower.js':
                            ['web/assets/js/src/vendor/jquery.scrollTo.js',
                             'web/assets/js/src/vendorpage_followers.js'],

                    'web/assets/js/min/easyshop.user_forgotpass.js':
                            ['web/assets/js/src/vendor/jquery.numeric.js',
                             'web/assets/js/src/vendor/jquery.validate.js',
                             'web/assets/js/src/register.js'],

                    'web/assets/js/min/easyshop.user_forgotpass_confirm.js':
                            ['web/assets/js/src/vendor/mootools-core-1.4.5-full-compat.js',
                             'web/assets/js/src/vendor/jquery.numeric.js',
                             'web/assets/js/src/vendor/jquery.validate.js',
                             'web/assets/js/src/register.js',
                             'web/assets/js/src/forgotpassword.js'],

                    'web/assets/js/min/easyshop.user_login_view.js':
                            ['web/assets/js/src/vendor/jquery.validate.js',
                             'web/assets/js/src/login.js'],

                    'web/assets/js/min/easyshop.user_register.js':
                            ['web/assets/js/src/vendor/jquery-1.9.1.js',
                             'web/assets/js/src/vendor/jquery-ui.js',
                             'web/assets/js/src/vendor/jquery.numeric.js',
                             'web/assets/js/src/vendor/jquery.validate.js',
                             'web/assets/js/src/vendor/jquery.bxslider.min.js',
                             'web/assets/js/src/vendor/mootools-core-1.4.5-full-compat.js',
                             'web/assets/js/src/vendor/password_meter.js',
                             'web/assets/js/src/landingpage-responsive-nav.js',
                             'web/assets/js/src/landingpage.js'],

                    'web/assets/js/min/easyshop.user_register_subscribe_success.js':
                            ['web/assets/js/src/vendor/jquery-1.9.1.js',
                             'web/assets/js/src/vendor/jquery-ui.js',
                             'web/assets/js/src/landingpage-bootstrap.min.js'],

                    'web/assets/js/min/easyshop.user_vendor_view.js':
                            ['web/assets/js/src/vendorpage_new.js',
                             'web/assets/js/src/vendor/bootstrap.js',
                             'web/assets/js/src/vendor/jquery.Jcrop.min.js',
                             'web/assets/js/src/vendor/jquery.simplemodal.js',
                             'web/assets/js/src/vendor/jquery.scrollTo.js',
                             'web/assets/js/src/vendor/chosen.jquery.min.js'],
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-bowercopy');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-uglify');

    grunt.registerTask('default', ['bowercopy', 'cssmin', 'uglify']);
};

