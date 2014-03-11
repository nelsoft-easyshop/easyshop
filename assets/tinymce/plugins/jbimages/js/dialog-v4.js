/**
 * Justboil.me - a TinyMCE image upload plugin
 * jbimages/js/dialog-v4.js
 *
 * Released under Creative Commons Attribution 3.0 Unported License
 *
 * License: http://creativecommons.org/licenses/by/3.0/
 * Plugin info: http://justboil.me/
 * Author: Viktor Kuzhelnyi
 *
 * Version: 2.3 released 23/06/2013
 */
 
 /**
 *	Modified by Janz - 2/27/2014
 *	Added check for status in uploadFinish to trigger this.close()
 *
 */
var jbImagesDialog = {
	
	resized : false,
	iframeOpened : false,
	timeoutStore : false,
	myformdata : [],
	isInProgress : false,
	uploadCounter : 1,
	hasError : false,
	hasSuccess : false,
	
	inProgress : function() {
		document.getElementById("upload_infobar").style.display = 'none';
		document.getElementById("upload_additional_info").innerHTML = '';
		//document.getElementById("upload_form_container").style.display = 'none';
		document.getElementById("upload_in_progress").style.display = 'block';
		if(this.isInProgress) {
			var origForm = document.getElementById("upl" + this.uploadCounter);			
			this.myformdata.push(origForm);
			this.uploadCounter++;
		} else {
			this.isInProgress = true;
			var origForm = document.getElementById("upl");
			origForm.submit();
		}
		var newForm = origForm.cloneNode(true);
		origForm.style.display = 'none';
		newForm.id = "upl" + this.uploadCounter;
		origForm.parentNode.appendChild(newForm);
		var filenames = origForm.children[0].children[0].files;
		for (var i = 0; i<filenames.length; i++){
			var fileExtension = filenames[i].name.split('.').pop()
			var allowedFileType = ['jpg', 'gif', 'png'];
			if ( allowedFileType.join('|').indexOf(fileExtension) != -1 ){
				document.getElementById("fileupload_list").innerHTML += filenames[i].name + '<br>';
			}
		}
		
		this.timeoutStore = window.setTimeout(function(){
			document.getElementById("upload_additional_info").innerHTML = 'This is taking longer than usual.' + '<br />' + 'An error may have occurred.' + '<br /><a href="#" onClick="jbImagesDialog.showIframe()">' + 'View script\'s output' + '</a>';
			// tinyMCEPopup.editor.windowManager.resizeBy(0, 30, tinyMCEPopup.id);
		}, 20000);		
	},
	
	showIframe : function() {
		if (this.iframeOpened == false)
		{
			document.getElementById("upload_target").className = 'upload_target_visible';
			// tinyMCEPopup.editor.windowManager.resizeBy(0, 190, tinyMCEPopup.id);
			this.iframeOpened = true;
		}
	},
	
	uploadFinish : function(result) {
		if (result.resultCode == 'failed')
		{
			//window.clearTimeout(this.timeoutStore);
			//document.getElementById("upload_in_progress").style.display = 'none';
			//document.getElementById("upload_infobar").style.display = 'block';
			//document.getElementById("upload_infobar").innerHTML = result.result;
			//document.getElementById("upload_form_container").style.display = 'block';
			this.hasError = true;
			
			if (this.resized == false)
			{
				// tinyMCEPopup.editor.windowManager.resizeBy(0, 30, tinyMCEPopup.id);
				this.resized = true;
			}
		}
		else
		{
			//document.getElementById("upload_in_progress").style.display = 'none';
			//document.getElementById("upload_infobar").style.display = 'block';
			//document.getElementById("upload_infobar").innerHTML = 'Upload Complete';
			
			var w = this.getWin();
			tinymce = w.tinymce;
			
			tinymce.EditorManager.activeEditor.insertContent('<img src="' + result.filename +'">');
			
			this.hasSuccess = true;
			//Added condition
			/*if(result.status == 'last'){
				document.getElementById("upload_infobar").innerHTML = 'Upload Complete';
				//this.close();
			}*/
			
		}
		if ( this.myformdata.length !== 0 ) {
			(this.myformdata)[0].submit();
			this.myformdata.shift();
			document.getElementById("upload_infobar").style.display = 'none';
			document.getElementById("upload_infobar").innerHTML = '';
		} else {
			this.isInProgress = false;
			document.getElementById("upload_in_progress").style.display = 'none';
			document.getElementById("upload_infobar").style.display = 'block';
			if(this.hasSuccess && !this.hasError){
				document.getElementById("upload_infobar").innerHTML = 'Upload Complete';
			}
			else if(!this.hasSuccess && this.hasError){
				document.getElementById("upload_infobar").innerHTML = result.result;
			}
			else if(this.hasSuccess && this.hasError){
				document.getElementById("upload_infobar").innerHTML = 'Upload Complete. One or more files failed to upload.';
			}
		}
	},
	
	getWin : function() {
		return (!window.frameElement && window.dialogArguments) || opener || parent || top;
	},
	
	close : function() {
		var t = this;

		// To avoid domain relaxing issue in Opera
		function close() {
			tinymce.EditorManager.activeEditor.windowManager.close(window);
			tinymce = tinyMCE = t.editor = t.params = t.dom = t.dom.doc = null; // Cleanup
		};

		if (tinymce.isOpera)
			this.getWin().setTimeout(close, 0);
		else
			close();
	}

};