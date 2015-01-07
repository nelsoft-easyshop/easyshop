
(function ($) { 

    $('input#main_search_alt')
        .typeahead({
            ajax: { 
                url: '/search/suggest',
                triggerLength: 3, // This is the minimum length of text to take action on
                timeout: 450, //  Specify the amount of time to wait for keyboard input to stop until you send the query to the server.
                preProcess: function (data) { 
                    if ($.isEmptyObject(data)) { 
                        $('.suggested-result-container-alt').empty();
                    } 
                    return data;
                }
            },
            items: 10, // The maximum number of items to show in the results. 
            menu: '<ul class="typeahead dropdown-menu suggested-result-container-alt"></ul>' ,
            item: '<li><a href="#"></a></li>'
        }) 
        .focus(function() { 
            if($(this).val().length >= 3){ 
                if ($('.suggested-result-container-alt').is(':empty') === false){ 
                    $('.suggested-result-container-alt').show();
                }
            }
        })
        .focusout(function() { 
            $('.suggested-result-container-alt').hide();
            $('.suggested-result-container-alt2').html($('.suggested-result-container-alt').html());
        });

    $('input#main_search_alt2')
        .typeahead({
            ajax: { 
                url: '/search/suggest',
                triggerLength: 3, // This is the minimum length of text to take action on
                timeout: 450, //  Specify the amount of time to wait for keyboard input to stop until you send the query to the server.
                preProcess: function (data) { 
                    if ($.isEmptyObject(data)) { 
                        $('.suggested-result-container-alt2').empty();
                    } 
                    return data;
                }
            },
            items: 10, // The maximum number of items to show in the results. 
            menu: '<ul class="typeahead dropdown-menu suggested-result-container-alt2"></ul>' ,
            item: '<li><a href="#"></a></li>'
        }) 
        .focus(function() { 
            if($(this).val().length >= 3){ 
                if ($('.suggested-result-container-alt2').is(':empty') === false){ 
                    $('.suggested-result-container-alt2').show();
                }
            }
        })
        .focusout(function() { 
            $('.suggested-result-container-alt2').hide();
            $('.suggested-result-container-alt').html($('.suggested-result-container-alt2').html());
        });


})(jQuery);


