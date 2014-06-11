(function ($) {
    $(document).ready(function () {
        
        /* Register events */
        easyshop.eventDispatcher.register('unreadMessages', function (unreadMessages) {
            document.title = (unreadMessages.unread_msgs === 0 ? "Message | Easyshop.ph" : "Message (" + unreadMessages.unread_msgs + ") | Easyshop.ph");
            if (unreadMessages.unread_msgs !== 0) {
                onFocusReload(unreadMessages);
            }
        });
    });
    
    /**
     * @param {type} msgs
     * @returns {undefined}
     */
    function onFocusReload(msgs) {
        html = "";
        var span = "";
        D = msgs.messages;
        $.each(D,function(key,val){
            var cnt = parseInt(Object.keys(val).length)- 1;
            var Nav_msg = D[key][Object.keys(val)[cnt]]; //first element of object
            if ($('#ID_'+Nav_msg.name).length) { //if existing on the conve
                $('#ID_'+Nav_msg.name).children('.msg_message').text(Nav_msg.message);
                $('#ID_'+Nav_msg.name).attr('data',JSON.stringify(val));
                $('#ID_'+Nav_msg.name).parent().parent().addClass('NS');
                $('#ID_'+Nav_msg.name+" .unreadConve").html("("+Nav_msg.unreadConve+")");
                if ($('#ID_'+Nav_msg.name).hasClass("Active")) {//if focus on the conve
                    specific_msgs();
                    seened($('#ID_'+Nav_msg.name));
                    $('#ID_'+Nav_msg.name+" .unreadConve").html("");
                }
            } else {
                html +='<tr class="'+(Nav_msg.opened == "0" && Nav_msg.status == "reciever" ? "NS" : "")+' odd">';
                html +='<td class=" sorting_1">';
                if (Nav_msg.status == "sender") {
                    html +='<img src=<?=base_url()?>'+Nav_msg.recipient_img+'/60x60.png data="'+Nav_msg.sender_img+'">';
                } else {
                    html +='<img src=<?=base_url()?>'+Nav_msg.sender_img+'/60x60.png data="'+Nav_msg.recipient_img+'">';
                }
                span = (Nav_msg.unreadConve != 0 ? '<span class="unreadConve">('+Nav_msg.unreadConve+')</span>' : "");
                html +='</td>';
                html +='<td class=" ">';
                html +="<a class='btn_each_msg' id='ID_"+Nav_msg.name+"' data='"+ escapeHtml(JSON.stringify(val))+"' href='javascript:void(0)'>";
                html +='<span class="msg_sender">'+Nav_msg.name+ '</span>'+span;
                html +='<span class="msg_message">'+escapeHtml(Nav_msg.message)+'</span>';
                html +='<span class="msg_date">'+Nav_msg.time_sent+'</span>';
                html +='</a>';
                html +='</td>';
                html +='</tr>';
            }
        });
        
        if (msgs.Case == "UnreadMsgs"){
            $("#table_id tbody").prepend(html);
        } else{
            $("#table_id tbody").append(html);
            $("#table_id a").first().addClass("Active");
        }
    }
})(window.jQuery);
