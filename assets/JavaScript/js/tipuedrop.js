
/*
Tipue drop 3.1
Copyright (c) 2013 Tipue
Tipue drop is released under the MIT License
http://www.tipue.com/drop
*/


(function($) {

     $.fn.tipuedrop = function(options) {

          var set = $.extend( {
          
               'show'                   : 20,
               'maxWidth'               : 0,
               'speed'                  : 300,
               'newWindow'              : false,
               'mode'                   : 'static',
               'contentLocation'        : 'tipuedrop/tipuedrop_content.json'
          
          }, options);
          
          return this.each(function() {
          
               var tipuedrop_in = {
                    pages: []
               };
               $.ajaxSetup({
                    async: false
               });                 
                              
               if (set.mode == 'json')
               {
                    $.getJSON(set.contentLocation,
                         function(json)
                         {
                              tipuedrop_in = $.extend({}, json);
                         }
                    );
               }
               if (set.mode == 'static')
               {
                    tipuedrop_in = $.extend({}, tipuedrop);
               }

               $(this).keyup(function(event)
               {
                    getTipuedrop($(this));
               });               
               
               function getTipuedrop($obj)
               {
                    if ($obj.val())
                    {    
                         var category = $("#q_cat").val();
                         
                         var out = '<div id="tipue_drop_point"></div>';
                         var c = 0;
                         for (var i = 0; i < tipuedrop_in.pages.length; i++)
                         {
                              var pat = new RegExp($obj.val(), 'i');
                              if ((tipuedrop_in.pages[i].title.search(pat) != -1) && c < set.show)
                              {
                                   out += '<a href="' + tipuedrop_in.pages[i].loc + '"';
                                   // out += '<a href="' + tipuedrop_in.pages[i].loc + '&q_cat='+category+'"';
                                   if (set.newWindow)
                                   {
                                        out += ' target="_blank"';
                                   }
                                   out += '><div class="main_search_drop_item" style="max-width: ' + (set.maxWidth - 40) + 'px;">';
                                   out += '<div class="main_search_drop_text">' + tipuedrop_in.pages[i].title + '</div></div></a>';
                                   c++;
                              }
                         }
                         if (c == 0)
                         {
                              out += '<div class="main_search_drop_no_items">No suggestions</div>';     
                         }
                                        
                         $('#main_search_drop_content').html(out);
                         $('#main_search_drop_content').fadeIn(set.speed);
                         $('#main_search_drop_fade').fadeIn(100);
                    }
                    else
                    {
                         $('#main_search_drop_content').fadeOut(set.speed);
                         $('#main_search_drop_fade').fadeOut(100);
                    }
               }
               
               $('html').click(function()
               {
                    $('#main_search_drop_content').fadeOut(set.speed);
                    $('#main_search_drop_fade').fadeOut(100);
               });
          
          });
     };
     
})(jQuery);
