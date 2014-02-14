var HomePage = {
    scrollr : null,
    scale : 1,
    defWindowWidth : 1920,
    defWindowHeight : 1080,
    sections: null,
    sectionsHeight: [],

    init : function(){
        var self = this;
        // init scrollr

        if(!Mobile.isIPad && !Mobile.yes)
        {
            //set def section height
            self.sections = jQuery('.section-holder');
            jQuery.each(self.sections, function(index, section){
                self.sectionsHeight[index] = jQuery(section).height();
                console.log(self.sectionsHeight[index]);
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
        console.log(self.scale);
        jQuery.each(jQuery('.section-holder'), function(index, section){
            jQuery(section).height(self.sectionsHeight[index] * self.scale);
            console.log(jQuery(section).height());
        });
    }
};


jQuery(function($){

    // videopanel behavior
    VideoPanel.init('.video-panel');
    if(!Mobile.yes && !Mobile.isIPad){
        HomePage.init();
        $(window).resize(function(){
            $('html').scrollTop(0);
            HomePage.refresh();
        });
        $(window).resize();
    }
    jQuery(window).on('load', function(){
        //animate main section here
        $('.main-section .image-holder').delay(1000).fadeIn(1000);
        $('.main-section p').delay(2000).fadeIn(1000);  
    });

    $(window).scroll(function(){
        var $topPosition = $(window).scrollTop();
        $('.yoffset').html($topPosition);
    });
});
