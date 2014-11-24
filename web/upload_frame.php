<?php

$url = basename($_SERVER['SCRIPT_FILENAME']);
             
session_start();
$totalFileSize = $_SESSION['bytes_total'];
$uploadedBytes = $_SESSION['bytes_uploaded_to_s3'];
$percentage = $uploadedBytes / $uploadedBytes * 100;
echo $percentage;


?>

<script type='text/javascript' src="/assets/js/src/vendor/jquery-1.9.1.js" ></script>


<script>
$(document).ready(function() { 
//

    setInterval(function() 
        {
    $.get("<?php echo $url; ?>?progress_key=<?php echo $_GET['up_id']; ?>&randval="+ Math.random(), { 
        //get request to the current URL (upload_frame.php) which calls the code at the top of the page.  It checks the file's progress based on the file id "progress_key=" and returns the value with the function below:
    },
        function(data)  //return information back from jQuery's get request
            {
                $('#progress_container').fadeIn(100);   //fade in progress bar  
                $('#progress_bar').width(data +"%");    //set width of progress bar based on the $status value (set at the top of this page)
                $('#progress_completed').html(parseInt(data) +"%"); //display the % completed within the progress bar
            }
        )},500);    //Interval is set at 500 milliseconds (the progress bar will refresh every .5 seconds)

});


</script>

<body style="margin:0px">
<!--Progress bar divs-->
<div id="progress_container">
    <div id="progress_bar">
         <div id="progress_completed"></div>
    </div>
</div>
<!---->
</body>