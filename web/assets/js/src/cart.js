(function ($) {
	$(window).on("load resize",function(){
		
		$(".cart-item-remove").click(function(){
			$(".remove-item-modal").modal();
			var heightOfRemoveItemModal = $("remove-item-modal").outerHeight();
			$(".remove-item-modal").parents(".simplemodal-container").addClass("my-modal").removeAttr("id").removeClass("feedback-modal-container");
			$(".my-modal").css("height", heightOfRemoveItemModal+"px");
		});
	});
})(jQuery);
