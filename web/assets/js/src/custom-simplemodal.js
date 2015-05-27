function getMarginTop()
{
	$(this).bind("myCustom click resize",function(){
	    var windowHeight = $(window).outerHeight(); //Height of window
	    var modalContentHeight = $("#simplemodal-data").outerHeight(); //Height modal content
	    var remainingVerticalSpace = windowHeight - modalContentHeight; //Remaining vertical space of the window when modal is present
	    var halfOfVerticalSpace = remainingVerticalSpace/2; //Half of the vertical space that will represent as the margin-top of the modal to center its vertical alignment

	    $(".simplemodal-container").css("top", halfOfVerticalSpace+"px").end(); 
	}).trigger("myCustom");

}


