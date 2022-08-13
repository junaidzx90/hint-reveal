jQuery(document).ready(function ($) {
	$(".reveal_butn").on("click", function(){
		let ln = $(this).parent().find(".hintRevealTable").children().length;
		
		$(this).parent('.hintReveal').find(".hintRow.revealed").next('.hintRow').addClass("revealed");
		$(this).siblings('hintRow').removeClass('revealed');
		
		$(this).attr("data-reveald", parseInt($(this).attr("data-reveald"))+1);
		
		if(parseInt($(this).attr("data-reveald")) === ln){
			$(this).hide();
		}
	});
});