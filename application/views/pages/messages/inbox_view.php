<script type="text/javascript" src="<?=base_url()?>assets/JavaScript/jquery.dataTables.min.js"></script>
<div class="container">
    <div id="head_container">       
        <div><input type="button" id="modal-launcher" value="Compose"></div>
		<div><span> <button id="chsn_delete_btn"> Delete selected </button> <button id="delete_all_btn"> Delete this conversation </button></span></div>
        <div id="loader"><input type="button" id="btn_refresh" title="REFRESH"></div> 
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
			<tr class="<?=(reset($row)['opened'] == 0 && reset($row)['status'] == "reciever" ? "NS" : "")?>">
				<td>
				<?PHP  if(reset($row)['status'] == "sender"){ ?>
					<img data="<?=reset($row)['sender_img']?>" src="<?=base_url().reset($row)['recipient_img']?>/60x60.png">
				<?PHP }else{ ?>
					<img data="<?=reset($row)['recipient_img']?>" src="<?=base_url().reset($row)['sender_img']?>/60x60.png">
				<?PHP } ?>
				</td>
				<td>
                    <?php 
                          $keys = array_keys($row);
                          $row[reset($keys)]['message'] = html_escape(reset($row)['message']);
                    ?>
                    
					<a class="btn_each_msg" id="ID_<?PHP echo reset($row)['name']; ?>" href="javascript:void(0)" data='<?=html_escape(json_encode($row))?>'>
						<span class="msg_sender"><?PHP echo reset($row)['name']; ?></span>
						<span class="msg_message"><?PHP echo html_escape(reset($row)['message']); ?></span>
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
			<img id="msg_loader" src="<?=base_url()?>assets/images/orange_loader.gif">
		</div>
		<div id="msg_textarea">
			<textarea id="out_txtarea" placeholder="Write a message"></textarea>
			<button id="send_btn" data="">Reply</button><img src="<?=base_url()?>assets/images/horizontal_bar_loader.gif">
		</div>
    </div>
</div>
<div id="modal-background">
    <img src="<?=base_url()?>assets/images/horizontal_loading.gif">
</div>
<div id="modal-container">
    <div id="modal-div-header">
        <button id="modal-close">X</button>        
    </div>
    <div id="modal-inside-container">
		<div>
			<label>To : </label>
			<input type="text" value="" id="msg_name" name="msg_name" placeholder="username">
		</div>
		<div>
			<label>Message : </label><br>
			<textarea cols="40" rows="5" name="msg-message" id="msg-message" placeholder="Your message here.."></textarea>		
		</div>	   
    </div>
    <button id="modal_send_btn">Send</button>
</div>

