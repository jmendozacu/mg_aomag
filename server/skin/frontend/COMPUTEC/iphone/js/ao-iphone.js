jQuery(function($) {
	
	$("footer .copyright").click(function() {
		if(!$(this).next().data("open")) {
			$(this).next().data("open", true)
			$(this).next().toggle();
			$("html, body").animate({scrollTop: $(document).height() - $(window).height() + "px"});
		} else {
			$(this).next().data("open", false)
			$(this).next().slideToggle();
		}
	});
	
	$("#menu .menu").click(function(e) {
		e.preventDefault();
		$(".menu-box").slideToggle();
	});
	
	$("#menu .recherche").click(function(e) {
		e.preventDefault();
		$(".search").slideToggle();
	});
	
	$(".share-with").click(function(e) {
		e.preventDefault();
		$(".addthis_sharing_toolbox").slideToggle();
	});
	
	$("#slideshow").bxSlider({
		controls: false
	});
	
	$(".filters-block h3").click(function(e) {
		e.preventDefault();
		$(".filters-block .block-content").slideToggle();
	});
	
	$("[for='sort_by']").text($("#sort_by").find("option:selected").text());
	
	$(".product-image-wrap ul").bxSlider({
		controls: false
	});
	
	$(".product-collateral .box-collateral > h2").click(function(e) {
		e.preventDefault();
		$(this).toggleClass("opened").next().slideToggle();
	});
	
	jQuery("p.agree a").live("click", function(e) { e.preventDefault(); jQuery(".agreement-content").show() });
	
	
});