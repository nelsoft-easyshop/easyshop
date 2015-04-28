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
        <?php if(strtolower(ENVIRONMENT) === 'development'): ?>
            <link rel="stylesheet" href="/assets/css/promo-css.css?ver=<?php echo ES_FILE_VERSION ?>">
        <?php else: ?>
            <link rel="stylesheet" href="/assets/css/min-easyshop.promo-css.css?ver=<?php echo ES_FILE_VERSION ?>">
        <?php endif; ?>
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
                    <a href="/">
                        <img src="<?=getAssetsDomain()?>assets/images/promo-images/easyshop_logo.png">
                    </a>
                </div>
            </div>
        </header>

        <section class="slideshow">

            <div class="container single-image-before"></div>

            <div class="single-image">
                <img src="<?=getAssetsDomain()?>assets/images/promo-images/ESbanner.jpg" alt="">

            </div>

            <div class="container single-image-after" style="height=20;padding:10px 0;">
                
            </div>

        </section>
        <section>
            <?php echo form_open('/EstudyantrepreneurSuccess', ['id' => 'frm-vote']); ?>
            <div class="container load-animate">
                <div class="text-center dropdown-school-list">
                    <select id="ddown-school">
                        <option value="" disabled selected>Select your university...</option>
                        <?PHP foreach($schools_and_students as $school => $students) : ?>
                        <option value="<?=html_escape(str_replace(' ', '-', $school))?>" data-students='<?=json_encode(html_escape($students['students']))?>'><?=html_escape($school)?></option>
                        <?PHP endforeach; ?>
                    </select>
                </div>

                <div>
                <?PHP foreach($schools_and_students as $school => $students) : ?>
                    <div id="student-container" class="<?=html_escape(str_replace(' ', '-', $school))?> select-school mrgn-top-35">
                        <div class="row-fluid">
                            <?php foreach (array_chunk($students['students'], 3, true) as $studentGroup): ?>
                                <div class="span6">
                                    <?php foreach($studentGroup as $student): ?>
                                        <div class="school-participant">
                                            <input class="checkbox-student" id="lbl-<?=html_escape($student['idStudent'])?>" type="radio" data-school="<?=html_escape($school)?>" value="<?=html_escape($student['idStudent'])?>" name="school">
                                            <label for="lbl-<?=html_escape($student['idStudent'])?>"> <?=html_escape($student['student'])?></label>
                                            <div class="school-description row-fluid">
                                                <div class="span12">
                                                    <?=html_escape($student['description'])?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?PHP endforeach; ?>
                </div>
                <div class="vote-btn mrgn-top-35">
                    <input type="hidden" name="studentId" id="stud-id" value="">
                    <input type="hidden" name="schoolName" id="school-name" value="">
                    <button id="btn-vote" class="btn btn-primary"> VOTE </button>
                </div>
            </div>
            <?php echo form_close();?>
        </section>

        <section class="mechanics-section">
            <div class="container load-animate">
                <div class="box">
                    <div class="padding-top-70 padding-bottom-70 padding-left-30 padding-right-30 ss">
                        <p>
                            The 2014 EStudyantrepreneur Awards will give recognition to the most preferred student-owned 
                            business establishment around the Metro through online voting hosted by EasyShop.ph.
                        </p>
                        <h3><b>MECHANICS</b></h3>
                        <ul>
                            <li>
                                Nominations will be done per school. The Entrepreneurship Department or the department in 
                                charge of the Entrepreneurship program will provide at least three (3) student-owned business 
                                nominees together with their advisers. To be eligible to receive the EStudyantrepreneur Award, 
                                the nominee must be the owner of a small business. There are no category limitations for each 
                                business.
                            </li>
                            <li>
                                A completed nomination form with the adviser’s endorsement letter must be submitted on or before <b>April 5, 2015</b>.
                            </li>
                            <li>
                                All students with existing businesses are encouraged to join as long as their advisers endorse their respective businesses.
                            </li>
                            <li>
                                Businesses must be registered from 2013 onwards.
                            </li>
                            <li>
                                Winners will be chosen via an online poll on the EasyShop.ph website.
                            </li>
                            <li>
                                Voters should register on the Easyshop.ph website in order to vote for their choice.
                            </li>
                            <li>
                                Voting period starts on <b>April 15, 2015</b>.
                            </li>
                            <li>
                                Voting period ends on <b>June 15, 2015</b>.
                            </li>
                            <li>
                                There will be one winner for the each school and one winner for the EStudyantrepreneur Awards.
                            </li>
                            <li>
                                There will only be one round of voting. The business who will receive the highest votes among the nominees in each school will be the winner of the consolation prize. The business who will receive the highest total votes will be proclaimed the 2015 EStudyantrepreneur.
                            </li>
                            <li>
                                Voting can only be done once. 1 account = 1 vote.
                            </li>
                            <li>
                                There will only be one winner for the each school and one winner for the EStudyantrepreneur Awards.
                            </li>
                            <li>
                                Awarding will be on <b>June 15, 2015</b>.
                            </li>
                        </ul>
                        <br>
                        <h3><b>What’s in it for participating businesses?</b></h3>
                        <p>
                            EasyShop Online, Inc. will be giving this award as recognition to their businesses that will 
                            give them the advantage of having a free advertisement on the company’s website and will 
                            potentially increase their market’s awareness and eventually generate additional profit.
                        </p>
                        <br>
                        <h3><b>Prizes:</b></h3>
                        <p>
                            School Poll Winner: Php 20,000 for the Student, Php 10,000 for the school, & Php 5,000 for 
                            the Adviser
                        </p>
                        <p>
                            Overall Winner: Php 50,000 for the Student, Php 25,000 for the school, & Php 10,000 for the 
                            Adviser
                        </p>
                        <br>
                        <h3><b>Terms and Conditions:</b></h3>
                        <ul>
                            <li>
                                The contest is open to all Students who are endorsed by their business advisers with an 
                                existing business established on the year 2013 up to the present which is a requirement 
                                to complete their course.
                            </li>
                            <li>
                                By registering to EasyShop.ph, individuals agree, warrant, and represent that all personal 
                                information provided is true, correct, and complete.
                            </li>
                            <li>
                                By participating in this promo, the participant voluntarily provides information that may 
                                be used for market research.
                            </li>
                            <li>
                                A participant can win in the school online poll and inter-school poll.
                            </li>
                            <li>
                                Employees of EasyShop Online Inc. including their relatives up to second degree of 
                                consanguinity or affinity is disqualified from joining the promotion.
                            </li>
                            <li>
                                Only residents of the Republic of the Philippines are eligible to participate in this 
                                promotion.
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
                        <form method="post" id="register" action="/subscribe" class="newsletter-form">
                            <div class="row-fluid">
                                <fieldset>
                                    <?php echo form_open('/subscribe');?>
                                    <input type="text" id="useremail" class="span6" name="email" placeholder="Your e-mail here">
                                    <input type="submit" value="subscribe" class="btn btn-primary" name="subscribe_btn">
                                    <?php echo form_close();?>
                                </fieldset>
                            </div>
                            <div class="newsletter-info-blank">Please enter your email address.</div>
                            <div class="newsletter-info">Thank you for subscribing.</div>
                            <div class="newsletter-validate">Please enter a valid e-mail address</div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <footer>
            <a href="#" id="top">&#59235;</a>


            <section class="footer_links">
                <ul>
                    <li><a href="/">Visit Site</a>&nbsp;&nbsp;.&nbsp;&nbsp;</li>
                    <li><a href="/terms">Terms &amp; Conditions</a>&nbsp;&nbsp;.&nbsp;&nbsp;</li>
                    <li><a href="/policy">Privacy Policy</a>&nbsp;&nbsp;.&nbsp;&nbsp;</li>
                    <li><a href="/contact">Contact</a></li>
                </ul>
            </section>

            <section class="copyright">
                <p>Copyright © 2015 Easyshop.ph<br>All rights reserved.</p>
            </section>
        </footer>
        <input type="hidden" id="is-logged-in" value="<?= (bool) html_escape($isLoggedIn) ? 'true' : 'false'?>">

    <?php if(strtolower(ENVIRONMENT) === 'development'): ?>
        <script type="text/javascript" src="/assets/js/src/vendor/jquery-1.9.1.js?ver=<?php echo ES_FILE_VERSION ?>"></script>
        <script type="text/javascript" src="/assets/js/src/promo/estudyantrepreneur.js?ver=<?php echo ES_FILE_VERSION ?>"></script>
        <script type="text/javascript" src="/assets/js/src/plugins.js?ver=<?php echo ES_FILE_VERSION ?>"></script>
        <script type="text/javascript" src="/assets/js/src/vendor/modernizr-2.6.2.min.js?ver=<?php echo ES_FILE_VERSION ?>"></script>
        <script type="text/javascript" src="/assets/js/src/promo/christmas-promo.js?ver=<?php echo ES_FILE_VERSION ?>"></script>
    <?php else: ?>
        <script src="/assets/js/min/easyshop.estudyantrepreneur-promo.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
    <?php endif; ?>
    </body>
</html>
