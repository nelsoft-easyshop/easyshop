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
                <!-- <div class="text-center dropdown-school-list">
                    <select id="ddown-school">
                        <option value="" disabled selected >Select your university...</option>
                        <?PHP foreach($schools_and_students as $school => $students) : ?>
                        <option value="<?=html_escape(str_replace(' ', '-', $school))?>" data-students='<?=json_encode(html_escape($students['students']))?>'><?=html_escape($school)?></option>
                        <?PHP endforeach; ?>
                    </select>
                </div> -->
                <div class="text-center dropdown-school-list">
                    <select id="ddown-school">
                        <option value="" disabled selected >Select your university...</option>
                        <option value="ateneo-demanila">ATENEO DE MANILA UNIVERSITY</option>
                        <option value="mirian-college">MIRIAM COLLEGE</option>
                        <option value="sanbeda-college">SAN BEDA COLLEGE</option>
                        <option value="university-ofthe-philippines">UNIVERSITY OF THE PHILIPPINES CIRCLE OF ENTREPRENEURS</option>
                        <option value="university-of-santo-tomas">UNIVERSITY OF SANTO TOMAS</option>
                    </select>
                </div>
                <div>
                    <div id="student-container" class="mirian-college select-school mrgn-top-35">
                        <div class="row-fluid">
                            <div class="span6">
                                <div class="school-participant">
                                    <input id="impack" type="radio" data-school="my school" value="1" name="school">
                                    <label for="impack"> IMPACK</label>
                                    <div class="school-description row-fluid">
                                        <div class="span12">
                                            IMPACK is an innovative compactmultipurpose life vest bag with 
                                            detachable bag and sleeping mat. IMPACK also features compartments 
                                            to store essentials for emergency or outdoor activities wherein 
                                            it uses light-weight materials, floatation device for safety and 
                                            most importantly, it is easy to carry and store.
                                        </div>
                                    </div>
                                </div>
                                <div class="school-participant">
                                    <input id="Botanika" type="radio" data-school="my school" value="1" name="school">
                                    <label for="Botanika">Botanika</label>
                                    <div class="school-description row-fluid">
                                        <div class="span12">
                                            Botanika features the old method of natural dyeing 
                                            transfer, and creating clothing and other ensembles 
                                            that are environmental friendly, modern, high quality, 
                                            trendy and in style. Botanika produces clothing made with 
                                            a wide variety of quality fabrics and materials that are 
                                            carefully chosen by the company. Plant dye extracts are 
                                            artistically transferred to each fabric. Presenting their 
                                            product with authenticity, from the packaging that are 
                                            personally handcrafted with art and uniqueness. 
                                        </div>
                                    </div>
                                </div>
                                <div class="school-participant">
                                    <input id="EmmysPolvoron" type="radio" data-school="my school" value="1" name="school">
                                    <label for="EmmysPolvoron"> Emmy’s Polvoron</label>
                                    <div class="school-description row-fluid">
                                        <div class="span12">
                                            Emmy's is a manufacturing and retail company that offers 
                                            Polvoron or Powdered Milk Candies, infused with real fruit 
                                            bits and vitamin-riched vegetables. Emmy's Polvoron has 6 
                                            flavors; Mango, Strawberry, Pineapple, Squash, Carrots and 
                                            Cinnamon, and, Malunggay. The company aims to provide 
                                            healthy alternative to desserts and snacks and also to 
                                            make Filipino local delicacies be recognized in both the 
                                            local and foreign markets.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="span6">
                                <div class="school-participant">
                                    <input id="Lorenza" type="radio" data-school="my school" value="1" name="school">
                                    <label for="Lorenza">Lorenza</label>
                                    <div class="school-description row-fluid">
                                        <div class="span12">
                                            Lorenza is a fashion brand that carries Filipino-inspired 
                                            clothing brand for women. The brand name Lorenza is derived 
                                            from LorenzaAgoncillo, who was one of the seamstresses of 
                                            the first official Philippine Flag.<br /><br />
                                            The brand uses locally made textiles such as organza, habi, 
                                            and batik. However, the brand goes beyond the use Filipino 
                                            textiles because it also takes inspiration from the silhouette 
                                            of the traditional and indigenous Filipina attire such as 
                                            costumes from the Ibaloi tribe, Muslim Mindanao Region, and 
                                            Spanish colonial period.
                                        </div>
                                    </div>
                                </div>
                                <div class="school-participant">
                                    <input id="Resack" type="radio" data-school="my school" value="1" name="school">
                                    <label for="Resack">Re-sack</label>
                                    <div class="school-description row-fluid">
                                        <div class="span12">
                                            Re-sack is an eco-friendly online shop that sells products 
                                            that are made out of recycled jute sacks. The company's 
                                            advocacy is to utilize recycled materials to contribute to 
                                            our nation's preservation of natural resources. Furthermore 
                                            with this advocacy we are promoting to other business owners 
                                            to use recycled materials and be eco- friendly too.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="student-container" class="ateneo-demanila select-school mrgn-top-35">
                        <div class="row-fluid">
                            <div class="span6">
                                <div class="school-participant">
                                    <input id="Cryoplus" type="radio" data-school="my school" value="1" name="school">
                                    <label for="Cryoplus">Cryo+</label>
                                    <div class="school-description row-fluid">
                                        <div class="span12">
                                            CRYO+ Instant Cold Wraps is an instant and reusable instant cold 
                                            pack that comes with a neoprene sleeve. It is specially designed 
                                            to provide instant cooling with added compression to relieve 
                                            muscle pain and inflammation. Each cold pack effectively aids 
                                            in muscle recovery for at least 30 minutes after activation, 
                                            giving more than enough time for inflamed muscles to return back 
                                            to its normal condition after induced stress. 


                                            Not only is CRYO+ Instant Cold Wraps good for muscle recovery, 
                                            but also for emergency situations in need of immediate cold 
                                            treatment.

                                            You may visit our Facebook page: http://www.facebook.com/CryoPlusInstantColdWraps 
                                            for more information

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="span6">
                                <div class="school-participant">
                                    <input id="BahayKeso" type="radio" data-school="my school" value="1" name="school">
                                    <label for="BahayKeso">BahayKeso</label>
                                    <div class="school-description row-fluid">
                                        <div class="span12">
                                            BahayKeso Artisanal Cheese Spreads is dedicated to making 
                                            delicious spreads inspired by the vast flavors of the Philippines. 
                                            Our spreads are made with a blend of premium sharp cheddar and 
                                            creamy cow’s milk cheese, giving a seriously cheesy experience!
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="student-container" class="sanbeda-college select-school mrgn-top-35">
                        <div class="row-fluid">
                            <div class="span6">
                                <div class="school-participant">
                                    <input id="makipinoy" type="radio" data-school="my school" value="1" name="school">
                                    <label for="makipinoy">Maki-Pinoy</label>
                                    <div class="school-description row-fluid">
                                        <div class="span12">
                                            Maki-Pinoy is a service type business that serves different 
                                            cuisines particularly Spanish, American and Japanese. 
                                            The concept of the products represents how the Filipino’s 
                                            were able to adapt to the culture of its colonizers. The 
                                            Filipinos were able to carry on to the concept that has been 
                                            inherited; and now, we have integrated all famous Spanish, 
                                            American, and Japanese cuisines to fit the Filipino’s taste.
                                        </div>
                                    </div>
                                </div>
                                <div class="school-participant">
                                    <input id="Shakeoway" type="radio" data-school="my school" value="1" name="school">
                                    <label for="Shakeoway">Shake O’ Way</label>
                                    <div class="school-description row-fluid">
                                        <div class="span12">
                                            Shake O’ Way is an innovated beverage and at the same time a 
                                            dessert. Our products are basically composed of pure vanilla 
                                            ice cream, mix with branded chocolates, topped with whip cream 
                                            and chocolate syrup. The branded chocolates that we use are 
                                            Kitkat, Oreo, Hersheys, Butterfinger and Whooper, but in the 
                                            future of the business we will offer more variety of flavors. 
                                        </div>
                                    </div>
                                </div>
                                <div class="school-participant">
                                    <input id="Polloxrice" type="radio" data-school="my school" value="1" name="school">
                                    <label for="Polloxrice">Pollo x Rice</label>
                                    <div class="school-description row-fluid">
                                        <div class="span12">
                                            It is a food service type of business that caters different 
                                            flavors of rice topped with 4 BBQ-buffalo glazed chicken poppers 
                                            drizzled with mourney sauce. The purpose of the product is to 
                                            satisfy the ever-changing taste and preferences of people by 
                                            offering a variety of rice flavors that most of the competitors 
                                            fail to provide. The innovation here is that the group plans to 
                                            give life to the rice instead of the usual: flavorful viands and 
                                            the never-ending plain rice. The group thinks that it is not 
                                            necessary to create and invent new viands in this day and age, 
                                            so why not create and offer flavored rice. They will first offer 
                                            3 common flavors of rice, namely Riso (Plain Rice), Aglio 
                                            (Garlic Rice) and Burro (Buttered Rice) so that their market, 
                                            which by the way are students, will first be familiar with the 
                                            flavors and when they fully accepted the idea, then the group 
                                            will offer exciting new flavors.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="span6">
                                <div class="school-participant">
                                    <input id="Travelhomeph" type="radio" data-school="my school" value="1" name="school">
                                    <label for="Travelhomeph">Travel Home PH</label>
                                    <div class="school-description row-fluid">
                                        <div class="span12">
                                            Travel Home PH is a travel management company that caters social 
                                            travelers mainly the overseas filipino workers by being their 
                                            travel partner as to arranging their travel needs. The travel 
                                            management company is equipping its market on their preferred 
                                            destinations.
                                        </div>
                                    </div>
                                </div>
                                <div class="school-participant">
                                    <input id="Resack" type="radio" data-school="my school" value="1" name="school">
                                    <label for="Resack">Reverti</label>
                                    <div class="school-description row-fluid">
                                        <div class="span12">
                                            Reverti is a latin word meaning Reverse and has envision to enter the 
                                            fashion industry with reversible bag. The pioneer product of Reverti is 
                                            a reversible backpack. The backpack is made of Ripstop fabric that is 
                                            tear-resistant and water-resistant.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="student-container" class="university-ofthe-philippines select-school mrgn-top-35">
                        <div class="row-fluid">
                            <div class="span6">
                                <div class="school-participant">
                                    <input id="onlymnl" type="radio" data-school="my school" value="1" name="school">
                                    <label for="onlymnl">ONLY MNL</label>
                                    <div class="school-description row-fluid">
                                        <div class="span12">
                                            ONLY MNL is a fashion brand that aims to empower the local fashion industry through its two products: clothing line and fashion magazine. Its clothing products are designed by aspiring designers and made by skillful Filipina dressmakers. On the other hand, the fashion magazine features local talents, brands, and fashion organizations to give them the opportunity to showcase what they have. ONLY MNL encourages the young Filipina to embrace her own identity through style and to patronize our own fashion.
                                        </div>
                                    </div>
                                </div>
                                <div class="school-participant">
                                    <input id="mangoplusred" type="radio" data-school="my school" value="1" name="school">
                                    <label for="mangoplusred">Mango+Red</label>
                                    <div class="school-description row-fluid">
                                        <div class="span12">
                                            Mango+Red Manila started off as a humble shirt store of pocket t-shirts with the aim of providing a fresh, more customized option for pocket tee lovers. Mango+Red attracted the market and differentiated itself by offering printed pockets instead of the variety in fabrics which was done by the general competitors. Today, Mango+Red improved and expanded its product line by adding others shirts services like: silkscreen and digital printing and gave out a helping hand to other Filipinos who wanted to generate extra income by being open to resellers nationwide.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="span6">
                                <div class="school-participant">
                                    <input id="Serigrafiamnl" type="radio" data-school="my school" value="1" name="school">
                                    <label for="Serigrafiamnl">Serigrafia MNL</label>
                                    <div class="school-description row-fluid">
                                        <div class="span12">
                                            Serigrafia MNL is a home-based printing shop which not only prints ready-made designs but also offers artistically designed, unique, and comfortable apparel and textile products. It offers band and pop culture merchandise to teens and young adults. It also looks forward to collaborating with financially-challenged visual artists from the Art Capital of the Philippines, Angono, Rizal, to produce hand-painted apparel.
                                        </div>
                                    </div>
                                </div>
                                <div class="school-participant">
                                    <input id="Silverscreenphotobooth" type="radio" data-school="my school" value="1" name="school">
                                    <label for="Silverscreenphotobooth">Silver Screen Photobooth</label>
                                    <div class="school-description row-fluid">
                                        <div class="span12">
                                            Silver Screen Photobooth is known for its outstanding professional creative images and design. All they care about is capturing all of your worth remembering memories with gorgeous photos and design.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="student-container" class="university-of-santo-tomas select-school mrgn-top-35">
                        <div class="row-fluid">
                            <div class="span6">
                                <div class="school-participant">
                                    <input id="Picazza" type="radio" data-school="my school" value="1" name="school">
                                    <label for="Picazza">Picazza</label>
                                    <div class="school-description row-fluid">
                                        <div class="span12">
                                            Picazza Pizza House is a restaurant that gives a unique experience by providing customer involvement. The main product of Picazza, the DIY Pizza, allows their customers to choose what kind of dough, flavor of sauce, and the toppings they prefer on their pizza. They also have the option to assemble their pizza on their own. Picazza also serves appetizers, pastas, and milkshakes.
                                        </div>
                                    </div>
                                </div>
                                <div class="school-participant">
                                    <input id="billystapatogo" type="radio" data-school="my school" value="1" name="school">
                                    <label for="billystapatogo">Billy’s Tapa-To-Go</label>
                                    <div class="school-description row-fluid">
                                        <div class="span12">
                                            Billy's Tapa to go sells all-time favorite flavourful recipe tapsilog meal which is served with three flavors to choose from mainly Original, Sweet, and Spicy tapa along with other "silog" meals at high quality and at low price in high accessible near school (UST) and dormitory area perfect for students who are busy and always on-the-go.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="span6">
                                <div class="school-participant">
                                    <input id="cafferaphotocafe" type="radio" data-school="my school" value="1" name="school">
                                    <label for="cafferaphotocafe">CafferaPhoto+Cafe</label>
                                    <div class="school-description row-fluid">
                                        <div class="span12">
                                            CafferaPhoto+Cafeis Manila's 1st Photography themed Cafe. It serves coffee and experiece to photography enthusiasts and spreads the love of photography to everyone.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
        <script type="text/javascript" src="/assets/js/src/promo/christmas-promo.js?ver=<?php echo ES_FILE_VERSION ?>"></script>
    <?php else: ?>
        <script src="/assets/js/min/easyshop.estudyantrepreneur-promo.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
    <?php endif; ?>
    </body>
</html>
