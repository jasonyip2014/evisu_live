var RulesScrollr = {
    scrollr : null,
    scale : 1,
    defWindowWidth : 1920,
    defWindowHeight : 1080,

    init : function(){
        var self = this;
        self.timeLineHtml = jQuery('#time-line').html();
        self.windowResize();
        self.sectionsResize();

        // init scrollr
        if(!Mobile.isIPad && !Mobile.yes) {
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
        //jQuery('body,html').height('auto');

        //block Items behavior
        var $document = jQuery(document);
        $document.on('mouseenter', '.item', function(){
            jQuery(this).css('z-index', 10);
        });
        $document.on('mouseleave', '.item', function(){
            jQuery(this).css('z-index', 1);
        });

        jQuery('.navigation .navigation-item').on('click', function(){
            self.goToSection(jQuery(this).data('section'));
            return false;
        });

        // =============== quick navigation buttons behavior ================
        jQuery('.explore-more-btn').on('click', function(){
            jQuery.each(jQuery('.section'), function(index, section)
            {
                if((jQuery(section).offset().top) >= (jQuery(window).scrollTop()  + 1))
                {
                    self.goToSection(index);
                    return false;
                }
            });

        });
        jQuery('.back-btn').on('click', function(){
            jQuery.each(jQuery('.section'), function(index, section)
            {
                if((jQuery(section).offset().top) >= (jQuery(window).scrollTop() - 5))
                {
                    if(index > 0)
                    {
                        self.goToSection(index-1);
                    }
                    return false;
                }
            });
        });
    },

    refresh : function(){
        var self = this;
        self.windowResize();
        self.sectionsResize();
        self.imagesAspectRatio();
        if(!Mobile.isIPad && !Mobile.yes){
            self.scrollr.refresh(undefined, self.scale);
        }
        //jQuery('body,html').height('auto');
    },

    imagesAspectRatio : function(){
        var self = this;
        var images = jQuery('.r_image_panel.big img');
        var windowWidth = jQuery(window).width();
        var windowHeight = jQuery(window).height();
        if(self.defWindowHeight / windowHeight < self.defWindowWidth / windowWidth)
        {
            //horizontaly centering
            images.css({width:'auto', height:'100%'});
                images.css({marginLeft:-((images.width() - windowWidth) / 2), marginTop:0});
        }
        else
        {
            //verticaly centering
            images.css({width:'100%', height:'auto'});
            images.css({marginTop:-((images.height() - windowHeight) / 2), marginLeft:0});
        }
    },

    sectionsResize : function(){

        var self = this;
        jQuery('#time-line').html(self.timeLineHtml);
        $windowHeight = jQuery(window).height();

        jQuery.each(jQuery('.section'), function(index, section)
        {
            jQuery(section).height($windowHeight);
        });

        //time line
        var timeLine = jQuery('.line');
        var endImgHolder = jQuery('.end-img-holder');
        var endText = jQuery('.end-text');
        var sectionCount = jQuery('.section').length - 1;
        var animationStart = jQuery('.begin-img-holder').offset().top+jQuery('.begin-img-holder').height();
        var animationEnd = (sectionCount) * self.defWindowHeight + 65 + 180 - animationStart;

        //clear time-line


        timeLine.attr('data-' + parseInt(animationStart),'height:0px');
        timeLine.attr('data-' + parseInt(animationEnd), 'height:' + (sectionCount * $windowHeight - animationStart + 65) + 'px');
        endImgHolder.attr('data-' + parseInt(animationEnd), 'opacity:1');
        endImgHolder.attr('data-' + (parseInt(animationEnd)-1), 'opacity:0');
        endText.attr('data-' + (parseInt(animationEnd)), 'opacity:1');
        endText.attr('data-' + (parseInt(animationEnd)-1), 'opacity:0');
        //jQuery('.begin-img').animate({opacity:1}, 1000);

        self.setImagePosition();
    },

    goToSection: function(section){
        jQuery('html, body').animate({scrollTop: jQuery('.section-'+(section)).offset().top}, 2000);
    },

    windowResize : function(){
        var self = this;
        self.scale = jQuery(window).height() / self.defWindowHeight;
        jQuery(window).scrollTop(0);
    },

    setImagePosition : function(){
        var sectionHeight = jQuery('.section').height();
        var imageHeight = jQuery('.section .r_image_panel.small').height();
        var imageTop = sectionHeight - imageHeight / 2;
        jQuery('.section .r_image_panel.small').css({top:imageTop})
    }

};

jQuery(function($){
    if(!Mobile.yes){
        RulesScrollr.init();
        jQuery('.begin-img').css({opacity: 0});

        $(window).scroll(function(){
            var	$topPosition = $(window).scrollTop();
            //Back Button Enable/Disable
            if($topPosition > 0){
                $('.quick-navigation .explore-more-btn').addClass('little');
            }else if($topPosition == 0){
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
            if(window.pageYOffset == jQuery(document).height() - jQuery(window).height()){
                jQuery('.explore-more-btn').hide();
            }else{
                jQuery('.explore-more-btn').show();
            }
        });

        $(window).load(function(){
            $('.explore-more-btn').fadeIn('slow');
            RulesScrollr.setImagePosition();
            RulesScrollr.imagesAspectRatio();
            //main-section animation
            $('.main-text-container').delay(1000).fadeIn(1000);
            $('.main-section .small').css('top', '+=140px').delay(500).animate({top: '-=140px'}, 3000);
            $('.begin-img').delay(2000).animate({opacity:1}, 1000);

        });

        $(window).resize(function(){
            RulesScrollr.refresh();
        });
    }
 });


// mobile only js write hear
jQuery(function($){
    if(Mobile.yes){
        //section navigation
        jQuery('.navigation .navigation-item').on('click', function(){
            jQuery('html, body').animate({scrollTop: jQuery('.section-'+(jQuery(this).data('section'))).offset().top}, 2000);
            return false;
        });

        jQuery(window).load(function(){

            // Mobile Image Centering
            var $bigImages = jQuery('.block.big').find('img');
            jQuery.each($bigImages, function(index, img){
                $img = jQuery(img);
                var left_range = ($img.width() - 640) / 2;
                $img.css({marginLeft: '-' + left_range + 'px'});
            });

            //time-line height set
            jQuery('.line').height((jQuery('.section').length - 2) * 1079 + 266 + 73);
        });
    }
});