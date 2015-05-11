<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <link rel="stylesheet" type="text/css" href="/assets/css/chat-css.css?ver=<?=ES_FILE_VERSION?>" media='screen'>
<?php else: ?>
    <link  rel="stylesheet" type="text/css" href='/assets/css/min-easyshop.chat-view.css?ver=<?=ES_FILE_VERSION?>' media='screen'/>
<?php endif; ?>

<section class="bg-cl-fff of-hidden">
    <div class="container pd-tb-45">
        <div class="row">
            <!-- left panel message -->
            <div class="col-md-3 left-panel-message">
                <input class="chat-compose btn btn-default-5" type="button" value="COMPOSE">
                <!-- chat dialog box -->
                <div class="chat-compose-dialog">
                    <div class="row">
                        <div class="col-xs-12 chat-compose-search-container">
                            <strong>To:</strong>
                            <input type="text" class="ui-form-control" placeholder="Search">
                            <div class="chat-compose-search-result">
                                <ul>
                                    <li>MichaelaMichaelaMichaelaMichaelaMichaelaMichaelaMichaelaMichaelaMichaelaMichaelaMichaelaMichaelaMichaelaMichaela</li>
                                    <li>MichaelaMichaelaMichaela</li>
                                    <li>MichaelaMichaela</li>
                                    <li>Michaela</li>
                                    <li>Michaela</li>
                                    <li>Michaela</li>
                                    <li>Michaela</li>
                                    <li>Michaela</li>
                                    <li>Michaela</li>
                                    <li>Michaela</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <textarea placeholder="Your Message..." class="ui-form-control"></textarea>
                        </div>
                        <div class="col-xs-12 text-right">
                            <input type="button" class="btn btn-default-3" value="SEND">
                        </div>
                    </div>
                </div>
                <!-- end chat dialog box -->

                <div class="mrgn-top-20 contacts-container">
                    <h4>CONTACTS</h4>
                    <div class="contact-list-container">
                        <input class="form-control" type="text" placeholder="Search">
                        <div class="search-contact-results-container">
                            <p><strong>Search Results</strong></p>
                            <div>
                                <ul class="search-contact-results">
                                    <li>
                                        <div class="contact-photo search-contact-photo" style="background-image: url(../assets/images/brands/michaela.png);"></div>
                                        <div class="search-contact-info-container">
                                            <div class="search-contact-info-name">Michaela MichaelaMichaelaMichaelaMichaelaMichaela</div>
                                            <div class="search-contact-info-others">
                                                <span>ValenzuelaValenzuelaValenzuelaValenzuela City</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="contact-photo search-contact-photo" style="background-image: url(../assets/images/brands/michaela.png);"></div>
                                        <div class="search-contact-info-container">
                                            <div class="search-contact-info-name">Michaela</div>
                                            <div class="search-contact-info-others">
                                                <span>Pasig City</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="contact-photo search-contact-photo" style="background-image: url(../assets/images/brands/michaela.png);"></div>
                                        <div class="search-contact-info-container">
                                            <div class="search-contact-info-name">MichaelaMichaelaMichaelaMichaela</div>
                                            <div class="search-contact-info-others">
                                                <span>Pasig City</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="contact-photo search-contact-photo" style="background-image: url(../assets/images/brands/michaela.png);"></div>
                                        <div class="search-contact-info-container">
                                            <div class="search-contact-info-name">Micha</div>
                                            <div class="search-contact-info-others">
                                                <span>Pasig City</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="contact-photo search-contact-photo" style="background-image: url(../assets/images/brands/michaela.png);"></div>
                                        <div class="search-contact-info-container">
                                            <div class="search-contact-info-name">Michael</div>
                                            <div class="search-contact-info-others">
                                                <span>Pasig City</span>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <ul class="contact-list">
                            <li>
                                <div class="contact-photo contact-profile-photo" style="background-image: url(../assets/images/brands/michaela.png);">
                                </div>
                                <div class="contact-info-container">
                                    <div class="contact-info-name">Michaela MichaelaMichaelaMichaelaMichaelaMichaela</div>
                                    <div class="contact-info-others">
                                        <span class="contact-online">
                                            <i class="fa fa-check-circle"></i> Online
                                        </span>
                                        <span>ValenzuelaValenzuelaValenzuelaValenzuela City</span>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="contact-photo contact-profile-photo" style="background-image: url(../assets/images/brands/bsmobile.png);">
                                </div>
                                <div class="contact-info-container">
                                    <div class="contact-info-name">BS Mobile</div>
                                    <div class="contact-info-others">
                                        <span class="contact-offline">
                                            <i class="fa fa-check-circle"></i> Offline
                                        </span>
                                        <span>Makati City</span>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="contact-photo contact-profile-photo" style="background-image: url(../assets/images/brands/dorothyperkins.png);">
                                </div>
                                <div class="contact-info-container">
                                    <div class="contact-info-name">Dorothy Perkins</div>
                                    <div class="contact-info-others">
                                        <span class="contact-online">
                                            <i class="fa fa-check-circle"></i> Online
                                        </span>
                                        <span>Pasig City</span>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="contact-photo contact-profile-photo" style="background-image: url(../assets/images/brands/forever21.png);">
                                </div>
                                <div class="contact-info-container">
                                    <div class="contact-info-name">forever21</div>
                                    <div class="contact-info-others">
                                        <span class="contact-offline">
                                            <i class="fa fa-check-circle"></i> Offline
                                        </span>
                                        <span>Makati City</span>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="contact-photo contact-profile-photo" style="background-image: url(../assets/images/brands/ivessaintlaurent.png);">
                                </div>
                                <div class="contact-info-container">
                                    <div class="contact-info-name">Ives Saint Laurent</div>
                                    <div class="contact-info-others">
                                        <span class="contact-online">
                                            <i class="fa fa-check-circle"></i> Online
                                        </span>
                                        <span>Pasig City</span>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="contact-photo contact-profile-photo" style="background-image: url(../assets/images/brands/uniqlo.png);">
                                </div>
                                <div class="contact-info-container">
                                    <div class="contact-info-name">uniqlo</div>
                                    <div class="contact-info-others">
                                        <span class="contact-offline">
                                            <i class="fa fa-check-circle"></i> Offline
                                        </span>
                                        <span>Makati City</span>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="contact-photo contact-profile-photo" style="background-image: url(../assets/images/brands/samsung.png);">
                                </div>
                                <div class="contact-info-container">
                                    <div class="contact-info-name">Samsung</div>
                                    <div class="contact-info-others">
                                        <span class="contact-online">
                                            <i class="fa fa-check-circle"></i> Online
                                        </span>
                                        <span>Pasig City</span>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="contact-photo contact-profile-photo" style="background-image: url(../assets/images/brands/bsmobile.png);">
                                </div>
                                <div class="contact-info-container">
                                    <div class="contact-info-name">BS Mobile</div>
                                    <div class="contact-info-others">
                                        <span class="contact-offline">
                                            <i class="fa fa-check-circle"></i> Offline
                                        </span>
                                        <span>Makati City</span>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="contact-photo contact-profile-photo" style="background-image: url(../assets/images/brands/michaela.png);">
                                </div>
                                <div class="contact-info-container">
                                    <div class="contact-info-name">Michaela MichaelaMichaelaMichaelaMichaelaMichaela</div>
                                    <div class="contact-info-others">
                                        <span class="contact-online">
                                            <i class="fa fa-check-circle"></i> Online
                                        </span>
                                        <span>Pasig City</span>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="contact-photo contact-profile-photo" style="background-image: url(../assets/images/brands/bsmobile.png);">
                                </div>
                                <div class="contact-info-container">
                                    <div class="contact-info-name">BS Mobile</div>
                                    <div class="contact-info-others">
                                        <span class="contact-offline">
                                            <i class="fa fa-check-circle"></i> Offline
                                        </span>
                                        <span>Makati City</span>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- end of left panel message -->

            <!-- right panel of message -->
            <div class="col-md-9">
                <div class="right-panel-message">
                    <div class="right-panel-header">
                        <div class="col-xs-8">
                            <h4 class="chat-box-contact-name">
                                Michaela MichaelaMichaelaMichaelaMichaelaMichaelaMichaelaMich
                                aelaMichaelaMichaelaMichaelaMichaelaMichaela
                            </h4>
                        </div>
                        <div class="col-xs-4 text-right">
                            <input class="btn btn-default-1" type="button" value="Delete Conversation">
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="chat-box-messages row">
                        <div class="chat-box-receive">
                            <div class="contact-photo chat-box-profile-photo" style="background-image: url(../assets/images/brands/michaela.png);"></div>
                            <div class="chat-box-message">
                                Lorem ipsum dolor sit amet.
                                <div class="delete-message"><i class="icon-delete"></i></div>
                            </div>
                            <div class="chat-box-time">
                                <div>2015-01-23 </div>
                                <div><strong>09:36:24</strong></div>
                            </div>
                        </div>
                        <div class="chat-box-send">
                            <div class="contact-photo chat-box-profile-photo" style="background-image: url(../assets/images/brands/michaela.png);"></div>
                            <div class="chat-box-message">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc nec ex eu metus 
                                porttitorornare nec sed urna.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc nec ex eu metus 
                                porttitorornare nec sed urna.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc nec ex eu metus 
                                porttitorornare nec sed urna.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc nec ex eu metus 
                                porttitorornare nec sed urna.
                                <div class="delete-message"><i class="icon-delete"></i></div>
                            </div>
                            <div class="chat-box-time">
                                <div>2015-01-23 </div>
                                <div><strong>09:36:24</strong></div>
                            </div>
                        </div>
                        <div class="chat-box-receive">
                            <div class="contact-photo chat-box-profile-photo" style="background-image: url(../assets/images/brands/michaela.png);"></div>
                            <div class="chat-box-message">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc nec ex eu metus 
                                porttitorornare nec sed urna.
                                <div class="delete-message"><i class="icon-delete"></i></div>
                            </div>
                            <div class="chat-box-time">
                                <div>2015-01-23 </div>
                                <div><strong>09:36:24</strong></div>
                            </div>
                        </div>
                        <div class="chat-box-send">
                            <div class="contact-photo chat-box-profile-photo" style="background-image: url(../assets/images/brands/michaela.png);"></div>
                            <div class="chat-box-message">
                                Lorem ipsum dolor sit amet.
                                <div class="delete-message"><i class="icon-delete"></i></div>
                            </div>
                            <div class="chat-box-time">
                                <div>2015-01-23 </div>
                                <div><strong>09:36:24</strong></div>
                            </div>
                        </div>
                        <div class="chat-box-receive">
                            <div class="contact-photo chat-box-profile-photo" style="background-image: url(../assets/images/brands/michaela.png);"></div>
                            <div class="chat-box-message">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc nec ex eu metus 
                                porttitorornare nec sed urna.
                                <div class="delete-message"><i class="icon-delete"></i></div>
                            </div>
                            <div class="chat-box-time">
                                <div>2015-01-23 </div>
                                <div><strong>09:36:24</strong></div>
                            </div>
                        </div>
                        <div class="chat-box-send">
                            <div class="contact-photo chat-box-profile-photo" style="background-image: url(../assets/images/brands/michaela.png);"></div>
                            <div class="chat-box-message">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc nec ex eu metus 
                                porttitorornare nec sed urna.
                                <div class="delete-message"><i class="icon-delete"></i></div>
                            </div>
                            <div class="chat-box-time">
                                <div>2015-01-23 </div>
                                <div><strong>09:36:24</strong></div>
                            </div>
                        </div>
                        <div class="chat-box-receive">
                            <div class="contact-photo chat-box-profile-photo" style="background-image: url(../assets/images/brands/michaela.png);"></div>
                            <div class="chat-box-message">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc nec ex eu metus 
                                porttitorornare nec sed urna.
                                <div class="delete-message"><i class="icon-delete"></i></div>
                            </div>
                            <div class="chat-box-time">
                                <div>2015-01-23 </div>
                                <div><strong>09:36:24</strong></div>
                            </div>
                        </div>
                        <div class="chat-box-send">
                            <div class="contact-photo chat-box-profile-photo" style="background-image: url(../assets/images/brands/michaela.png);"></div>
                            <div class="chat-box-message">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc nec ex eu metus 
                                porttitorornare nec sed urna.
                                <div class="delete-message"><i class="icon-delete"></i></div>
                            </div>
                            <div class="chat-box-time">
                                <div>2015-01-23 </div>
                                <div><strong>09:36:24</strong></div>
                            </div>
                        </div>
                        <div class="chat-box-receive">
                            <div class="contact-photo chat-box-profile-photo" style="background-image: url(../assets/images/brands/michaela.png);"></div>
                            <div class="chat-box-message">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc nec ex eu metus 
                                porttitorornare nec sed urna.
                                <div class="delete-message"><i class="icon-delete"></i></div>
                            </div>
                            <div class="chat-box-time">
                                <div>2015-01-23 </div>
                                <div><strong>09:36:24</strong></div>
                            </div>
                        </div>
                        <div class="chat-box-send">
                            <div class="contact-photo chat-box-profile-photo" style="background-image: url(../assets/images/brands/michaela.png);"></div>
                            <div class="chat-box-message">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc nec ex eu metus 
                                porttitorornare nec sed urna.
                                <div class="delete-message"><i class="icon-delete"></i></div>
                            </div>
                            <div class="chat-box-time">
                                <div>2015-01-23 </div>
                                <div><strong>09:36:24</strong></div>
                            </div>
                        </div>
                        <div class="chat-box-receive">
                            <div class="contact-photo chat-box-profile-photo" style="background-image: url(../assets/images/brands/michaela.png);"></div>
                            <div class="chat-box-message">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc nec ex eu metus 
                                porttitorornare nec sed urna.
                                <div class="delete-message"><i class="icon-delete"></i></div>
                            </div>
                            <div class="chat-box-time">
                                <div>2015-01-23 </div>
                                <div><strong>09:36:24</strong></div>
                            </div>
                        </div>
                        <div class="chat-box-send">
                            <div class="contact-photo chat-box-profile-photo" style="background-image: url(../assets/images/brands/michaela.png);"></div>
                            <div class="chat-box-message">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc nec ex eu metus 
                                porttitorornare nec sed urna.
                                <div class="delete-message"><i class="icon-delete"></i></div>
                            </div>
                            <div class="chat-box-time">
                                <div>2015-01-23 </div>
                                <div><strong>09:36:24</strong></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="send-message-box">
                    <textarea class="ui-form-control" placeholder="Your Message..."></textarea>
                    <input type="button" value="SEND">
                </div>
            </div>
            <!-- end of right panel of message -->
        </div>
    </div>
</section>

<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <script type='text/javascript' src="/assets/js/src/vendor/bower_components/jquery.nicescroll.js?ver=<?=ES_FILE_VERSION?>"></script> 
    <script type='text/javascript' src="/assets/js/src/chat.js?ver=<?=ES_FILE_VERSION?>"></script> 
<?php else: ?>
    <script src="/assets/js/min/easyshop.chat-view.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<?php endif; ?>

