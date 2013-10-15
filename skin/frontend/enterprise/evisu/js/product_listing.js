var DropDown = {
    self : null,
    sort_timeout : null,
    sort_display : false,

    filter_timeout : null,
    filter_display : false,

    change_filter_timeout : null,

    init : function()
    {
        var self = this;
        //====================== sort by init ============================
        jQuery('.sort-by-title').on('click', function(){
            clearTimeout(self.sort_timeout);
            if(self.sort_display){
                self.sort_hide();
            } else{
                self.sort_show();
            }
        });

        jQuery('.sort-by-title').on('mouseover', function() {
            clearTimeout(self.sort_timeout);
            self.sort_timeout = setTimeout(function(){
                self.sort_show();
            }, 500);
        });

        jQuery('.sort-by-title').on('mouseleave', function() {
            clearTimeout(self.sort_timeout);
            self.sort_timeout = setTimeout(function(){
                self.sort_hide();
            }, 100);
        });

        jQuery('.sort-by').on('mouseover', function() {
            clearTimeout(self.sort_timeout);
        });

        jQuery('.sort-by').on('mouseleave', function() {
            clearTimeout(self.sort_timeout);
            self.sort_timeout = setTimeout(function(){
                self.sort_hide();
            }, 100);
        });

        // ===================== filter by init ===============================

        jQuery('.block-subtitle').on('click', function(){
            clearTimeout(self.filter_timeout);
            if(self.filter_display){
                self.filter_hide();
            }
            else{
                self.filter_show(1);
            }
        });

        jQuery('.block-subtitle').on('mouseover', function() {
            clearTimeout(self.filter_timeout);
            self.filter_timeout = setTimeout(function(){
                self.filter_show(1);
            }, 500);
        });

        jQuery('.block-subtitle').on('mouseleave', function() {
            clearTimeout(self.filter_timeout);
            self.filter_timeout = setTimeout(function(){
                self.filter_hide();
            }, 100);
        });

        jQuery('.block-layered-nav #narrow-by-list').on('mouseover', function() {
            clearTimeout(self.filter_timeout);
        });

        jQuery('.block-layered-nav #narrow-by-list').on('mouseleave', function() {
            clearTimeout(self.filter_timeout);
            self.filter_timeout = setTimeout(function(){
                self.filter_hide();
            }, 100);
        });
    },

    // ===================== filter by functions ===============================
    sort_show : function ()
    {
        var self = this;
        jQuery('.sort-by-title').addClass('active');
        jQuery('.main').animate({opacity:0.4}, 'fast');
        jQuery('.breadcrumbs-container').animate({opacity:0.4}, 'fast');
        jQuery('.sort-by-holder').stop(true, true).slideDown('normal', function(){
            self.sort_display = true;
        });
    },

    sort_hide : function()
    {
        var self = this;
        jQuery('.sort-by-title').removeClass('active');
        jQuery('.sort-by-holder').stop(true, true).slideUp('normal', function(){
            self.sort_display = false;
        });
        jQuery('.main').animate({opacity:1}, 'fast');
        jQuery('.breadcrumbs-container').animate({opacity:1},'fast');
    },

    // ===================== filter by functions ===============================
    filter_show : function (animate)
    {
        var self = this;
        console.log(animate);
        if(!animate)
        {
            jQuery('.block-subtitle').addClass('active');
            jQuery('.main').css('opacity',0.4);
            jQuery('.breadcrumbs-container').css('opacity',0.4);
            jQuery('.block-layered-nav .block-content').css('display','block');
            self.filter_display = true;
        }
        else
        {
            jQuery('.block-subtitle').addClass('active');
            jQuery('.main').animate({opacity:0.4}, 'fast');
            jQuery('.breadcrumbs-container').animate({opacity:0.4}, 'fast');
            jQuery('.block-layered-nav .block-content').stop(true, true).slideDown('normal', function(){self.filter_display = true});
        }
    },

    filter_hide : function()
    {
        var self = this;
        jQuery('.block-subtitle').removeClass('active');
        jQuery('.block-layered-nav .block-content').stop(true, true).slideUp('normal', function(){self.filter_display = false;});
        jQuery('.main').animate({opacity:1}, 'fast');
        jQuery('.breadcrumbs-container').animate({opacity:1},'fast');
    },

    changeFilter : function(url)
    {
        setLocation(url);
        //toDo disable checkbox ?
    }
};

jQuery(function($){
    // filters and sorters init //
    DropDown.init();

    //loader
    jQuery('.col-main').css({'opacity':0});
    jQuery('.site-loader').show();
    // =============== quick navigation buttons behavior ================
    jQuery('.explore-more-btn').on('click', function(){
        jQuery('html,body').stop(true, false).animate({scrollTop : '+=' + jQuery(window).height()},'slow');
    });
    jQuery('.back-btn').on('click', function(){
        jQuery('html,body').stop(true, false).animate({scrollTop : '-=' + jQuery(window).height()},'slow');
    });

    $(window).load(function(){
        $('.explore-more-btn').fadeIn('slow');
    });

    // ================= product grid image ========================
    $('.products-grid .images-holder').on('mouseenter', function(){
        var container = $(this);
        container.find('.img-2').css('opacity', 0);
        container.find('.img-1').stop(true,false);
        container.find('.img-1').animate({opacity: 0},'fast');
        container.find('.img-2').animate({opacity: 0.4},'fast');
        container.parent().next().css('zIndex', 4);
    });

    $('.products-grid .hover-container').on('mouseleave', function(){
        var container = $(this).parent().parent();
        container.prev().find('.img-2').stop(true,false);
        container.prev().find('.img-2').animate({opacity: 0},'fast');
        container.prev().find('.img-1').animate({opacity: 1},'fast');
        container.css('zIndex', 2);
    });
    // =================================================================

    $(window).scroll(function(){
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

});

jQuery(window).load(function(){
    //loader
    jQuery('.col-main').stop(true, true).animate({opacity: 1}, 'fast');
    jQuery('.site-loader').fadeOut('fast');
});
