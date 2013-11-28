var HeritageScrollr = {
    scrollr : null,
    scale : 1,
    defWindowWidth : 1920,
    defWindowHeight : 1080,

    init : function(){
        var self = this;
        self.windowResize();
        self.sectionsResize();
        // init scrollr
        console.log(this.scale);
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
        //jQuery('body,html').height('auto');

        //block Items behavior
        var $document = jQuery(document);
        $document.on('mouseenter', '.item', function(){
            console.log('enter');
            jQuery(this).css('z-index', 10);
        });
        $document.on('mouseleave', '.item', function(){
            console.log('leave');
            jQuery(this).css('z-index', 1);
        });
    },

    refresh : function(){
        var self = this;
        self.windowResize();
        self.sectionsResize();
        self.scrollr.refresh(undefined, self.scale);
        //jQuery('body,html').height('auto');
    },

    sectionsResize : function(){
        $windowHeight = jQuery(window).height();
        jQuery.each(jQuery('.section'), function(index, section)
        {
           jQuery(section).height($windowHeight);
        });
    },


    windowResize : function(){
        var self = this;
        self.scale = jQuery(window).height() / self.defWindowHeight;
        jQuery(window).scrollTop(0);
    }

};


jQuery(function($){

    HeritageScrollr.init();


    $(window).load(function(){
    });

	$(window).resize(function(){
        HeritageScrollr.refresh();
	});

 });

//=========Frst section animation=======================

jQuery(window).load(function(){
    //first-section animation
    jQuery('.begin-text').delay(500).fadeIn(500);
    jQuery('.begin-img').delay(1000).fadeIn(500);
    jQuery('.section-1 .block-1').delay(1000).animate({left: "3%"}, 1500);
    jQuery('.section-1 .block-3').delay(1000).animate({right: "3%"}, 1500);
    jQuery('.section-1 .block-2').delay(2000).animate({opacity: 1});
});

// jQuery( window ).scroll(function() {

//     var a = jQuery(document).height();
//     console.log(a);
//     var y = window.pageYOffset;
//     console.log(y);

//     if(y>1500){
//         jQuery('.end-img').fadeIn(100);
//     }
//     else{
//        jQuery('.end-img').fadeOut(100); 
//     }

// });