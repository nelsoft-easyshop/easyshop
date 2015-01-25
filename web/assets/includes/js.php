<?php
/*
 * This file is only for scripts that have dependent scripts
 */
?>
<script type='text/javascript' src="/assets/js/src/vendor/jquery-1.9.1.js" ></script>
<script type='text/javascript' src="/assets/js/src/landingpage-responsive-nav.js" ></script>
<script type='text/javascript' src="/assets/js/src/vendor/jquery-ui.js"></script>
<script type='text/javascript' src="/assets/js/src/vendor/jquery.jcarousel.min.js"></script>
<script type='text/javascript' src="/assets/js/src/vendor/jquery.cookie.js"></script>
<script src="https://autobahn.s3.amazonaws.com/js/autobahn.min.js"></script>
<script>
    window.ab || document.write('<script src="/assets/js/src/vendor/autobahn.min.js">\x3C/script>');
</script>
<script src="/assets/js/src/lib/websocket/client.js"></script>
<script src="/assets/js/src/lib/eventdispatcher.js"></script>

<script type='text/javascript'>
    <?php if(preg_match('/(?i)msie [4-9]/',$_SERVER['HTTP_USER_AGENT'])): ?>
        var badIE = true;
    <?php else: ?>
        var badIE = false;
    <?php endif; ?>

    var config = {
         base_url: "<?php echo base_url(); ?>",
         badIE : badIE
    };
    
    
    if (typeof jQuery.ui != 'undefined') {
        window.alert = function(message, detail){
        var detail = (typeof detail === "undefined") ? "" : '<hr/>'+detail;
        var html_content = '<b>'+message+'</b>'+detail;        
        $(document.createElement('div'))
            .attr({title: 'Easyshop.ph', class: 'alert'})
            .html(html_content)
            .dialog({
                buttons: {OK: function(){$(this).dialog('close');}},
                close: function(){$(this).remove();},
                draggable: true,
                modal: true,
                resizable: false,
                dialogClass: 'error-modal',
            });
        };
    }

   

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
    
    function serverTime() { 
        var time = null; 
        $.ajax({url: '/home/getServerTime', 
            async: false, dataType: 'text', 
            success: function(text) { 
                time = new Date(text); 
            }, error: function(http, message, exc) { 
                time = new Date(); 
        }}); 
        return time; 
    }

    function reload(){
        window.location.reload();
    }
</script>