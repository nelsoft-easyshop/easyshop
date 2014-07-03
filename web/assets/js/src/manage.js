
$(document).ready(function() { 
    
    $('#pop_up_image_edit , .div_content').hide();

    $(document).on('click',".showDiv",function (e){
        div = $(this).data('div');
        $('.div_content').hide();
        $('#'+div).show();
    });

    $(document).on('click',"#slideProductBtn",function (e){
        $('#slideProduct').ajaxForm({
            url: config.base_url+'manage/editSlideProduct',
            type: 'POST', 
            dataType: 'json',
            success: function(d) {   
                if(d.e == 0){
                    
                }else{
                    alert(d.m);
                }
            }
        }); 
        $('#slideProduct').submit();
    });

    $(document).on('change',"#add_more_photo_input",function (e){
        startUpload();
    });

    $("#add_more_photo").click(function(){
        $('#add_more_photo_input').click(); 
    });




    $(document).on('click','.removePic',function () { 
        removePhoto($(this).data('node'),$(this).data('div')); 
    });

    $(document).on('click','.moveUp',function () { 
        div = $(this).data('div');
        item = $('#'+div);
        item.insertBefore(item.prev());

        node = $(this).data('node');
        $.ajax({
            type: "GET",
            url: config.base_url +  'manage/moveNodeXml/up',
            data: "node="+ node,
            dataType: "json",
            cache: false,
            success: function(d) {
                
           }
       });
    });

    $(document).on('click','.moveDown',function () { 
        div = $(this).data('div');
        item = $('#'+div);
        item.insertAfter(item.next());

        node = $(this).data('node');
        $.ajax({
            type: "GET",
            url: config.base_url +  'manage/moveNodeXml/down',
            data: "node="+ node,
            dataType: "json",
            cache: false,
            success: function(d) {

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
        $('#image_prev').attr('src', config.base_url + node);

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
            onShow: function(){
                $('#saveImage').on('click', function(){
                    node = encodeURI(node);
                    target = encodeURI($('#link').val());
                    getx = encodeURI($('#image_x').val());
                    getxx = encodeURI($('#image_xx').val());
                    gety = encodeURI($('#image_y').val());
                    getyy = encodeURI($('#image_yy').val());
                    $.ajax({
                        type: "GET",
                        url: config.base_url +  'manage/updateNodeXml',
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
                    aspectRatio: width/height,
                    boxWidth: 500,
                    boxHeight: 500,
                    minSize: [width*0.1,height*0.1],
                    trueSize: [width,height],
                    onChange: showCoords,
                    onSelect: showCoords,
                    onRelease: resetCoords(defx,defxx,defy,defyy)
                });
                this.setPosition();
            },
            onClose: function(){
                $('#image_prev').attr('src', '');
                onRelease: resetCoords(defx,defxx,defy,defyy)
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

    function resetCoords(defx,defxx,defy,defyy){
        $('#image_x').val(defx);
        $('#image_xx').val(defxx);
        $('#image_y').val(defy);
        $('#image_yy').val(defyy);
    }

    function startUpload()
    {  
        $('#picform').ajaxForm({
            url: config.base_url+'manage/uploadMainSlide',
            type: 'POST', 
            dataType: 'json',
            success: function(d) {   
               if(d.e == 0){
                    $('.current_selected').append('<div id="'+d.d+'"><img src="'+config.base_url+'assets/images/mainslide/'+d.f+'"><a href="javascript:void(0)" data-node="'+d.u+'"  data-div="'+d.d+'" class="editPic" data-ratiox="0" data-ratioy="0" data-ratioxx="0" data-ratioyy="0" data-link="home" >edit</a> | <a data-node="'+d.u+'"  data-div="'+d.d+'" class="removePic" href="javascript:void(0)">remove</a> | <a data-node="'+d.u+'"  data-div="'+d.d+'" class="moveUp" href="javascript:void(0)">up</a> | <a data-node="'+d.u+'"  data-div="'+d.d+'" class="moveDown" href="javascript:void(0)">down</a></div>');
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
            url: config.base_url +  'manage/removeMainSlide',
            data: "node="+ node,
            dataType: "json",
            cache: false,
            success: function(d) {
                 $('#'+id).remove();
            }
        });
    }
});
