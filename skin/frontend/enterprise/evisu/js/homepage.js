var HomePage = {
    scrollr : null,
    scale : 1,
    defWindowWidth : 1920,
    defWindowHeight : 1080,
    sections: null,
    sectionsHeight: [],

    init : function(){
        //Mobile.yes = true;
        var self = this;
        // init scrollr

        if(!Mobile.isIPad && !Mobile.yes)
        {
            //set def section height
            self.sections = jQuery('.section-holder');
            jQuery.each(self.sections, function(index, section){
                self.sectionsHeight[index] = jQuery(section).height();
            });

            self.sectionsResize();

            //init scroller
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
        }
    },

    refresh : function(){
        var self = this;
        if(!Mobile.isIPad && !Mobile.yes){
            self.sectionsResize();
            self.scrollr.refresh(undefined, self.scale);
        }
    },

    sectionsResize: function(){
        var self = this;
        self.scale = jQuery(window).width() / self.defWindowWidth;
        jQuery.each(jQuery('.section-holder'), function(index, section){
            jQuery(section).height(self.sectionsHeight[index] * self.scale);
        });
    }
};


jQuery(function($){

    // videopanel behavior
    VideoPanel.init('.video-panel');
    if(!Mobile.yes && !Mobile.isIPad){
        HomePage.init();
        jQuery(window).resize(function(){
            jQuery('html').scrollTop(0);
            HomePage.refresh();
            jQuery.each(jQuery('.video-panel'), function(){
                jQuery(this).height(jQuery(this).find('>.image-holder').height());
            });
        });

    }
    jQuery('.main-section p').hide();
    jQuery(window).on('load', function(){
        jQuery(window).resize();
        //animate main section here
        jQuery('.main-section .image-holder').delay(1000).animate({opacity:1},1000);
        jQuery('.main-section .main-text>.row1').delay(2000).fadeIn(1000);
        jQuery('.main-section .main-text>.row2').delay(3000).fadeIn(1000);
    });

    jQuery(window).scroll(function(){
        var $topPosition = $(window).scrollTop();
        jQuery('.yoffset').html($topPosition);
    });
});
