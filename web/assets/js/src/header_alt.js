
(function ($) { 

        var hideSuggestion = function(){ 
            $('.nav-suggestion').css({
                top: $('#main_search_alt2').offset().top + $('#main_search_alt2').outerHeight(),
                left: $('#main_search_alt2').offset().left,
                width: $('#main_search_alt2').outerWidth()
            });
        }

        $(window).on('scroll', hideSuggestion);

        var $minChars = 3;
        
        $('#main_search_alt')
            .autoComplete({
                minChars: $minChars,
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
                },
                onSelect: function(term){
                    $('#main_search_alt').addClass('selectedClass');
                }
            })
            .focus(function() {
                if($(this).val().length < $minChars){
                    $('.autocomplete-suggestions').hide();
                }
                else{ 
                    if(!$(this).hasClass('selectedClass')){
                        if( $.trim( $('.main-nav').html() ).length ) {
                            hideSuggestion();
                            $('.main-nav').show();
                        }
                    }
                    else{ 
                        $(this).removeClass('selectedClass');
                    }
                }
            })
            .click(function() {
                if($(this).val().length < $minChars){
                    $('.autocomplete-suggestions').hide();
                }
                else{ 
                    if(!$(this).hasClass('selectedClass')){
                        if( $.trim( $('.main-nav').html() ).length ) {
                            hideSuggestion();
                            $('.main-nav').show();
                        }
                    }
                    else{ 
                        $(this).removeClass('selectedClass');
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
            }) 
            .keyup(function() {
                var searchString = $(this).val();
                $('#main_search_alt2').val(searchString);
            });

        $('#main_search_alt2')
            .autoComplete({
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
                },
                onSelect: function(term){
                    $('#main_search_alt2').addClass('selectedClass');
                }
            })
            .focusout(function() {
                $('.main-nav').html($('.nav-suggestion').html());
            })
            .focus(function() {
                if($(this).val().length < $minChars){
                    $('.autocomplete-suggestions').hide();
                }
                else{ 
                    if(!$(this).hasClass('selectedClass')){
                        if( $.trim( $('.nav-suggestion').html() ).length ) {
                            hideSuggestion();
                            $('.nav-suggestion').show();
                        }
                    }
                    else{ 
                        $(this).removeClass('selectedClass');
                    }
                }
            })
            .click(function() {
                if($(this).val().length < $minChars){
                    $('.autocomplete-suggestions').hide();
                }
                else{ 
                    if(!$(this).hasClass('selectedClass')){
                        if( $.trim( $('.nav-suggestion').html() ).length ) {
                            hideSuggestion();
                            $('.nav-suggestion').show();
                        }
                    }
                    else{ 
                        $(this).removeClass('selectedClass');
                    }
                }
            })
            .change(function() {
                if($(this).val().length <= 0){
                    $('.autocomplete-suggestions').empty();
                }
            })
            .keyup(function() {
                var searchString = $(this).val();
                $('#main_search_alt').val(searchString);
            });

})(jQuery);


