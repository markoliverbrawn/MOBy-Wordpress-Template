jQuery(window).resize(function(){
	//Make the boxes equal heights
	if(jQuery('.boxes'))
	{
		waitForFinalEvent(function(){
			jQuery('.boxes-item').css('height','auto');
			if(jQuery(window).width()>=768)
			{
				jQuery('.boxes').each(function(i,v){
					var max = 0;
					var $boxset = jQuery(v);
					$boxset.find('.boxes-item').each(function(i,v){
						if(jQuery(v).outerHeight()>max)
						{
							max = jQuery(v).outerHeight();
						}
					});
					$boxset.find('.boxes-item').css({height:max});

					//console.log(max);
				});
			}
		}, 100, "make-boxes-equal-heights");
	}
});
waitForFinalEvent(function(){
	//try{console.log('Resizing boxes');}catch(e){}
	jQuery(window).trigger('resize');
}, 100, 'resizeBoxesOnResize');