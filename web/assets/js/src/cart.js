(function ($) {

	$(window).on("load resize",function(){
		var browserWidth = $(window).width();
		var heightOfModal = $(".simplemodal-wrap").outerHeight();
		
		$('.circle-breadcrumb').animate({ background:'#000000' }, 3000);

		$(".cart-item-remove").click(function(){
			$(".remove-item-modal").modal();
			$(".remove-item-modal").parents(".simplemodal-container").addClass("my-modal").removeAttr("id").removeClass("feedback-modal-container");
			$(".my-modal").css("height", heightOfModal+"px");
		});

		$(".calculate-shipping-label").click(function(){
			$(".shipping-calculator-modal").modal({
				containerCss:{
					height: heightOfShippingModal
				}
			});
			$(".shipping-calculator-modal").parents(".simplemodal-container").addClass("my-modal").removeAttr("id").removeClass("feedback-modal-container");
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
