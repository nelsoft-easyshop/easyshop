<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Estudyantrepreneur</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="/assets/images/favicon.ico" type="image/x-icon"/>
        <link rel="stylesheet" href="/assets/css/promo-css.css">
        <script src="/assets/js/src/vendor/modernizr-2.6.2.min.js"></script>
        <script type="text/javascript">

          var _gaq = _gaq || [];
          _gaq.push(['_setAccount', 'UA-33801742-8']);
          _gaq.push(['_trackPageview']);

          (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
          })();

        </script>
    </head>
    <body class="animated fadeIn">
        <header>
            <div class="container">
                <div class="logo">
                    <a href="/?view=basic">
                        <img src="/assets/images/promo-images/easyshop_logo.png">
                    </a>
                </div>
            </div>
        </header>

        <section class="slideshow">

            <div class="container single-image-before"></div>

            <div class="single-image">
                <img src="/assets/images/promo-images/ESbanner.jpg" alt="">

            </div>

            <div class="container single-image-after" style="height=20;padding:10px 0;">
                
            </div>

        </section>
        <section>
            <div class="container load-animate">
                <div class="text-center dropdown-school-list">
                    <select id="ddown-school">
                        <option value=""></option>
                        <?PHP foreach($schools_and_students as $school => $students) : ?>
                        <option value="<?=html_escape(str_replace(' ', '-', $school))?>"><?=html_escape($school)?></option>
                        <?PHP endforeach; ?>
                    </select>
                </div>
                <div>
                    <div id="student-container" class="select-school mrgn-top-35">
                        <?PHP foreach ($schools_and_students as $school => $students) : ?>
                            <div id="<?=html_escape(str_replace(' ', '-', $school))?>" style="border: 1px black solid;display: none" class="display-none">
                            <?PHP foreach ($students as $student) : ?>
                                <span>
                                    <input name="student" type="radio" value="<?=html_escape($student['idStudent'])?>" data-school="<?=html_escape($student['idSchool'])?>">
                                    <label><?=html_escape($student['student'])?></label>
                                </span>
                            <?PHP endforeach; ?>
                            </div>
                            <br>
                        <?PHP endforeach; ?>
                    </div>
                </div>
                <div class="vote-btn mrgn-top-35">
                    <button id="btn-vote" class="btn btn-primary"> VOTE </button>
                </div>
        </section>

        <section class="mechanics-section">
            <div class="container load-animate">
                <div class="box">
                    <div class="padding-top-70 padding-bottom-70 padding-left-30 padding-right-30">
                        <h3>MECHANICS</h3>
                        <ul>
                            <li>
                                Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. 
                            </li>
                            <li>
                                Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. 
                            </li>
                            <li>
                                Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. 
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section class="newsletter">
            <div class="container load-animate">
                <div class="row-fluid">
                    <div class="span12 padding-top-30">
                        <h3>Make sure you don't miss interesting events, sale, <br>and more by joining our newsletter program.</h5>
                        <br>
                        <form method="post" action="newsletter.php" class="newsletter-form">
                            <div class="row-fluid">
                                <fieldset>
                                    <input class="span6" type="email" placeholder="Your e-mail here" name="email" required><br>
                                    <button class="btn btn-primary" type="submit">SUBSRIBE</button>
                                </fieldset>
                            </div>           
                            <div class="newsletter-info">Thanks for subscribing</div>
                            <div class="newsletter-validate">Please enter a valid e-mail'</div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <footer>
            <a href="#" id="top">&#59235;</a>


            <section class="footer_links">
                <ul>
                    <li><a href="/?view=basic">Visit Site</a>&nbsp;&nbsp;.&nbsp;&nbsp;</li>
                    <li><a href="/terms">Terms &amp; Conditions</a>&nbsp;&nbsp;.&nbsp;&nbsp;</li>
                    <li><a href="/policy">Privacy Policy</a>&nbsp;&nbsp;.&nbsp;&nbsp;</li>
                    <li><a href="/contact">Contact</a></li>
                </ul>
            </section>

            <section class="copyright">
                <p>Copyright © 2015 Easyshop.ph<br>All rights reserved.</p>
            </section>
        </footer>


        <script type="text/javascript" src="/assets/js/src/promo/estudyantrepreneur.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script src="/assets/js/src/plugins.js"></script>
        <script src="/assets/js/src/christmas-promo.js"></script>

    </body>
</html>


