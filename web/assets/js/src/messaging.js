(function ($) {

    var $userInfo = $('#userInfo');
    var $chatServer = $('#chatServer');
    var socket = io.connect( 'https://' + $chatServer.data('host') + ':' + $chatServer.data('port'));

    $(document).ready(function () {
        /* Register events */
        socket.on('send message', function( data ) {
            onFocusReload(data.message);
        });
        setAccountOnline($userInfo.data('store-name'));
    });

    var setAccountOnline = function(memberId) {
        socket.emit('set account online', memberId);
    };

    /**
     * @param {type} msgs
     * @returns {undefined}
     */
    function onFocusReload(msgs)
    {
        var html = "";
        var span = "";
        var message = msgs.messages;
        $.each(message,function(key,val){
            var cnt = parseInt(Object.keys(val).length)- 1;
            if(/^((?!chrome).)*safari/i.test(navigator.userAgent)){ //if safari
                for (var first_key in val) if (val.hasOwnProperty(first_key)) break;
                var Nav_msg = message[key][first_key]; //first element of object
            }else{
                var Nav_msg = message[key][Object.keys(val)[cnt]]; //first element of object
            }
            var recipientName = escapeHtml(Nav_msg.name);
            if (parseInt($('#ID_'+recipientName).length) === 1) { //if existing on the conve
                $('#ID_'+recipientName).children('.msg_message').text(Nav_msg.message);
                $('#ID_'+recipientName).attr('data',JSON.stringify(val));
                if (Nav_msg.unreadConversationCount != 0) {
                    $('#ID_'+recipientName).parent().parent().addClass('NS');
                    $('#ID_'+recipientName+" .unreadConve").html("("+Nav_msg.unreadConversationCount+")");
                }
                if ($('#ID_'+recipientName).hasClass("Active")) {//if focus on the conve
                    specific_msgs();
                    seened($('#ID_'+recipientName));
                    $('#ID_'+recipientName+" .unreadConve").html("");
                }
                html = $('#ID_'+recipientName).parent().parent();
            }
            else{
                if($(".dataTables_empty").length){
                    $(".dataTables_empty").parent().remove();
                }
                html +='<tr class="'+(Nav_msg.opened == "0" && Nav_msg.status == "receiver" ? "NS" : "")+' odd">';
                html +='<td class=" sorting_1">';
                if (Nav_msg.status == "sender") {
                    html +='<img src="' +config.assetsDomain+Nav_msg.recipient_img+'/60x60.png" data="'+Nav_msg.sender_img+'">';
                } else {
                    html +='<img src="' +config.assetsDomain+Nav_msg.sender_img+'/60x60.png" data="'+Nav_msg.recipient_img+'">';
                }
                span = (Nav_msg.unreadConversationCount != 0 ? '<span class="unreadConve">('+Nav_msg.unreadConversationCount+')</span>' : "");
                html +='</td>';
                html +='<td class=" ">';
                html +="<a class='btn_each_msg' id='ID_" + recipientName + "' data='"+ escapeHtml(JSON.stringify(val))+"' href='javascript:void(0)'>";
                html +='<span class="msg_sender">' + recipientName + '</span>'+span;
                html +='<span class="msg_message">'+escapeHtml(Nav_msg.message)+'</span>';
                html +='<span class="msg_date">'+Nav_msg.time_sent+'</span>';
                html +='</a>';
                html +='</td>';
                html +='</tr>';
            }
        });
        if(msgs.unread_msgs_count === "true"){
            $("#table_id tbody").prepend(html);
            arrage_by_timeSent();
        }
        else{
            $("#table_id tbody").append(html);
            $("#table_id a").first().addClass("Active");
        }
        return true;
    }

    $(document).ready(function()
    {
        $('#table_id').dataTable({
            "bScrollInfinite": true,
            "bScrollCollapse": false,
            "sScrollY": "375px"
        });
        $("#table_id_info").hide();
        
        $('#table_id_filter label input').prop('placeholder','Search').prop('id','tbl_search').prop('class','ui-form-control');
        $('#tbl_search').hide();
        $("#modal-background, #modal-close").click(function() {
            $("#modal-container, #modal-background").toggleClass("active");
            $("#modal-container").hide();
            $("#msg-message").val("");
            $("#msg_name").val("");
        });

        $("#modal-launcher").click(function() {
            $("#modal-container, #modal-background").toggleClass("active");
            $("#modal-container").show();
        });

        $("#msg_textarea").on("click","#send_btn",function(){

            var D = eval('(' + $(this).attr('data') + ')');
            var recipient = $('#userDataContainer').html().trim();
            var img = D.img;
            var msg = $("#out_txtarea").val();
            if (msg == "") {
                return false;
            }
            send_msg(recipient,msg, true);

            var objDiv = document.getElementById("msg_field");
            objDiv.scrollTop = objDiv.scrollHeight;
        });

        $(function()
        {
            initSectorUI();
            $("#navigator a").click(function()
            {
                showSectorMini($(this).attr('href'));
            });
        });

        var initSectorUI = function(){
            if (location.hash) showSectorMini(location.hash);
        };

        var showSectorMini = function(sector){
            var username = sector.replace('#', '');
            $("#msg_name").val(username);
            $("#modal-container, #modal-background").toggleClass("active");
            $("#modal-container").show();
        };

        /*
         * We only enable this when web socket fails
         */
        if (!("WebSocket" in window)) {

            //this is for page reload every time the user is focused on the web page/tab
            var myInterval;
            var interval_delay = 5000;
            var is_interval_running = false;

            $(document).ready(function () {
                $(window).focus(function () {
                    clearInterval(myInterval);
                    if  (!is_interval_running)
                        myInterval = setInterval(Reload, interval_delay);
                }).blur(function () {
                    clearInterval(myInterval);
                    is_interval_running = false;
                });
            });

            interval_function = function ()
            {
                is_interval_running = true;
            }
        }
        arrage_by_timeSent();

    });


function Reload()
{
    var csrftoken = $("meta[name='csrf-token']").attr('content');
    var todo = "Get_UnreadMsgs";
    $.ajax({
        type:"POST",
        dataType : "json",
        url : "/MessageController/getAllMessage",
        data : {csrfname:csrftoken,isUnread:todo},
        success : function(d)
        {
            $(".msg_countr").html(d.unread_msgs_count);
            if(parseInt(d.unread_msgs_count) === 0 ){
                $('#unread-messages-count').addClass('unread-messages-count-hide');
            }else{
                $('#unread-messages-count').removeClass('unread-messages-count-hide');
            }
            document.title = (d.unread_msgs_count == 0 ? "Message | Easyshop.ph" : "Message (" + d.unread_msgs_count + ") | Easyshop.ph");

            if (d.unread_msgs_count != 0) {
                onFocus_Reload(d);
            }
        }
    });
}

$("#modal_send_btn").on("click",function(){
    var recipient = $("#msg_name").val().trim();
    var msg = $("#msg-message").val().trim();

    if(recipient == ""){
        alert("Username is required.");
        return false;
    }

    if (msg == "") {
        alert("Say something.");
        return false;
    }

    if(send_msg(recipient,msg, false)){
        $("#modal-container, #modal-background").toggleClass("active");
        $("#modal-container").hide();
        $("#msg-message").val("");
        $("#msg_field").empty();
        $("#msg_textarea").hide();
        $("#msg_name").val("");
        alert("Your message has been sent");
    }
    else {
        return false;
    }
});

$("#chsn_delete_btn").on("click",function()
{
    var checked = $(".d_all:checked").map(function () {return this.value;}).get().join(",");
    delete_data(checked);
});

$("#delete_all_btn").on("click",function()
{
    var checked = $(".d_all").map(function () {return this.value;}).get().join(",");
    delete_data(checked);
});

function send_msg(recipient,msg, isOnConversation)
{
    var csrftoken = $("meta[name='csrf-token']").attr('content');

    var result = false;
    $.ajax({
        type : "POST",
        dataType : "json",
        url : "/MessageController/send",
        beforeSend :function(){
            $("#msg_textarea img").show();
            $("#send_btn").hide();
        },
        data : {recipient:recipient,msg:msg,csrfname:csrftoken},
        success : function(resultMsg)
        {
            var data = resultMsg.message;
            $("#msg_textarea img").hide();
            $("#send_btn").show();
            if (data.success != 0) {
                socket.emit('send message', {recipient: recipient, message: resultMsg.recipientMessage });
               if (onFocusReload(data) && !isOnConversation) {
                   $('#modal-close').trigger('click');
                }
                result = true;
            }else{
                alert(data.msg);
                result = false;
            }
        }
    });
    return result;
}


$("#table_id tbody").on("click",".btn_each_msg",function()
{
    var D = eval('(' + $(this).attr('data') + ')');
    var html = "";
    $("#chsn_username").html($(this).children(":first").html()).show();
    var name = $('#chsn_username').html();
    $("#send_btn").attr("data","{'img':'"+$(this).parent().parent().find('img').attr('data')+"'}");
    $("#userDataContainer").empty().html(name.trim());

    $("#msg_field").empty();
    $.each(D,function(key,val){
        if (val.status == "receiver") {
            html += '<span class="float_left">';
        }
        else {
            html += '<span class="float_right">';
        }
        html += '<span class="chat-img-con"><span class="chat-img-con2"><img src="'+ config.assetsDomain +val.sender_img+'/60x60.png"></span></span>';
        html += '<div class="chat-container"><div></div>';
        html += '<input type="checkbox" class="d_all" value="'+val.id_msg+'">';
        html += '<p>'+escapeHtml(val.message)+'</p>';
        html += '<span class="msg-date">'+escapeHtml(val.time_sent)+'</span></span></div>';
        if(/^((?!chrome).)*safari/i.test(navigator.userAgent)){ //if safari
            $("#msg_field").prepend(html);
        }else{
            $("#msg_field").append(html);
        }
        html = "";
    });
    $("#msg_textarea").show();
    var objDiv = document.getElementById("msg_field");
    objDiv.scrollTop = objDiv.scrollHeight;
    $("#delete_all_btn").show();
    $("#chsn_delete_btn").hide();
    $(".btn_each_msg").removeClass("Active");
    $(this).addClass("Active");
    $("#"+this.id+" .msg_sender span").remove();
    seened(this);
});

function specific_msgs()
{
    var html = "";
    var all_messages = eval('('+ $(".Active").attr('data')+')');
    var objDiv = document.getElementById("msg_field");
    $("#msg_field").empty();
    $.each(all_messages,function(key,val){
        if (val.status == "receiver") {
            html += '<span class="float_left">';
        } else {
            html += '<span class="float_right">';
        }
        html += '<span class="chat-img-con"><span class="chat-img-con2"><img src="'+ config.assetsDomain + val.sender_img + '/60x60.png"></span></span>';
        html += '<div class="chat-container"><div></div>';
        html += '<input type="checkbox" class="d_all" value="'+val.id_msg+'">';
        html += '<p>'+escapeHtml(val.message)+'</p>';
        html += '<span class="msg-date">'+escapeHtml(val.time_sent)+'</span></span></div>';

        if(/^((?!chrome).)*safari/i.test(navigator.userAgent)){ //if safari
            $("#msg_field").prepend(html);
        }
        else{
            $("#msg_field").append(html);
        }
        html = "";
    });
    $("#out_txtarea").val("");
    $("#msg_textarea").show();
    objDiv.scrollTop = objDiv.scrollTop + 100;
}

function onFocus_Reload(msgs)
{
    var html = "";
    var span = "";
    D = msgs.messages;
    $.each(D,function(key,val){
        var cnt = parseInt(Object.keys(val).length)- 1;
        if(/^((?!chrome).)*safari/i.test(navigator.userAgent)){ //if safari
            for (var first_key in val) if (val.hasOwnProperty(first_key)) break;
            var Nav_msg = D[key][first_key]; //first element of object
        }else{
            var Nav_msg = D[key][Object.keys(val)[cnt]]; //first element of object
        }
        if ($('#ID_'+Nav_msg.name).length) { //if existing on the conve
            $('#ID_'+Nav_msg.name).children('.msg_message').text(Nav_msg.message);
            $('#ID_'+Nav_msg.name).children('.msg_date').text(Nav_msg.time_sent);
            $('#ID_'+Nav_msg.name).attr('data',JSON.stringify(val));
            $('#ID_'+Nav_msg.name).parent().parent().addClass('NS');
            $('#ID_'+Nav_msg.name+" .unreadConve").html("("+Nav_msg.unreadConversationCount+")");
            if ($('#ID_'+Nav_msg.name).hasClass("Active")) {//if focus on the conve
                specific_msgs();
                seened($('#ID_'+Nav_msg.name));
                $('#ID_'+Nav_msg.name+" .unreadConve").html("");
            }
            html = $('#ID_'+Nav_msg.name).parent().parent();
        }
        else{
            if($(".dataTables_empty").length){
                $(".dataTables_empty").parent().remove();
            }
            html +='<tr class="'+(Nav_msg.opened == "0" && Nav_msg.status == "receiver" ? "NS" : "")+' odd">';
            html +='<td class=" sorting_1">';
            if (Nav_msg.status == "sender") {
                html +='<div class="img-wrapper-div"><span class="img-wrapper-span"><img src='+ config.assetsDomain + Nav_msg.recipient_img+'/60x60.png data="'+Nav_msg.sender_img+'"></span></div>';
            }
            else {
                html +='<div class="img-wrapper-div"><span class="img-wrapper-span"><img src='+ config.assetsDomain + Nav_msg.sender_img+'/60x60.png data="'+Nav_msg.recipient_img+'"></span></div>';
            }
            span = (Nav_msg.unreadConversationCount != 0 ? '<span class="unreadConve">('+Nav_msg.unreadConversationCount+')</span>' : "");
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
    if(msgs.isUnreadMessages === "true"){
        $("#table_id tbody").prepend(html);
        arrage_by_timeSent();
    }
    else{
        $("#table_id tbody").append(html);
        $("#table_id a").first().addClass("Active");
    }
}

$("#msg_field").on("click",".d_all",function()
{
    if ($('.d_all').not(':checked').length == $('.d_all').length) {
        $("#chsn_delete_btn").hide();
    }
    else{
        $("#chsn_delete_btn").show();
    }
});

function arrage_by_timeSent()
{
    $("#table_id tbody tr").each(function(){
        var d = new Date();
        var msg_date_top =  new Date($("#table_id tbody").children().first().find('.msg_date').text().replace(/-/g,'/')).getTime();
        var dt =  new Date($(this).find('.msg_date').text().replace(/-/g,'/')).getTime();
        var tr_class = $(this).attr('class');
        var new_tr = '<tr class ="' + tr_class + '">' + $(this).html() + '</tr>';
        if(dt > msg_date_top){
            $(this).remove();
            $("#table_id tbody").prepend(new_tr);
        }

    });
}

function delete_data(ids)
{
    var csrftoken = $("meta[name='csrf-token']").attr('content');
    var csrfname = $("meta[name='csrf-name']").attr('content');
    var data = "";
    $.ajax({
        type : "POST",
        dataType : "json",
        beforeSend: function(){
            $("#modal-background").show();
            $("#modal-background img").show();
        },
        url : "/MessageController/delete",
        data : {id_msg:ids,csrfname:csrftoken},
        success : function(result) {
            if(result.messages != ""){
                $("#table_id tbody").empty();
                onFocus_Reload(result);
                $("#msg_field").empty();
                $("#msg_textarea").hide();
                $("#chsn_delete_btn,#delete_all_btn,#chsn_username").hide();
            }
            else {
                location.reload();
            }
        }
    });
    $("#modal-background").hide();
    $("#modal-background img").hide();

}

function seened(obj)
{
    var $parentLi = $(obj).parent().parent();
    if ($parentLi.hasClass("NS") && $(obj).hasClass('Active')) {
        $(obj).children(".unreadConve").html("");
        var checked = $(".float_left .d_all").map(function () {return this.value;}).get().join(",");
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        $.ajax({
            type : "POST",
            dataType : "json",
            url : "/MessageController/updateMessageToSeen",
            data : {checked:checked,csrfname:csrftoken},
            success : function(data) {
                if (data === true) {
                    $parentLi.removeClass('NS');
                }else{
                    alert("Error loading the message.");
                }
            }
        });
    }
}

})(jQuery);

