jQuery(window).resize(function(){
	//Make the boxes equal heights
	if(jQuery('.boxes'))
	{
		waitForFinalEvent(function(){
			jQuery('.boxes-item,.boxes-item>a').css('height','auto');
			jQuery('.boxes').each(function(i,v){
				var max = 0;
				var $boxset = jQuery(v);
				$boxset.find('.boxes-item').each(function(i,v){
					if(jQuery(v).height()>max)
					{
						max = jQuery(v).height();
					}
				});
				$boxset.find('.boxes-item,.boxes-item>a').css({height:max});

				console.log(max);
			});
		}, 100, "make-boxes-equal-heights");
	}
});
waitForFinalEvent(function(){
	try{console.log('Resizing boxes');}catch(e){}
	jQuery(window).trigger('resize');
	
}, 100, 'resizeBoxesOnResize');