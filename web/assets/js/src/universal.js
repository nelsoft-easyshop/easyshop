    
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
                "box-shadow": "0px 0px 2px 2px #FF0000"});
    $(idclass).focus();
} 

function validateWhiteTextBox(idclass){
    $(idclass).css({"-webkit-box-shadow": "0px 0px 2px 2px #FFFFFF",
                "-moz-box-shadow": "0px 0px 2px 2px #FFFFFF",
                "box-shadow": "0px 0px 2px 2px #FFFFFF"});
}