<script>
	$(document).ready(function() {
		$('#table_id').dataTable({
			"bScrollInfinite": true,
			"bScrollCollapse": false,
			"sScrollY": "375px"
		});
			
        $('#table_id_filter label input').prop('placeholder','Search').prop('id','tbl_search');
		$("#modal-background, #modal-close").click(function() {
			$("#modal-container, #modal-background").toggleClass("active");
			$("#modal-container").hide();
			$("#msg-message").val("");
		});
		
		$("#modal-launcher").click(function() {
			$("#modal-container, #modal-background").toggleClass("active");
			$("#modal-container").show();
		});
				
		
		$("#msg_textarea").on("click","#send_btn",function(){ // restrict textarea,button if doesnt have value 
			
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
		
    
        ////this is for page reload every time the user is focused on the web page/tab
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
        
        interval_function = function () {
             is_interval_running = true;
        }
       
    });
     
    function Reload() {
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');
        var result = "";
        $.ajax({
            asycn :true,
            type:"POST",
            dataType : "json",
            url : "<?=base_url()?>messages/get_all_msgs2",
			data : {csrfname:csrftoken},
			success : function(d) {
                if (d.unread_msgs != 0) {
                    onFocus_Reload(d.messages);
                }
            }            
        });
    }
	$("#modal_send_btn").on("click",function(){
		var recipient = $("#msg_name").val().trim();
		var msg = $("#msg-message").val().trim();
        if (msg == "") {
            alert("Say something..");
            return false;
        }
		if(send_msg(recipient,msg)){
			$("#modal-container, #modal-background").toggleClass("active");
			$("#modal-container").hide();
			$("#msg-message").val("");
			$("#msg_field").empty().append('<img id="msg_loader" src="'+config.base_url+'assets/images/orange_loader.gif">');
			$("#msg_textarea").hide();
			alert("Message sent");
		}else {
			alert("Try again");
            return false;
		}
	});   	
		
	$("#chsn_delete_btn").on("click",function(){
		var checked = $(".d_all:checked").map(function () {return this.value;}).get().join(",");
		var result = delete_data(checked);
		if(result != ""){
			tbl_data(result);
			$("#msg_field").empty().append('<img id="msg_loader" src="<?=base_url()?>assets/images/orange_loader.gif">');
			$("#msg_textarea").hide();
			$("#chsn_delete_btn").hide();            
		}else {
            location.reload();
		}
	});
	$("#delete_all_btn").on("click",function(){
		var checked = $(".d_all").map(function () {return this.value;}).get().join(",");	
		var result = delete_data(checked);	
		if(result != ""){
			tbl_data(result);
			$("#msg_field").empty().append('<img id="msg_loader" src="<?=base_url()?>assets/images/orange_loader.gif">');
			$("#msg_textarea").hide();
		}else {
            location.reload();
		}
	}); 
	
	function send_msg(recipient,msg){
		var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');
        
		var result = "";
		$.ajax({
			async : false,
			type : "POST",
			dataType : "json",
			url : "<?=base_url()?>messages/send_msg",
            beforeSend :function(){
                $("#msg_textarea img").show();
                $("#send_btn").hide();
            },
			data : {recipient:recipient,msg:msg,csrfname:csrftoken},
			success : function(data) {
                if (data != "false") {
                    result = data.messages;
                    tbl_data(result)
                }else{
                    alert("Username does not exist");
                    return false;
                }
			}
		});		
        $("#msg_textarea img").hide();
        $("#send_btn").show();
		return result;
	}
	$("#table_id tbody").on("click",".btn_each_msg",function(){	
		var D = eval('(' + $(this).attr('data') + ')');
		var html = "";
		$("#send_btn").attr("data","{'name':'"+$(this).children(":first").html()+"','img':'"+$(this).parent().parent().children(":first").children().attr("data")+"'}");
		$.each(D,function(key,val){
			if (val.status == "reciever") {
				html += '<span class="float_left">';
			} else {
				html += '<span class="float_right">';
			}
			html += '<img src="'+val.sender_img+'/60x60.png">';
			html += '<div></div>';
			html += '<input type="checkbox" class="d_all" value="'+val.id_msg+'">';
			html += '<p>'+escapeHtml(val.message)+'</p></span>';
			$("#msg_field").empty();
			$("#msg_field").prepend(html);
		});
		$("#msg_textarea").show();
		var objDiv = document.getElementById("msg_field");	
		objDiv.scrollTop = objDiv.scrollHeight;
		$("#head_container span").show();
		$(".btn_each_msg").removeClass("Active");
		$(this).addClass("Active");
		seened(this);
	});
	
	
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
	function specific_msgs() {
		var html = "";
		var all_messages = eval('('+ $(".Active").attr('data')+')');
        var objDiv = document.getElementById("msg_field");
		$.each(all_messages,function(key,val){
			if (val.status == "reciever") {
				html += '<span class="float_left">';
			} else {
				html += '<span class="float_right">';
			}
			html += '<img src="'+val.sender_img+'/60x60.png">';
			html += '<div></div>';
			html += '<input type="checkbox" class="d_all" value="'+val.id_msg+'">';
			html += '<p>'+escapeHtml(val.message)+'</p></span>';
			$("#msg_field").empty();
			$("#msg_field").append(html);
		});
		$("#out_txtarea").val("");
		$("#msg_textarea").show();
        objDiv.scrollTop = objDiv.scrollTop + 100;
	}
	$("#btn_refresh").on("click",function(){
		var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');
		var data = "";
		$.ajax({
			async:false,
			type : "POST",
			dataType : "json",
            onLoading:$("#btn_refresh").css('background-image','url("<?=base_url()?>/assets/images/ref-icon.gif")'),
			url : "<?=base_url()?>messages/get_all_msgs",
			data : {csrfname:csrftoken},
			success : function(d) {
                if (d.messages != 0) {
                    tbl_data(d.messages);
                    specific_msgs();                    
                    var objDiv = document.getElementById("msg_field");	
                    objDiv.scrollTop = objDiv.scrollHeight;
                    $("#send_btn").attr("data","{'name':'"+$(".Active").children(":first").html()+"','img':'"+$(".Active").parent().parent().children(":first").children().attr("data")+"'}");
                    $("#head_container span").show();
                }else{
                    location.reload();
                }
                $("#btn_refresh").css('background-image','url("<?=base_url()?>/assets/images/ref-icon2.png")');
			}   
		});
	});
    function tbl_data(D){
        html = "";
        $.each(D,function(key,val){
            var cnt = parseInt(Object.keys(val).length)- 1;
            var Nav_msg = D[key][Object.keys(val)[cnt]]; //first element of object
            html +='<tr class="'+(Nav_msg.opened == "0" && Nav_msg.status == "reciever" ? "NS" : "")+' odd">';
            html +='<td class=" sorting_1">';
            if (Nav_msg.status == "sender") {
                html +='<img src=<?=base_url()?>'+Nav_msg.recipient_img+'/60x60.png data="'+Nav_msg.sender_img+'">';
            }else {
                html +='<img src=<?=base_url()?>'+Nav_msg.sender_img+'/60x60.png data="'+Nav_msg.recipient_img+'">';
            }
            html +='</td>';
            html +='<td class=" ">';
            html +="<a class='btn_each_msg' id='ID_"+Nav_msg.name+"' data='"+ escapeHtml(JSON.stringify(val))+"' href='javascript:void(0)'>";
            html +='<span class="msg_sender">'+Nav_msg.name+'</span>';
            html +='<span class="msg_message">'+escapeHtml(Nav_msg.message)+'</span>';
            html +='<span class="msg_date">'+Nav_msg.time_sent+'</span>';
            html +='</a>';
            html +='</td>';
            html +='</tr>';
            $("#table_id tbody").empty();
            $("#table_id tbody").append(html);
        });
        $("#table_id a").first().addClass("Active");
	}
    function onFocus_Reload(D) {
		html = "";
		$.each(D,function(key,val){
			var cnt = parseInt(Object.keys(val).length)- 1;
			var Nav_msg = D[key][Object.keys(val)[cnt]]; //first element of object
            if ($('#ID_'+Nav_msg.name).length) {
                $('#ID_'+Nav_msg.name).children('.msg_message').text(escapeHtml(Nav_msg.message));
                $('#ID_'+Nav_msg.name).attr('data',JSON.stringify(val));
                $('#ID_'+Nav_msg.name).parent().parent().addClass('NS');
                if ($('#ID_'+Nav_msg.name).hasClass("Active")) {
                    specific_msgs();
                    seened($('#ID_'+Nav_msg.name));
                }
            }else{
                //append another div on tbl_data
                html +='<tr class="'+(Nav_msg.opened == "0" && Nav_msg.status == "reciever" ? "NS" : "")+' odd">';
                html +='<td class=" sorting_1">';
                if (Nav_msg.status == "sender") {
                    html +='<img src=<?=base_url()?>'+Nav_msg.recipient_img+'/60x60.png data="'+Nav_msg.sender_img+'">';
                }else {
                    html +='<img src=<?=base_url()?>'+Nav_msg.sender_img+'/60x60.png data="'+Nav_msg.recipient_img+'">';
                }
                html +='</td>';
                html +='<td class=" ">';
                html +="<a class='btn_each_msg' id='ID_"+Nav_msg.name+"' data='"+ escapeHtml(JSON.stringify(val))+"' href='javascript:void(0)'>";
                html +='<span class="msg_sender">'+Nav_msg.name+'</span>';
                html +='<span class="msg_message">'+escapeHtml(Nav_msg.message)+'</span>';
                html +='<span class="msg_date">'+Nav_msg.time_sent+'</span>';
                html +='</a>';
                html +='</td>';
                html +='</tr>';
                $("#table_id tbody").prepend(html);
            }
		});
    }
    $("#msg_field").on("click",".d_all",function(){
		if ($('.d_all').not(':checked').length == $('.d_all').length) {
			$("#chsn_delete_btn").hide();
		}else{
			$("#chsn_delete_btn").show();
		}
	});
	function delete_data(ids) {		
		var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');
		var data = "";
		$.ajax({
			async:false,
			type : "POST",
			dataType : "json",
			beforeSend: function(){
                $("#modal-background").show();
                $("#modal-background img").show();
                },
			url : "<?=base_url()?>messages/delete_msg",
			data : {id_msg:ids,csrfname:csrftoken},
			success : function(d) {
                data = d.messages;
			}
		});
		$("#modal-background").hide();
        $("#modal-background img").hide();
		return data;
	}

    function seened(obj) {
        //if ($(obj).parent().parent().attr('class').split(' ')[0] == "NS") {
        if ($(obj).parent().parent().hasClass("NS")) {
            var checked = $(".float_left .d_all").map(function () {return this.value;}).get().join(",");
            var csrftoken = $("meta[name='csrf-token']").attr('content');
            var csrfname = $("meta[name='csrf-name']").attr('content');
            $.ajax({
                async : false,
                type : "POST",
                dataType : "json",
                url : "<?=base_url()?>messages/is_seened",
                data : {checked:checked,csrfname:csrftoken},
                success : function(data) {
                    $(obj).parent().parent().removeClass('NS');
                }
            });
        }
    }
</script>





















