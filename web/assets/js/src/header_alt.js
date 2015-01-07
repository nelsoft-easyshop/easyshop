
(function ($) { 

    $('input#main_search_alt')
        .typeahead({
            ajax: { 
                url: '/search/suggest',
                triggerLength: 3, // This is the minimum length of text to take action on
                timeout: 450, //  Specify the amount of time to wait for keyboard input to stop until you send the query to the server.
            },
            items: 10, // The maximum number of items to show in the results.  
        });

    $('input#main_search_alt2')
        .typeahead({
            ajax: { 
                url: '/search/suggest',
                triggerLength: 3, // This is the minimum length of text to take action on
                timeout: 450, //  Specify the amount of time to wait for keyboard input to stop until you send the query to the server.
            },
            items: 10, // The maximum number of items to show in the results.  
        });


})(jQuery);


