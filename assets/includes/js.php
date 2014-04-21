<script type='text/javascript' src="<?=base_url()?>assets/JavaScript/js/jquery-1.9.1.js" ></script>
<script type='text/javascript' src="<?=base_url()?>assets/JavaScript/js/jquery-ui.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/JavaScript/js/jquery.jcarousel.min.js"></script>

 

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