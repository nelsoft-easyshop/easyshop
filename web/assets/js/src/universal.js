    
if (typeof jQuery.ui != 'undefined') {
    window.alert = function(message, detail){
    var detail = (typeof detail === "undefined") ? "" : '<hr/>'+detail;
    var html_content = '<b>'+message+'</b>'+detail;        
    $(document.createElement('div'))
        .attr({title: '', class: 'alert'})
        .html(html_content)
        .dialog({
            buttons: {OK: function(){$(this).dialog('close');}},
            close: function(){$(this).remove();},
            draggable: true,
            modal: true,
            resizable: false,
            dialogClass: 'error-modal',
            width:'auto',
            fluid : true
        });
    };
}

// on window resize run function
$(window).resize(function () {
    fluidDialog();
});

// catch dialog if opened within a viewport smaller than the dialog width
$(document).on("dialogopen", ".ui-dialog", function (event, ui) {
    fluidDialog();
});

function fluidDialog() {
    var $visible = $(".ui-dialog:visible");
    // each open dialog
    $visible.each(function () {
        var $this = $(this);
        var dialog = $this.find(".ui-dialog-content").data("ui-dialog");
        // if fluid option == true
        if (dialog.options.fluid) {
            //reposition dialog
            dialog.option("position", dialog.options.position);
        }
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

function replaceNumberWithCommas(thisnumber){
    //Seperates the components of the number
    var n= thisnumber.toString().split(".");
    //Comma-fies the first part
    n[0] = n[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    //Combines the two sections
    return n.join(".");
}

function validateRedTextBox(idclass){
    $(idclass).css({"-webkit-box-shadow": "0px 0px 2px 2px #FF0000",
                "-moz-box-shadow": "0px 0px 2px 2px #FF0000",
                "box-shadow": "0px 0px 2px 2px #FF0000",
                "-webkit-appearance":"none"}).addClass('my_err');
    $(idclass).focus();
} 

function validateWhiteTextBox(idclass){
    $(idclass).css({"-webkit-box-shadow": "0px 0px 2px 2px #FFFFFF",
                "-moz-box-shadow": "0px 0px 2px 2px #FFFFFF",
                "box-shadow": "0px 0px 2px 2px #FFFFFF",
                "-webkit-appearance":"none"}).removeClass('my_err');
}

function updateMessageCountIcons(){
    $.ajax({
        type:"get",
        dataType : "json",
        url : "/MessageController/getNumberOfUnreadMessages",
        success : function(count)
        {   
            var numberOfUnreadMessages = $.parseJSON(count);
            var title = '';
            
            if($('#original-title').length === 0){
                var originalTitleTag = document.createElement('meta');
                originalTitleTag.id = "original-title";
                originalTitleTag.name = "original-title";
                title = $(document).prop('title');
                originalTitleTag.content = title;
                document.getElementsByTagName('head')[0].appendChild(originalTitleTag);
            }
            else{
                title = $('#original-title').attr('content')
            }

            $('.msg_countr').html(numberOfUnreadMessages);
            if(parseInt(numberOfUnreadMessages) > 0){
                $(document).prop('title', '(' + numberOfUnreadMessages + ') ' + title);
                $('.msg_countr').css('display','inline-block');
            }
            else{
                $(document).prop('title', title);
                $('.msg_countr').css('display','none'); 
            }
        }
    }); 
    
}

