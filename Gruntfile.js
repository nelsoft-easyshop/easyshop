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
                    'js/src/vendor/socket.io.js': 'socket.io-client/socket.io.js',
                    'js/src/vendor/jquery.cropper.js': 'cropper/dist/cropper.js'
                }
            },
            stylesheets: {
                files: {
                    'css/bootstrap.css': 'bootstrap/dist/css/bootstrap.css',
                    'css/jquery.cropper.css': 'cropper/dist/cropper.min.css'
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
                        'web/assets/css/bootstrap-mods.css'],
                     'web/assets/css/min-easyshop.header-alt2.css':
                        ['web/assets/css/normalize.min.css',
                        'web/assets/css/simple-header-css.css',
                        'web/assets/css/footer-css.css',
                        'web/assets/css/bootstrap.css'
                        ],
                    'web/assets/css/min-easyshop.header-primary.css':
                        ['web/assets/css/main-style.css',
                         'web/assets/css/normalize.min.css',
                         'web/assets/css/header-css.css',
                         'web/assets/css/bootstrap.css',
                         'web/assets/css/responsive_css.css',
                         'web/assets/css/new-homepage.css',
                         'web/assets/css/footer-css.css'],
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
                        'web/assets/css/basic.css'],
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
                        'web/assets/css/jquery.cropper.css',
                        'web/assets/css/chosenwtihcreate.min.css'], 
                     
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
                        ['web/assets/css/owl.carousel.css',
                         'web/assets/css/jquery.bxslider.css',
                         'web/assets/css/product-search-new.css'],
                    'web/assets/css/min-easyshop.product-image-gallery.css':
                        ['web/assets/css/jquery.jqzoom.css'],
                    'web/assets/css/min-easyshop.productpage-primary.css':
                        ['web/assets/css/product-page-css.css',
                         'web/assets/css/jquery.jqzoom.css',
                         'web/assets/css/owl.carousel.css'],
                    'web/assets/css/min-easyshop.christmas-promo.css':
                        ['web/assets/css/promo-css.css'],
                    'web/assets/css/min-easyshop.post-and-win.css':
                        ['web/assets/css/style.css'],
                    'web/assets/css/min-easyshop.scratch-to-win.css':
                        ['web/assets/css/jquery.bxslider.css',
                         'web/assets/css/promo.css',   
                         'web/assets/css/bootstrap.css'],
                    'web/assets/css/min-easyshop.advance-search-main.css':
                        ['web/assets/css/product_search_category.css', 
                         'web/assets/css/product_search_category_responsive.css', 
                         'web/assets/css/product_advance_search.css', 
                         'web/assets/css/style_new.css'],
                    'web/assets/css/min-easyshop.product-search-new.css':
                        ['web/assets/css/product-search-new.css'],
                    'web/assets/css/min-easyshop.product-search-by-searchbox.css':
                        ['web/assets/css/bootstrap.css',
                         'web/assets/css/bootstrap-mods.css',
                         'web/assets/css/product_search_category.css',
                         'web/assets/css/product_search_category_responsive.css',
                         'web/assets/css/style_new.css'],
                    'web/assets/css/min-easyshop.user-about.css':
                        ['web/assets/css/contact.css'],
                    'web/assets/css/min-easyshop.user-followers.css':
                        ['web/assets/css/contact.css',
                         'web/assets/css/followers.css'],
                    'web/assets/css/min-easyshop.login.css':
                        ['web/assets/css/bootstrap.css',
                        'web/assets/css/bootstrap-mods.css'],
                    'web/assets/css/min-easyshop.member-account-activate.css':
                        ['web/assets/css/new-dashboard.css'],
                    'web/assets/css/min-easyshop.register.css':
                        ['web/assets/css/bootstrap.css',
                         'web/assets/css/new-login-register.css',
                         'web/assets/css/basic.css',
                         'web/assets/css/responsive_css.css',
                         'web/assets/css/main-style.css'],
                    'web/assets/css/min-easyshop.register-success.css':
                        ['web/assets/css/landingpage/bootstrap.css',
                         'web/assets/css/landingpage/bootstrap-responsive.css',
                         'web/assets/css/landingpage/mystyle.css',
                         'web/assets/css/jquery-ui.css'],
                    'web/assets/css/min-easyshop.social-media-merge.css':
                        ['web/assets/css/main-style.css',
                         'web/assets/css/new-login.css'],
                    'web/assets/css/min-easyshop.vendorview.css':
                        ['web/assets/css/vendorview.css'],
                    'web/assets/css/min-easyshop.how-to.css':
                        ['web/assets/css/how-to-page.css'],
                    'web/assets/css/min-easyshop.dashboard-personal-info.css':
                        ['web/assets/css/jquery-ui.css',
                         'web/assets/css/jquery-ui.theme.min.css'],
                    'web/assets/css/min-easyshop.dashboard-primary.css':
                        ['web/assets/css/jstree/style.css',
                         'web/assets/css/contact.css',
                         'web/assets/css/chosen.min.css', 
                         'web/assets/css/new-dashboard.css'],
                    'web/assets/css/min-easyshop.dashboard-qr-code.css':
                        ['web/assets/css/bootstrap.css',
                         'web/assets/css/qr-code-css.css',
                         'web/assets/css/qr-code-print.css'],
                    'web/assets/css/min-easyshop.home-reminder.css':
                        ['web/assets/css/basic.css',
                         'web/assets/css/message-box.css'],
                    'web/assets/css/min-easyshop.widget-selector.css':
                        ['web/assets/css/how-to-page.css',
                         'web/assets/css/widget-selector.css'],
                    'web/assets/css/min-easyshop.widget-page.css':
                        ['web/assets/css/bootstrap.css',
                         'web/assets/css/widget.css']
                }
            },
        },
        uglify : {
            combine_js: {
                files: {
                    'web/assets/js/min/easyshop.header_primary.js': 
                            ['web/assets/js/src/vendor/jquery.scrollUp.min.js',
                             'web/assets/js/src/vendor/jquery.auto-complete.js',
                             'web/assets/js/src/header.js'],
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
                             'web/assets/js/src/vendor/jquery.hilios.countdown.js',
                             'web/assets/js/src/promo/countdown-sale.js'],

                    'web/assets/js/min/easyshop.fixeddiscount.js':
                            ['web/assets/js/src/vendor/jquery.plugin.min.js',
                             'web/assets/js/src/vendor/jquery.hilios.countdown.js',
                             'web/assets/js/src/promo/fixed-discount.js'],

                    'web/assets/js/min/easyshop.genericWithCountdown.js':
                            ['web/assets/js/src/vendor/jquery.plugin.min.js',
                             'web/assets/js/src/vendor/jquery.hilios.countdown.js',
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
                            ['web/assets/js/src/messaging.js'],

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
                             'web/assets/js/src/vendor/jquery.simplemodal.js',
                             'web/assets/js/src/productUpload_step2.js',
                             'web/assets/tinymce/plugins/jbimages/js/jquery.form.js',
                             'web/assets/js/src/vendor/jquery.validate.js', 
                             'web/assets/js/src/vendor/jquery.cropper.js'],

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
                             'web/assets/js/src/promo/christmas-promo.js',
                             'web/assets/js/src/vendor/modernizr-2.6.2.min.js',
                             'web/assets/js/src/vendor/jquery.plugin.min.js',
                             'web/assets/js/src/vendor/jquery.countdown.min.js',
                             'web/assets/js/src/promo/countdown-sale.js'],

                    'web/assets/js/min/easyshop.scratch_to_win.js':
                            ['web/assets/js/src/vendor/jquery.plugin.min.js',
                             'web/assets/js/src/vendor/jquery.simplemodal.js',
                             'web/assets/js/src/vendor/jquery.plugin.min.js',
                             'web/assets/js/src/promo/scratchwinpromo.js'],

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
                             'web/assets/js/src/vendor/jquery.ui.touch-punch.min.js',
                             'web/assets/js/src/vendor/jquery.validate.js',
                             'web/assets/js/src/vendor/jquery.raty.min.js',
                             'web/assets/js/src/vendor/image.js',
                             'web/assets/js/src/vendor/jquery.idTabs.min.js',
                             'web/assets/js/src/vendor/jquery.idTabs.dashboard.home.js',
                             'web/assets/js/src/vendor/chosen.jquery.min.js',
                             'web/assets/js/src/vendor/jquery.simplemodal.js',
                             'web/assets/js/src/vendor/jquery.numeric.js',
                             'web/assets/js/src/vendor/jstree.js',
                             'web/assets/js/src/vendor/pwstrength.js',
                             'web/assets/js/src/dashboard.js',
                             'web/assets/js/src/dashboard-myaccount.js',
                             'web/assets/js/src/dashboard-express-edit.js'],

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
                            ['web/assets/js/src/forgotpassword.js'],

                    'web/assets/js/min/easyshop.user_forgotpass_update_password.js':
                            ['web/assets/js/src/vendor/pwstrength.js',
                             'web/assets/js/src/vendor/jquery.numeric.js',
                             'web/assets/js/src/vendor/jquery.validate.js',
                             'web/assets/js/src/forgotpassword.js'],

                    'web/assets/js/min/easyshop.user_login_view.js':
                            ['web/assets/js/src/vendor/jquery.validate.js',
                             'web/assets/js/src/login.js'],

                    'web/assets/js/min/easyshop.user_register.js':
                            ['web/assets/js/src/vendor/jquery-1.9.1.js',
                             'web/assets/js/src/vendor/jquery.cookie.js',
                             'web/assets/js/src/vendor/jquery.idTabs.min.js',
                             'web/assets/js/src/vendor/jquery-ui.js',
                             'web/assets/js/src/vendor/jquery.numeric.js',
                             'web/assets/js/src/vendor/jquery.validate.js',
                             'web/assets/js/src/vendor/pwstrength.js',
                             'web/assets/js/src/universal.js',
                             'web/assets/js/src/register.js',
                             'web/assets/js/src/login.js'],
                    'web/assets/js/min/easyshop.user_register_subscribe_success.js':
                            ['web/assets/js/src/vendor/jquery-1.9.1.js',
                             'web/assets/js/src/vendor/jquery-ui.js',
                             'web/assets/js/src/landingpage-bootstrap.min.js'],

                    'web/assets/js/min/easyshop.user_vendor_view.js':
                            ['web/assets/js/src/vendor/bootstrap.js',
                             'web/assets/js/src/vendor/jquery.Jcrop.min.js',
                             'web/assets/js/src/vendor/jquery.simplemodal.js',
                             'web/assets/js/src/vendor/jquery.scrollTo.js',
                             'web/assets/js/src/vendor/chosen.jquery.min.js',
                             'web/assets/js/src/easyshop.simplePagination.js',
                             'web/assets/js/src/vendorpage_new.js'],

                    'web/assets/js/min/easyshop.product-search-by-category-new.js':
                            ['web/assets/js/src/vendor/bootstrap.js',
                             'web/assets/js/src/vendor/jquery.simplemodal.js',
                             'web/assets/js/src/vendor/jquery.sticky-sidebar-scroll.js',
                             'web/assets/js/src/vendor/owl.carousel.min.js',
                             'web/assets/js/src/vendor/jquery.bxslider.min.js',
                             'web/assets/js/src/easyshop.simplePagination.js',
                             'web/assets/js/src/product-search-by-category.js',
                             'web/assets/js/src/product-search.js'],

                    'web/assets/js/min/easyshop.product-search-new.js':
                            ['web/assets/js/src/vendor/bootstrap.js',
                             'web/assets/js/src/vendor/jquery.simplemodal.js',
                             'web/assets/js/src/vendor/jquery.sticky-sidebar-scroll.js', 
                             'web/assets/js/src/easyshop.simplePagination.js', 
                             'web/assets/js/src/product-search.js'],

                    'web/assets/js/min/easyshop.includes.js':
                            ['web/assets/js/src/vendor/jquery-1.9.1.js',
                             'web/assets/js/src/landingpage-responsive-nav.js',
                             'web/assets/js/src/vendor/jquery-ui.js',
                             'web/assets/js/src/vendor/jquery.cookie.js',
                             'web/assets/js/src/lib/websocket/client.js',
                             'web/assets/js/src/lib/eventdispatcher.js',
                             'web/assets/js/src/nodeClient.js',
                             'web/assets/js/src/universal.js'],

                    'web/assets/js/min/easyshop.estudyantrepreneur-promo.js':
                        ['web/assets/js/src/vendor/jquery-1.9.1.js',
                        'web/assets/js/src/promo/estudyantrepreneur.js',
                        'web/assets/js/src/plugins.js',
                        'web/assets/js/src/vendor/promo/christmas-promo.js'],
                     
                    'web/assets/js/min/easyshop.bug_report.js':
                        ['web/assets/js/src/bug-report.js'],
                    
                    'web/assets/js/min/easyshop.home-reminder.js':
                        ['web/assets/js/src/vendor/jquery.simplemodal.js',
                         'web/assets/js/src/message-box.js'],
                     
                    'web/assets/js/min/easyshop.how-to.js':
                        ['web/assets/js/src/vendor/jquery-1.9.1.js',
                         'web/assets/js/src/vendor/modernizr-2.6.2.min.js',
                         'web/assets/js/src/how-to-page-plugins.js',
                         'web/assets/js/src/how-to-page.js'],
                     
                    'web/assets/js/min/easyshop.widget-page.js':
                        ['web/assets/js/src/vendor/jquery-1.9.1.js',
                         'web/assets/js/src/widget.js'],
                     
                    'web/assets/js/min/easyshop.widget-selector.js':
                        ['web/assets/js/src/vendor/jquery-1.9.1.js',
                         'web/assets/js/src/vendor/modernizr-2.6.2.min.js',
                         'web/assets/js/src/how-to-page-plugins.js',
                         'web/assets/js/src/how-to-page.js',
                         'web/assets/js/src/widget.js']
                }
            }
        }

    });

    grunt.loadNpmTasks('grunt-bowercopy');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-uglify');

    grunt.registerTask('default', ['bowercopy', 'cssmin', 'uglify']);
};

