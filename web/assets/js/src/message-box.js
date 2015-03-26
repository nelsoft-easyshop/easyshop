(function ($) {
	$(window).on("load", function(){
		$(".message-box").fadeIn().modal({
	        onOpen: function(dialog) {
		            dialog.overlay.fadeIn('fast', function () {
		                dialog.container.fadeIn('fast', function () {
		                    dialog.data.fadeIn('fast');
		                });
		            });
		        },
		    onClose: function (dialog) {
			    dialog.data.fadeOut('fast', function () {
			        dialog.container.fadeOut('fast', function () {
			            dialog.overlay.fadeOut('fast', function () {
			                $.modal.close();
			            });
			        });
			    });
			}
	    });
		$(".message-box").parents(".simplemodal-container").removeAttr("id").addClass("my-modal");
		$(".my-modal").css("height", "403px");
		var containerHeight = $(".my-modal").outerHeight();
		$(".message-container").css("height", containerHeight);


	});
		
}(jQuery));


