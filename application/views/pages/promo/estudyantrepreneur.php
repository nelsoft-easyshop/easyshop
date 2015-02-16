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
                            <?PHP foreach ($students['students'] as $student) : ?>
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
                        <p>
                            The 2014 EStudyantrepreneur Awards will give recognition to the most preferred student-owned business establishment around the Metro through online voting hosted by EasyShop.ph.
                        </p>
                        <h3><b>MECHANICS</b></h3>
                        <ul>
                            <li>
                                Nominations will be done per school. The Entrepreneurship Department or the department in charge of the Entrepreneurship program will provide at least three (3) student-owned business nominees together with their advisers. To be eligible to receive the EStudyantrepreneur Award, the nominee must be the owner of a small business. There are no category limitations for each business.
                            </li>
                            <li>
                                All students with existing businesses are encouraged to join as long as their advisers endorse their respective businesses.
                            </li>
                            <li>
                                Businesses must be registered from 2013 onwards and must be a requirement to complete their undergraduate program.
                            </li>
                            <li>
                                Winners will be chosen via an online poll on the EasyShop.ph website.
                            </li>
                            <li>
                                Nomination period for school level starts on <b>February 23, 2015</b>.
                            </li>
                            <li>
                                There will be three winners for the nomination period.
                            </li>
                            <li>
                                In case of a tie, both nominees will qualify in the in-school poll.
                            </li>
                            <li>
                                Voting period for the in-school level starts on <b>March 7, 2015</b>.
                            </li>
                            <li>
                                There will be one winner for the in-school voting round.
                            </li>
                            <li>
                                In case of a tie, both winners will qualify for the 2015 EStudyantrepreneur poll.
                            </li>
                            <li>
                                Voting period for the 2015 EStudyantrepreneur poll will start on <b>April 8, 2015</b>.
                            </li>
                            <li>
                                Awarding will be on <b>June 15, 2015</b>.
                            </li>
                        </ul>
                        <br>
                        <h3><b>What’s in it for participating businesses?</b></h3>
                        <p>
                            EasyShop Online, Inc. will be giving this award as recognition to their businesses that will give them the advantage of having a free advertisement on the company’s website and will potentially increase their market’s awareness and eventually generate additional profit.
                        </p>
                        <br>
                        <h3><b>Prizes:</b></h3>
                        <p>
                            School Poll Winner: Php 20,000 for the Student, Php 10,000 for the school, & Php 5,000 for the Adviser
                        </p>
                        <p>
                            Overall Winner: Php 50,000 for the Student, Php 25,000 for the school, & Php 10,000 for the Adviser
                        </p>
                        <br>
                        <h3><b>Terms and Conditions:</b></h3>
                        <ul>
                            <li>
                                The contest is open to all Students who are endorsed by their business advisers with an existing business established on the year 2013 up to the present which is a requirement to complete their course.
                            </li>
                            <li>
                                By registering to EasyShop.ph, individuals agree, warrant, and represent that all personal information provided is true, correct, and complete.
                            </li>
                            <li>
                                By participating in this promo, the participant voluntarily provides information that may be used for market research.
                            </li>
                            <li>
                                A participant can win in the school online poll and inter-school poll.
                            </li>
                            <li>
                                Employees of EasyShop Online Inc. including their relatives up to second degree of consanguinity or affinity is disqualified from joining the promotion.
                            </li>
                            <li>
                                Only residents of the Republic of the Philippines are eligible to participate in this promotion.
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
                        <h3>Make sure you don't miss interesting events, sale, <br>and more by joining our newsletter program.</h3>
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

        <script src="/assets/js/src/vendor/jquery-1.9.1.js"></script>
        <script type="text/javascript" src="/assets/js/src/promo/estudyantrepreneur.js"></script>
        <script src="/assets/js/src/plugins.js"></script>
        <script src="/assets/js/src/christmas-promo.js"></script>

    </body>
</html>
