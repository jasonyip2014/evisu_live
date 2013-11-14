var LPScrollr = {
    scrollr : null,
    scale : 1,
    defWindowWidth : 1920,
    sectionsHeight : {},

    init : function(){
        var self = this;
        self.windowResize();

        // init scrollr
        self.scrollr = skrollr.init({
            edgeStrategy : 'set',
            scale : self.scale,
            easing: {
                WTF: Math.random,
                inverted: function(p) {
                    return 1-p;
                }
            }
        });
        jQuery('body,html').height('auto');
        // init sections
        jQuery.each(jQuery('.section-holder'), function(index, section)
        {
            self.sectionsHeight[index] = jQuery(section);
            self.sectionsHeight[index].origHeight = jQuery(section).height();
        });
        self.sectionsResize();
    },

    refresh : function(){
        var self = this;
        self.windowResize();
        self.sectionsResize();
        self.videoPanelsResize();
        self.scrollr.refresh(undefined, self.scale);
        jQuery('body,html').height('auto');
    },

    sectionsResize : function(){
        var self = this;
        for(var sectionId in self.sectionsHeight)
        {
            if(self.sectionsHeight.hasOwnProperty(sectionId))
            {
                self.sectionsHeight[sectionId].height(self.sectionsHeight[sectionId].origHeight * self.scale);
            }
        }
    },

    videoPanelsResize : function()
    {
        jQuery.each(jQuery('.video-panel'), function(){
            jQuery(this).height(jQuery(this).find('>.image-holder').height());
        });
    },

    windowResize : function(){
        var self = this;
        self.scale = jQuery(window).width() / self.defWindowWidth;
        jQuery(window).scrollTop(0);
    }

};


jQuery(function($){

    LPScrollr.init();

    // videopanel behavior
    VideoPanel.init('.video-panel');

	$(window).scroll(function(){
		var	$topPosition = $(window).scrollTop();
        $('.yoffset').html($topPosition);
		//Back Button Enable/Disable
		if($topPosition > 0){
			$('.quick-navigation .explore-more-btn').addClass('little');
			//$('.quick-navigation .back-btn').fadeIn('normal');
		}else if($topPosition == 0){
			//$('.quick-navigation .back-btn').fadeOut('normal');
			$('.quick-navigation .explore-more-btn').removeClass('little');
		}

        if(window.pageYOffset > 100){
            $('.explore-more-btn').addClass('little');
            $('.back-btn').fadeIn(100);
        }
        else{
            $('.explore-more-btn').removeClass('little');
            $('.back-btn').fadeOut(100);
        }
        if(window.pageYOffset == jQuery(document).height() - jQuery(window).height())
        {
            jQuery('.explore-more-btn').hide();
        }
        else
        {
            jQuery('.explore-more-btn').show();
        }
	});

    // =============== quick navigation buttons behavior ================
    jQuery('.explore-more-btn').on('click', function(){
        jQuery('html,body').stop(true, false).animate({scrollTop : '+=' + jQuery(window).height()},5000); //* LPScrollr.scale
        //console.log(5000 / LPScrollr.scale)
    });
    jQuery('.back-btn').on('click', function(){
        jQuery('html,body').stop(true, false).animate({scrollTop : '-=' + jQuery(window).height()},5000); //* LPScrollr.scale
    });

    $(window).load(function(){
        $('.explore-more-btn').fadeIn('slow');
        LPScrollr.videoPanelsResize();
    });

	$(window).resize(function(){
        LPScrollr.refresh();
	});
	//$(window).resize();
	//$('html,body').scrollTop(0);

    //=========== left description fade in ==========================

    setTimeout(function(){
        $('.left-description').fadeIn('slow');
    }, 2000);

    setTimeout(function(){
        $('.landing-page .main-section .main-image-holder').fadeIn('slow');
    }, 1000);

});
