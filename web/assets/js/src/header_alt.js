
(function ($) { 

        var hideSuggestion = function(){ 
            $('.nav-suggestion').css({
                top: $('#main_search_alt2').offset().top + $('#main_search_alt2').outerHeight(),
                left: $('#main_search_alt2').offset().left,
                width: $('#main_search_alt2').outerWidth()
            });
        }

        $(window).on('scroll', hideSuggestion);

         $('#main_search_alt').autoComplete({
            minChars: 3,
            cache: false,
            menuClass: 'autocomplete-suggestions',
            source: function(term, response){ 
                try { 
                    xhr.abort(); 
                } catch(e){}
                var xhr = $.ajax({ 
                    type: "get",
                    url: '/search/suggest',
                    data: "query=" + term,
                    dataType: "json", 
                    success: function(data){
                        response(data); 
                    }
                });
            }
        });

        $('#main_search_alt2').autoComplete({
            minChars: 3,
            cache: false,
            menuClass: 'autocomplete-suggestions nav-suggestion',
            source: function(term, response){ 
                try { 
                    xhr.abort(); 
                } catch(e){}
                var xhr = $.ajax({ 
                    type: "get",
                    url: '/search/suggest',
                    data: "query=" + term,
                    dataType: "json", 
                    success: function(data){
                        response(data); 
                    }
                });
            }
        });

})(jQuery);


