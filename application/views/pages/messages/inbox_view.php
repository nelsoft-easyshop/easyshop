<script type="text/javascript" src="<?=base_url()?>assets/Javascript/jquery.dataTables.min.js"></script>
<div class="container">
    <div id="head_container">       
        <div><input type="button" id="modal-launcher" value="Compose"></div>
		<div><span>ACTIONS : <button id="chsn_delete_btn"> Delete selected </button> <button id="delete_all_btn"> Delete this conversation </button></span></div>
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
			<tr>
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
                    
					<a class="btn_each_msg" href="javascript:void(0)" data='<?=html_escape(json_encode($row))?>'>
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
			<button id="send_btn" data="">Reply</button>
		</div>
    </div>
</div>
<div id="modal-background">
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

<input type="hidden" id="csrf" name="<?php echo $my_csrf['csrf_name'];?>" value="<?php echo $my_csrf['csrf_hash'];?>">


<script>
	$(document).ready(function() {
		$('#table_id').dataTable({
			"bScrollInfinite": true,
			"bScrollCollapse": false,
			"sScrollY": "375px"
		});
			
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
			send_msg(recipient,msg);
			specific_msgs();
		});
		
    });
		
	$("#modal_send_btn").on("click",function(){
		var recipient = htmlspecialchars($("#msg_name").val().trim());
		var msg = $("#msg-message").val();
		if(send_msg(recipient,msg)){
			$("#modal-container, #modal-background").toggleClass("active");
			$("#modal-container").hide();
			$("#msg-message").val("");
			$("#msg_field").empty().append('<img id="msg_loader" src="'+config.base_url+'assets/images/orange_loader.gif">');
			$("#msg_textarea").hide();
			alert("Message sent");
		}else {
			alert("Try again");
		}
	});   	
		
	$("#chsn_delete_btn").on("click",function(){
		var checked = $(".d_all:checked").map(function () {return this.value;}).get().join(",");
		var result = delete_data(checked);
		if(result != ""){
			tbl_data(result);
			$("#msg_field").empty().append('<img id="msg_loader" src="<?=base_url()?>assets/images/orange_loader.gif">');
			$("#msg_textarea").hide();
		}else {
			alert ("Try again");
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
			alert ("Try again");
		}
	}); 
	
	function send_msg(recipient,msg){
		var csrftoken = $("#csrf").val().trim();
		var result = "";
		$.ajax({
			async : false,
			type : "POST",
			dataType : "json",
			url : "<?=base_url()?>messages/send_msg",
			data : {recipient:recipient,msg:msg,es_csrf_token:csrftoken},
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
			html += '<p>'+encodeURI(val.message)+'</p>';
			html += '<input type="checkbox" class="d_all" value="'+val.id_msg+'"></span>';
			$("#msg_field").empty();
			$("#msg_field").prepend(html);
		});
		$("#msg_textarea").show();
		var objDiv = document.getElementById("msg_field");	
		objDiv.scrollTop = objDiv.scrollHeight;
		$("#head_container span").show();
		$(".btn_each_msg").removeClass($(".btn_each_msg").attr('class').split(' ')[1]);
		$(this).addClass("Active");
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
    function tbl_data(D){
		html = "";
		$.each(D,function(key,val){
			var cnt = parseInt(Object.keys(val).length)- 1;
			var Nav_msg = D[key][Object.keys(val)[cnt]]; //first element of object
			html +='<tr class="odd">';
			html +='<td class=" sorting_1">';
			if (Nav_msg.status == "sender") {
				html +='<img src=<?=base_url()?>'+Nav_msg.recipient_img+'/60x60.png data="'+Nav_msg.sender_img+'">';
			}else {
				html +='<img src=<?=base_url()?>'+Nav_msg.sender_img+'/60x60.png data="'+Nav_msg.recipient_img+'">';
			}
			html +='</td>';
			html +='<td class=" ">';
			html +="<a class='btn_each_msg' data='"+ escapeHtml(JSON.stringify(val))+"' href='javascript:void(0)'>";
			html +='<span class="msg_sender">'+Nav_msg.name+'</span>';
			html +='<span class="msg_message">'+encodeURI(Nav_msg.message)+'</span>';
			html +='<span class="msg_date">'+Nav_msg.time_sent+'</span>';
			html +='</a>';
			html +='</td>';
			html +='</tr>';
			$("#table_id tbody").empty();
			$("#table_id tbody").append(html);
		});
		$("#table_id a").first().addClass("Active");
	}
	function specific_msgs() {
		var html = "";
		var all_messages = eval('('+ $(".Active").attr('data')+')');
		$.each(all_messages,function(key,val){
			if (val.status == "reciever") {
				html += '<span class="float_left">';
			} else {
				html += '<span class="float_right">';
			}
			html += '<img src="'+val.sender_img+'/60x60.png">';
			html += '<p>'+encodeURI(val.message)+'</p>';
			html += '<input type="checkbox" class="d_all" value="'+val.id_msg+'"></span>';
			$("#msg_field").empty();
			$("#msg_field").append(html);
		});
		$("#out_txtarea").val("");
		$("#msg_textarea").show();
		var objDiv = document.getElementById("msg_field");	
		objDiv.scrollTop = objDiv.scrollHeight;
	}
	$("#btn_refresh").on("click",function(){
		var csrftoken = $("#csrf").val().trim();
		var data = "";
		$.ajax({
			async:false,
			type : "POST",
			dataType : "json",
			url : "<?=base_url()?>messages/get_all_msgs",
			data : {es_csrf_token:csrftoken},
			success : function(d) {
                if (d.messages != 0) {
                    tbl_data(d.messages);
                    specific_msgs();
                    $("#head_container span").show();
                }else{
                    location.reload();
                }
			}   
		});
	});
	$("#msg_field").on("click",".d_all",function(){
		if ($('.d_all').not(':checked').length == $('.d_all').length) {
			$("#chsn_delete_btn").hide();
		}else{
			$("#chsn_delete_btn").show();
		}
	});
	function delete_data(ids) {		//loading when sql query
		var csrftoken = $("#csrf").val().trim();
		var data = "";
		$.ajax({
			async:false,
			type : "POST",
			dataType : "json",
			url : "<?=base_url()?>messages/delete_msg",
			data : {id_msg:ids,es_csrf_token:csrftoken},
			success : function(d) {
                if (d.messages != 0) {
                    data = d.messages;
                }else{
                    location.reload();
                }
			}
		});
		
		return data;
	}

</script>





















