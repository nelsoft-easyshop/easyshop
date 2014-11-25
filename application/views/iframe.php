<link type="text/css" href="/assets/css/sell_item.css?ver=<?=ES_FILE_VERSION?>" rel="stylesheet" /> 


<body style="margin:0px">

    <div id="progress_container">
        <div id="progress_bar">
            <div id="progress_completed">
                <span></span>
            </div>
        </div>
    </div>

</body>



<script type='text/javascript' src="/assets/js/src/vendor/jquery-1.9.1.js" ></script>

<script>

    (function($) {
        $(document).ready(function() { 
            setInterval(function() 
            {
                $.get("/productUpload/iframe", function(data)
                {
                    var percentage = $.parseJSON(data).percentage;
                    $('#progress_container').fadeIn(100);   //fade in progress bar  
                    $('#progress_completed span').html(parseInt(percentage) + "%"); //display the % completed within the progress bar
                }
            )},500);  
        });
    })(jQuery);

</script>