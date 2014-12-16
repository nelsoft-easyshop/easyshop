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

var OnProgress = function(myvars){
    return function(event, position, total, percentComplete){
        var progressbar = $('#progressbar');
        var statustxt = $('#statustxt');
        progressbar.width(percentComplete + '%') //update progressbar percent complete
        statustxt.html(percentComplete + '%'); //update status text
        if(percentComplete>50)
            {
                statustxt.css('color','#fff'); //change status text to white after 50%
            }
    }
}
 
var jbImagesDialog = {
    
    resized : false,
    iframeOpened : false,
    timeoutStore : false,
    myformdata : [],
    isInProgress : false,
    uploadCounter : 1,
    hasError : false,
    hasSuccess : false,
    allowedFileType : ['jpg', 'gif', 'png', 'jpeg'],
    
    inProgress : function() {
        var oldIE;
        var isSafari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0;
        
        if ($('html').is('.ie')) {
            oldIE = true;
        }

        if (oldIE || isSafari) {
            document.getElementById('upl1').submit();
        } 
        else{
            var origForm = document.getElementById('upl' + this.uploadCounter);
            var filenames = origForm.children[0].children[0].files;
            
            var filesAllowed = true;
            var notAllowedFiles = "";
            
            //check if all file extensions are allowed, break if one is not allowed
            for(var i = 0; i<filenames.length; i++){
                var fileExtension = filenames[i].name.split('.').pop();
                if ( this.allowedFileType.join('|').indexOf(fileExtension.toLowerCase()) == -1 ){
                    filesAllowed = false;
                    notAllowedFiles += filenames[i].name + '\n';
                }
            }

            if(filesAllowed){
                var uploadlist = document.getElementById("fileupload_list");
                for (var i = 0; i<filenames.length; i++){
                    var fileExtension = filenames[i].name.split('.').pop();
                    uploadlist.innerHTML += filenames[i].name + '<br>';
                    if ( filenames.length-1 == i ) {
                        uploadlist.innerHTML += '<br>';
                    }
                }
            
                if(this.isInProgress){
                    this.myformdata.push(origForm);
                } else {
                    this.isInProgress = true;
                    var options = {
                        target: '#upload_target',
                        uploadProgress: OnProgress('test')
                    };
                    $(origForm).ajaxSubmit(options);
                    document.getElementById('progressbox').style.display = 'block';
                }
                //var newForm = origForm.cloneNode(true);
                var newForm = document.getElementById('cloneForm').cloneNode(true);
                this.uploadCounter++;
                origForm.style.display = 'none';
                newForm.id = "upl" + this.uploadCounter;
                newForm.style.display = 'block';
                origForm.parentNode.appendChild(newForm);
            // If one of the files selected is not allowed, exit function.
            } else {
                alert('The following files are not allowed:\n\n' + notAllowedFiles);
                return false;
            }
        }
        
        document.getElementById("upload_infobar").style.display = 'none';
        document.getElementById("upload_additional_info").innerHTML = '';
        document.getElementById("upload_in_progress").style.display = 'block';
        
        if(!this.timeoutStore){
            this.timeoutStore = window.setTimeout(function(){
                document.getElementById("upload_additional_info").innerHTML = 'This is taking longer than usual.' + '<br />' + 'An error may have occurred.' + '<br /><a href="#" onClick="jbImagesDialog.showIframe()">' + 'View script\'s output' + '</a>';
                // tinyMCEPopup.editor.windowManager.resizeBy(0, 30, tinyMCEPopup.id);
            }, 100000);
        }
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
            this.hasError = true;
            
            if (this.resized == false)
            {
                // tinyMCEPopup.editor.windowManager.resizeBy(0, 30, tinyMCEPopup.id);
                this.resized = true;
            }
        }
        else
        {
            var w = this.getWin();
            tinymce = w.tinymce;
            var mybaseurl = tinymce.activeEditor.baseURI.source.replace('/assets/tinymce', '');

            tinymce.EditorManager.activeEditor.insertContent('<img src="' + result.filename +'" data-mce-src="' + result.filename +'">');

            this.hasSuccess = true;
        }

        //Added condition
        if(result.status == 'last'){
            $('#progressbar').width('100%');
            $('#statustxt').html('100%');
            if ( this.myformdata.length !== 0 ) {
                window.clearTimeout(this.timeoutStore);
                var options = {
                    target: '#upload_target',
                    uploadProgress: OnProgress('test')
                };
                $((this.myformdata)[0]).ajaxSubmit(options);
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
                this.hasError = this.hasSuccess = false;
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