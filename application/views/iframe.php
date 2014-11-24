<script type='text/javascript' src="/assets/js/src/vendor/jquery-1.9.1.js" ></script>

<script>

(function($) {

    $(document).ready(function() { 
        setInterval(function() 
        {
            $.get("/productUpload/iframe", function(data)
            {
                $('#progress_container').fadeIn(100);   //fade in progress bar  
                $('#progress_bar').width(data +"%");    //set width of progress bar based on the $status value (set at the top of this page)
                $('#progress_completed').html(parseInt(data) +"%"); //display the % completed within the progress bar
            }
        )},500);  

    });

})(jQuery);

</script>

<body style="margin:0px">

    <div id="progress_container">
        <div id="progress_bar">
            <div id="progress_completed"></div>
        </div>
    </div>

</body>