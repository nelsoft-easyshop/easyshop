(function ($) {
    var $userInfo = $('#userInfo');
    var $chatServer = $('#chatServer');
//    var socket = io.connect( 'https://' + $chatServer.data('host') + ':' + $chatServer.data('port'));
    $(document).ready(function()
    {
        /* Register events */
//        socket.on('send message', function( data ) {
//            onFocusReload(data.message);
//        });
//        setAccountOnline($userInfo.data('store-name'));

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
    });

    $("#modal_send_btn").on("click",function()
    {
        var recipient = $("#msg_name").val().trim();
        var msg = $("#msg-message").val().trim();
        if(recipient === ""){
            alert("Store name is required.");
            return false;
        }
        if (msg === "") {
            alert("Say something.");
            return false;
        }
        if(send_msg(recipient,msg, false)) {
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

    $("#table_id tbody").on("click",".btn_each_msg",function()
    {
        var msg = eval('(' + $(this).attr('data') + ')');
        var html = "";
        $("#chsn_username").html($(this).children(":first").html()).show();
        var name = $('#chsn_username').html();
        $("#send_btn").attr("data","{'img':'"+$(this).parent().parent().find('img').attr('data')+"'}");
        $("#userDataContainer").empty().html(name.trim());
        $("#msg_field").empty();
        var sortedObjectByKey = sortObjectByKey(msg);

        $.each(sortedObjectByKey,function(key, val) {
            if (typeof val !== "undefined") {
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
                $("#msg_field").append(html);
                html = "";
            }
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

    $("#msg_field").on("click",".d_all",function()
    {
        if ($('.d_all').not(':checked').length == $('.d_all').length) {
            $("#chsn_delete_btn").hide();
        }
        else{
            $("#chsn_delete_btn").show();
        }
    });

    var setAccountOnline = function(memberId)
    {
//        socket.emit('set account online', memberId);
    };

    function sortObjectByKey (obj)
    {
        var arrayOfObject = [];

        $.each(obj,function(key, val) {
            arrayOfObject[key] = val;
        });

        arrayOfObject.sort();

        return arrayOfObject;
    };

    function onFocusReload(msgs)
    {
        var html = "";
        var span = "";
        var message = msgs.messages;
        var onfocusedConversationId = $('.Active').attr('id');
        $("#table_id tbody").empty();
        $.each(message,function(key,val) {
            var cnt = parseInt(Object.keys(val).length)- 1;
            var isActive ='';
            var Nav_msg = message[key][Object.keys(val)[cnt]];
            if (typeof Nav_msg.name === "undefined") {
                for (var first_key in val) {
                    if (val.hasOwnProperty(first_key)) {
                        break;
                    }
                }
                Nav_msg = message[key][first_key];
            }
            var recipientName = escapeHtml(Nav_msg.name);
            if ($(".dataTables_empty").length) {
                $(".dataTables_empty").parent().remove();
            }
            if (onfocusedConversationId === 'ID_'+recipientName) {
                isActive = 'Active';
            }
            html +='<tr class="'+(Nav_msg.opened == "0" && Nav_msg.status == "receiver" ? "NS" : "")+' odd ">';
            html +='<td class=" sorting_1">';
            if (Nav_msg.status == "sender") {
                html +='<img src="' +config.assetsDomain+Nav_msg.recipient_img+'/60x60.png" data="'+Nav_msg.sender_img+'">';
            }
            else {
                html +='<img src="' +config.assetsDomain+Nav_msg.sender_img+'/60x60.png" data="'+Nav_msg.recipient_img+'">';
            }
            span = (Nav_msg.unreadConversationCount != 0 ? '<span class="unreadConve">('+Nav_msg.unreadConversationCount+')</span>' : "");
            html +='</td>';
            html +='<td class=" ">';
            html +="<a class='btn_each_msg " + isActive + "' id='ID_" + recipientName + "' data='"+ escapeHtml(JSON.stringify(val))+"' href='javascript:void(0)'>";
            html +='<span class="msg_sender">' + recipientName + '</span>'+span;
            html +='<span class="msg_message">'+escapeHtml(Nav_msg.message)+'</span>';
            html +='<span class="msg_date">'+Nav_msg.time_sent+'</span>';
            html +='</a>';
            html +='</td>';
            html +='</tr>';
        });
        $("#table_id tbody").append(html);
        $("#table_id a").first().addClass("Active");
        specific_msgs();
        seened($('.Active'));
        return true;
    }

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
                $("#out_txtarea").val("");
                $("#msg_textarea img").hide();
                $("#send_btn").show();
                if (parseInt(resultMsg.success) === 1) {
//                    socket.emit('send message', {recipient: recipient, message: resultMsg.recipientMessage });
                    if (onFocusReload(resultMsg.message) && !isOnConversation) {
                        $('#modal-close').trigger('click');
                    }
                    result = true;
                }
                else {
                    alert(resultMsg.errorMessage);
                    result = false;
                }
            }
        });
        return result;
    }

    function delete_data(ids)
    {
        var csrftoken = $("meta[name='csrf-token']").attr('content');
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
                    onFocusReload(result);
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
    
    function specific_msgs()
    {
        var html = "";
        var all_messages = eval('('+ $(".Active").attr('data')+')');
        $("#chsn_username").html($(".Active").children(":first").html()).show();
        var objDiv = document.getElementById("msg_field");
        $("#msg_field").empty();
        var sortedObjectByKey = sortObjectByKey(all_messages);
        $.each(sortedObjectByKey,function(key, val) {
            if (typeof val !== "undefined") {
                if (val.status == "receiver") {
                    html += '<span class="float_left">';
                }
                else {
                    html += '<span class="float_right">';
                }
                html += '<span class="chat-img-con"><span class="chat-img-con2"><img src="'+ config.assetsDomain + val.sender_img + '/60x60.png"></span></span>';
                html += '<div class="chat-container"><div></div>';
                html += '<input type="checkbox" class="d_all" value="'+val.id_msg+'">';
                html += '<p>'+escapeHtml(val.message)+'</p>';
                html += '<span class="msg-date">'+escapeHtml(val.time_sent)+'</span></span></div>';
                $("#msg_field").append(html);
                html = "";
            }
        });

        $("#msg_textarea").show();
        objDiv.scrollTop = objDiv.scrollTop + 100;
    }

    function seened(obj)
    {
        var $parentLi = $(obj).parent().parent();
        if ($parentLi.hasClass("NS") && $(obj).hasClass('Active')) {
            $(obj).children(".unreadConve").html("");
            var checked = $(".float_left .d_all").map(function () {return this.value;}).get().join("-");
            var csrftoken = $("meta[name='csrf-token']").attr('content');
            $.ajax({
                type : "POST",
                dataType : "json",
                url : "/MessageController/updateMessageToSeen",
                data : {checked:checked,csrfname:csrftoken},
                success : function(data) {
                    if (data === true) {
                        $parentLi.removeClass('NS');
                    }
                }
            });
        }
    }
})(jQuery);
