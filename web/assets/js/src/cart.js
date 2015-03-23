(function ($) {

	$(window).on("load resize",function(){
		var browserWidth = $(window).width();
		var heightOfModal = $(".simplemodal-wrap").outerHeight();
		
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
	});

	$(".payment-label").click(function(){
		var subCatContainer = $(this).parents(".payment-method-container").find(".payment-method-desc").slideDown();
		var paymentName = $(this).parents(".payment-method-container").find("label").text();
		$(".payment-method-desc").not(subCatContainer).slideUp();
		$(this).parents(".payment-method-container").find(".payment-method-desc").slideDown();		
		$(".btn-payment-button").text("Pay Via "+paymentName);
	});

	$(".btn-change-shipping").click(function(){
		$(".div-change-shipping-btn").slideToggle("fast");
		$(".div-save-shipping-btn").slideToggle("fast");
		$("#fname, #lname, #contact, #fullAddress").removeAttr("readonly");
		$("#city, #state").removeAttr("disabled");
	});

	$(".btn-change-shipping-cancel").click(function(){
		$(".div-change-shipping-btn").slideToggle("fast");
		$(".div-save-shipping-btn").slideToggle("fast");
		$("#fname, #lname, #contact, #fullAddress").attr("readonly", "true");
		$("#city, #state").attr("disabled", "true");
		$("#fname, #lname, #contact, #fullAddress").prop("readonly", "true");
		$("#city, #state").prop("disabled", "true");
	});
})(jQuery);
