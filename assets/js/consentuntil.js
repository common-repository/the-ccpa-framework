jQuery(function ($) {
	var calvisible = false;
	$('.ccpa-consent-until-cal').click(function(){
		if(calvisible == false){
			$('.ccpa-consent-until').css('opacity', 1);
			calvisible = true;
		}else{
			$('.ccpa-consent-until').css('opacity', 0);
			calvisible = false;
		}		
	});
	$('.ccpa-consent-until').change(function(){
		$('.ccpa-consent-until').css('opacity', 0);

	});
});
