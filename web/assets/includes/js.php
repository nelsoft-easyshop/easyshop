<?php
/*
 * This file is only for scripts that have dependent scripts
 */
?>
<script type='text/javascript' src="<?=base_url()?>assets/js/src/vendor/jquery-1.9.1.js" ></script>
<script type='text/javascript' src="<?=base_url()?>assets/js/src/vendor/jquery-ui.js"></script>
<script type='text/javascript' src="<?=base_url()?>assets/js/src/vendor/jquery.jcarousel.min.js"></script>
<script type='text/javascript' src="<?=base_url()?>assets/js/src/vendor/jquery.cookie.js"></script>
<script src="https://autobahn.s3.amazonaws.com/js/autobahn.min.js"></script>
<script>
    window.ab || document.write('<script src="/assets/js/src/vendor/autobahn.min.js">\x3C/script>');
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