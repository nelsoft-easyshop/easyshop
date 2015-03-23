(function ($) {
	$(window).on("load resize",function(){
		
		$(".cart-item-remove").click(function(){
			$(".remove-item-modal").modal();
			var heightOfRemoveItemModal = $("remove-item-modal").outerHeight();
			$(".remove-item-modal").parents(".simplemodal-container").addClass("my-modal").removeAttr("id").removeClass("feedback-modal-container");
			$(".my-modal").css("height", heightOfRemoveItemModal+"px");
		});

		$(".calculate-shipping-label").click(function(){
			$(".shipping-calculator-container").slideToggle("fast");
			$(this).toggleClass("toggleShippingIcon");
		});

		$(".payment-label").click(function(){
			var subCatContainer = $(this).parents(".payment-method-container").find(".payment-method-desc").slideDown();
			var paymentName = $(this).parents(".payment-method-container").find("label").text();
			$(".payment-method-desc").not(subCatContainer).slideUp();
			$(this).parents(".payment-method-container").find(".payment-method-desc").slideDown();		
			$(".btn-payment-button").text("Pay Via "+paymentName);
		});

	});
})(jQuery);
