<h2 style='color:red;'> Unable to upload image. </h2>

<p style='font-size:20px;'>
    <strong>
        You can only upload <?php echo $allowedFileTypes; ?> files with a  max size of  <?php echo $maxSize; ?> KB and 
        max  dimensions of <?php echo $maxHeight; ?> px by <?php echo $maxWidth; ?>  px 
    </strong>
    <input type="hidden" value="<?php echo isset($redirectUrl) ? $redirectUrl : '/' ?>" id="redirectUrl"/>
</p>
        
<script type='text/javascript'>
    setTimeout(
        function(){
            window.location.href= document.getElementById("redirectUrl").value;
        },
    3000);
</script>



