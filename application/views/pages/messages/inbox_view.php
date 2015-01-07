<script type="text/javascript" src="/assets/js/src/vendor/jquery.dataTables.min.js"></script>

<div class="container wrapper inbox-view-content">
    <div id="head_container">       
        <div>
            <input type="button" id="modal-launcher" value="Compose">
        </div>
        <div>
            <h3 id="chsn_username"></h3>
            <span>
                <button id="chsn_delete_btn"> Delete selected </button>
                <button id="delete_all_btn"> Delete this conversation </button>
            </span>
        </div>
    </div>
    <div id="panel_container">
        <table id="table_id">
            <thead>
            <tr>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?PHP foreach($result['messages'] as $key => $row) { ?>
                <tr class="<?=(reset($row)['opened'] == 0 && reset($row)['status'] == "receiver" ? "NS" : "")?>">
                    <td>
                        <div class="img-wrapper-div">
                            <span class="img-wrapper-span">
                            <?php if(reset($row)['status'] == "sender"): ?>
                                <img data="<?=reset($row)['sender_img']?>" src="/<?php echo reset($row)['recipient_img']?>/60x60.png">
                            <?php else: ?>
                                <img data="<?=reset($row)['recipient_img']?>" src="/<?php echo reset($row)['sender_img']?>/60x60.png">
                            <?php endif; ?>
                            <?php $span = (reset($row)['unreadConversationCount'] != 0 ? '('.reset($row)['unreadConversationCount'].')' : ""); ?>
                            </span>
                        </div>
                        
                    </td>
                    <td>
                        <a class="btn_each_msg" id="ID_<?PHP echo reset($row)['name']; ?>" href="javascript:void(0)" data='<?=html_escape(json_encode($row))?>'>
                        <span class="msg_sender"><?PHP echo reset($row)['name']."</span><span class=\"unreadConve\">".$span."</span>"; ?>
                            <?php
                            $keys = array_keys($row);
                            $row[reset($keys)]['message'] = html_escape(reset($row)['message']);
                            ?>
                            <span class="msg_message"><?PHP echo reset($row)['message']; ?></span>
                        <span class="msg_date"><?PHP echo reset($row)['time_sent']; ?></span>
                        </a>
                    </td>
                </tr>
            <?PHP
            }
            ?>
            </tbody>
        </table>
    </div>
    <div id="msg_inbox_container" class = "msg_container">
        <div id="msg_field">
            <!-- <img id="msg_loader" src="/assets/images/orange_loader.gif"> -->
        </div>
        <div id="msg_textarea">
            <textarea id="out_txtarea" placeholder="Write a message" class="ui-form-control"></textarea>
            <button id="send_btn" data="">Reply</button><img src="/assets/images/horizontal_bar_loader.gif">
        </div>
    </div>
</div>
<div id="modal-background">
    <img src="/assets/images/horizontal_loading.gif">
</div>
<div id="modal-container">
    <div id="modal-div-header">
        <button id="modal-close">X</button>
    </div>
    <div id="modal-inside-container" class="mrgn-top-10">
        <div>
            <label>To : </label>
            <input type="text" value="" id="msg_name" name="msg_name" placeholder="username" class="ui-form-control">
        </div>
        <div>
            <label>Message : </label><br>
            <textarea cols="40" rows="5" name="msg-message" id="msg-message" class="ui-form-control" placeholder="Your message here.."></textarea>		
        </div>
    </div>
    <button id="modal_send_btn">Send</button>
</div>

<script src="/assets/js/src/messaging.js"></script>
<script>
(function($)
{
    $(document).ready(function()
    {
        $('#table_id').dataTable({
            "bScrollInfinite": true,
            "bScrollCollapse": false,
            "sScrollY": "375px"
        });
        $("#table_id_info").hide();
        
        $('#table_id_filter label input').prop('placeholder','Search').prop('id','tbl_search').prop('class','ui-form-control');
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
            var recipient = D.name;
            var img = D.img;
            var msg = $("#out_txtarea").val();
            if (msg == "") {
                return false;
            }
            send_msg(recipient,msg);
            specific_msgs();

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

})(jQuery);

function Reload()
{
    var csrftoken = $("meta[name='csrf-token']").attr('content');
    var csrfname = $("meta[name='csrf-name']").attr('content');
    var result = "";
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

    if(send_msg(recipient,msg)){
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
    var result = delete_data(checked);
    if(result != ""){
        $("#table_id tbody").empty();
        onFocus_Reload(result);
        $("#msg_field").empty();
        $("#msg_textarea").hide();
        $("#chsn_delete_btn,#delete_all_btn,#chsn_username").hide();
    }
    else {
        location.reload();
    }
});

$("#delete_all_btn").on("click",function()
{
    var checked = $(".d_all").map(function () {return this.value;}).get().join(",");
    var result = delete_data(checked);
    if(result != ""){
        $("#table_id tbody").empty();
        onFocus_Reload(result);
        $("#msg_field").empty();
        $("#msg_textarea").hide();
        $("#chsn_delete_btn,#delete_all_btn,#chsn_username").hide();
    }
    else {
        location.reload();
    }
});

function send_msg(recipient,msg)
{
    var csrftoken = $("meta[name='csrf-token']").attr('content');
    var csrfname = $("meta[name='csrf-name']").attr('content');

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
        success : function(data)
        {
            $("#msg_textarea img").hide();
            $("#send_btn").show();
            if (data.success != 0) {
                $("#table_id tbody").empty();
                onFocus_Reload(data)
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
    $("#send_btn").attr("data","{'name':'"+$(this).children(":first").html()+"','img':'"+$(this).parent().parent().children(":first").children().attr("data")+"'}");
    $("#chsn_username").html($(this).children(":first").html()).show();

    $("#msg_field").empty();
    $.each(D,function(key,val){
        if (val.status == "receiver") {
            html += '<span class="float_left">';
        }
        else {
            html += '<span class="float_right">';
        }
        html += '<span class="chat-img-con"><span class="chat-img-con2"><img src="'+val.sender_img+'/60x60.png"></span></span>';
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
        html += '<span class="chat-img-con"><span class="chat-img-con2"><img src="'+val.sender_img+'/60x60.png"></span></span>';
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
    html = "";
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
                html +='<div class="img-wrapper-div"><span class="img-wrapper-span"><img src=/'+Nav_msg.recipient_img+'/60x60.png data="'+Nav_msg.sender_img+'"></span></div>';
            }
            else {
                html +='<div class="img-wrapper-div"><span class="img-wrapper-span"><img src=/'+Nav_msg.sender_img+'/60x60.png data="'+Nav_msg.recipient_img+'"></span></div>';
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
        success : function(d) {
            data = d;
        }
    });
    $("#modal-background").hide();
    $("#modal-background img").hide();
    return data;
}

function seened(obj)
{
    if ($(obj).parent().parent().hasClass("NS")) {
        $(obj).children(".unreadConve").html("");
        var checked = $(".float_left .d_all").map(function () {return this.value;}).get().join(",");
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');
        $.ajax({
            type : "POST",
            dataType : "json",
            url : "/MessageController/updateMessageToSeen",
            data : {checked:checked,csrfname:csrftoken},
            success : function(data) {
                if (data === true) {
                    $(obj).parent().parent().removeClass('NS');
                }else{
                    alert("Error loading the message.");
                }
            }
        });
    }
}

</script>
