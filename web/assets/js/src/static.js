(function ($) {
	$(window).on("load", function(){

		//default
		var tabSrc= $( document ).find(".tab-head-container a.active").attr('src');
		$("#"+tabSrc).show();

		/*$(".tab-head-container li").click(function(){
			$(this).parent().prepend(this);
		});*/

		$(".tab-head-container a").click(function(){
			var tabLink = $(this).attr('src');
			var tabName = $(document).find("#"+tabLink);

			$(".tab-head-container a").not($(this)).removeClass("active");
			$(this).addClass("active");
			$(".tab-container").not(tabName).hide();
			tabName.show();
		});



		$(".accordion-head").click(function(){
			var accordionBody = $(this).parents(".accordion-item-container").find(".accordion-body")
			$(".accordion-head").not(this).removeClass("toggled");
			$(".accordion-body").not(accordionBody).slideUp("fast");
			$(this).toggleClass("toggled");
			accordionBody.slideToggle("fast");
		});


	});
})(jQuery);
