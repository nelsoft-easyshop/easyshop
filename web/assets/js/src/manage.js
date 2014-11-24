
$(document).ready(function() { 
    
    $(document).on('click',".showDiv",function (e){
        div = $(this).data('div');
        $('.div_content').hide();
        $('#'+div).show();
    });

    $(document).on('click',"#slideProductBtn",function (e){
        $('#slideProduct').ajaxForm({
            url: '/manage/editSlideProduct',
            type: 'POST', 
            dataType: 'json',
            success: function(d) {   
                if(d.e == 0){
                    alert('Successfully modified');
                }else{
                    alert(d.m);
                }
            }
        }); 
        $('#slideProduct').submit();
    });
        
    $(document).on('change',"#add_more_photo_input",function (e){
        $('.current_selected').append('<div class="main_images loader"><span><img src="/assets/images/orange_loader.gif" /></span><h3>Uploading Please Wait...</h3></div>');
        startUpload();

    });

    $(document).on('click','.removePic',function () { 
        var r = confirm('Are you sure you want to remove this photo in home page?');
        var div = $(this).data('div');
        if(r == true){
            $('#'+div).fadeOut(500, function() { $('#'+div).remove(); });
            removePhoto($(this).data('node'),div);

        }
    });
 
    $(document).on('click','.movePosition',function () { 
        div = $(this).data('div');
        action = ($(this).data('action') == 'up' ? 'up' : 'down');
        node = $(this).data('node'); 
        item = $('#'+div);

        if(action == 'up'){
            item.insertBefore(item.prev());
        }else{
            item.insertAfter(item.next());
        } 
        $.ajax({
            type: "GET",
            url: '/manage/moveNodeXml/'+action,
            data: "node="+ node,
            dataType: "json",
            cache: false,
            success: function(d) {
                console.log('moving '+action+' complete');
            }
        });
    });


    $(document).on('click','.editPic',function () {
        div = $(this).data('div');
        node = $(this).data('node');
        
        defx = $(this).data('ratiox');
        defxx = $(this).data('ratioxx');
        defy = $(this).data('ratioy');
        defyy = $(this).data('ratioyy');
        deftarget = $(this).data('link');

        $('#image_x').val(defx);
        $('#image_xx').val(defxx);  
        $('#image_y').val(defy);
        $('#image_yy').val(defyy);
        $('#link').val(deftarget);
        
        var jcrop_api, width, height;
        $('#image_prev').attr('src', '/' + node);

        var image = new Image();
        image.src = $('#image_prev').attr("src");
        width = image.naturalWidth;
        height = image.naturalHeight;

        $('#pop_up_image_edit').modal({
            escClose: false,
            containerCss:{
                maxWidth: 600,
                minWidth: 505 
            },
            onShow: function(dialog){
                $(dialog.container).draggable();
                $('#saveImage').on('click', function(){
                    node = encodeURI(node);
                    target = encodeURI($('#link').val());
                    getx = encodeURI($('#image_x').val());
                    getxx = encodeURI($('#image_xx').val());
                    gety = encodeURI($('#image_y').val());
                    getyy = encodeURI($('#image_yy').val());
                    $.ajax({
                        type: "GET",
                        url: '/manage/updateNodeXml',
                        data: "node="+ node+"&target="+target+"&getx="+getx+"&getxx="+getxx+"&gety="+gety+"&getyy="+getyy,
                        dataType: "json",
                        cache: false,
                        success: function(d) { 
                            $('#'+div+' .editPic').data('ratiox', getx);
                            $('#'+div+' .editPic').data('ratioy', gety);
                            $('#'+div+' .editPic').data('ratioxx', getxx);
                            $('#'+div+' .editPic').data('ratioyy', getyy);
                            $('#'+div+' .editPic').data('link', target);
                            alert('Image Modified!');
                            $.modal.close();
                        }
                    });
                });
                $('#resetCoordinates').on('click', function(){
                    $('#image_x').val(defx);
                    $('#image_xx').val(defxx);  
                    $('#image_y').val(defy);
                    $('#image_yy').val(defyy);
                    $('#link').val(deftarget);
                });
                jcrop_api = $.Jcrop($('#image_prev'),{ 
                    boxWidth: 500,
                    boxHeight: 500,
                    minSize: [width*0.1,height*0.1],
                    trueSize: [width,height],
                    onChange: showCoords,
                    onSelect: showCoords
                });
                this.setPosition();
            },
            onClose: function(){
                $('#image_prev').attr('src', ''); 
                jcrop_api.destroy();
                $('#image_prev span').after('<img src="" id="image_prev">');
                $.modal.close();
            }
        });
    });

    function showCoords(c){ 
        $('#image_x').val(c.x);
        $('#image_xx').val(c.x2);
        $('#image_y').val(c.y);
        $('#image_yy').val(c.y2);
    }
 
    function startUpload()
    {  
        $('#picform').ajaxForm({
            url: '/manage/uploadMainSlide',
            type: 'POST', 
            dataType: 'json',
            success: function(d) {   
               if(d.e == 0){
                    $('.current_selected > .loader').remove();
                    $('.current_selected').append('<div class="main_images" id="'+d.d+'"><a href="javascript:void(0)" data-node="'+d.u+'"  data-div="'+d.d+'" class="editPic imglink" data-ratiox="0" data-ratioy="0" data-ratioxx="0" data-ratioyy="0" data-link="home" >E</a>  <a data-node="'+d.u+'"  data-div="'+d.d+'" class="removePic imglink" href="javascript:void(0)">X</a>  <a data-node="'+d.u+'"  data-div="'+d.d+'" class="imglink movePosition moveUp" data-action="up" href="javascript:void(0)">&#10096;</a>  <a data-node="'+d.u+'"  data-div="'+d.d+'" class="imglink movePosition moveDown" data-action="down" href="javascript:void(0)">&#10097;</a><div><img src="/assets/images/mainslide/'+d.f+'"></div></div>');
               }else{
                    alert(d.m);
               }
            }
        }); 
        $('#picform').submit();
    }

    function removePhoto(node,id)
    {                 
        $.ajax({
            type: "GET",
            url: '/manage/removeMainSlide',
            data: "node="+ node,
            dataType: "json",
            cache: false,
            success: function(d) {}
        });
    }
});
