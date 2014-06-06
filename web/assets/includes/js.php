<?php
/*
 * This file is only for scripts that have dependent scripts
 */
?>
<script type='text/javascript' src="<?=base_url()?>assets/JavaScript/js/jquery-1.9.1.js" ></script>
<script type='text/javascript' src="<?=base_url()?>assets/JavaScript/js/jquery-ui.js"></script>
<script type='text/javascript' src="<?=base_url()?>assets/JavaScript/js/jquery.jcarousel.min.js"></script>
<script type='text/javascript' src="<?=base_url()?>assets/JavaScript/js/jquery.cookie.js"></script>
<script src="https://autobahn.s3.amazonaws.com/js/autobahn.min.js"></script>
<script>
    window.ab || document.write('<script src="/assests/js/src/vendor/autobahn.min.js">\x3C/script>');
</script>
<script src="/assets/js/src/lib/websocket/client.js"></script>
<script src="/assets/js/src/lib/eventdispatcher.js"></script>

<script type='text/javascript'>
   window.alert = function(message){
        $(document.createElement('div'))
            .attr({title: 'Easyshop.ph', class: 'alert'})
            .html(message)
            .dialog({
                buttons: {OK: function(){$(this).dialog('close');}},
                close: function(){$(this).remove();},
                draggable: true,
                modal: true,
                resizable: false
            });
   };
   
   var entityMap = {
	"&": "&amp;",
	"<": "&lt;",
	">": "&gt;",
	'"': '&quot;',
	"'": '&#39;',
	"/": '&#x2F;'
    };

    function escapeHtml(string) {
        return String(string).replace(/[&<>"'\/]/g, function (s) {
          return entityMap[s];
        });
    }
</script>
        
<!-- <div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=1395192884090886";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<script type="text/javascript">
$(document).ready(function(){
	$('#es_fb_share').on('click', function(e){
		var name = $(this).data('name');
		var link = $(this).data('link');
		var pic = $(this).data('pic');
		var caption = $(this).data('caption');
		var desc = $(this).data('desc');
		var message = $(this).data('message');
		e.preventDefault();
		FB.ui(
		{
			method: 'FEED',
			display: 'popup',
			name: name,
			link: link,
			picture: pic,
			caption: caption,
			description: desc,
			message: message
		});
	});
});
</script> -->