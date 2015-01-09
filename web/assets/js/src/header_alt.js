
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
            menuClass: 'autocomplete-suggestions main-nav',
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
        })
        .focus(function() {
            if($(this).val().length <= 2){
                $('.autocomplete-suggestions').hide();
            }
            else{ 
                if( $.trim( $('.main-nav').html() ).length ) {
                    hideSuggestion();
                    $('.main-nav').show();
                }
            }
        })
        .focusout(function() {
            $('.nav-suggestion').html($('.main-nav').html());
        })
        .change(function() {
            if($(this).val().length <= 0){
                $('.autocomplete-suggestions').empty();
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
        })
        .focusout(function() {
            $('.main-nav').html($('.nav-suggestion').html());
        })
        .focus(function() {
            if($(this).val().length <= 2){
                $('.autocomplete-suggestions').hide();
            }
            else{ 
                if( $.trim( $('.nav-suggestion').html() ).length ) {
                    hideSuggestion();
                    $('.nav-suggestion').show();
                }
            }
        })
        .change(function() {
            if($(this).val().length <= 0){
                $('.autocomplete-suggestions').empty();
            }
        });

})(jQuery);


