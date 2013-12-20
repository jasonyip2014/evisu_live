var EvisuBlog = {
    init : function(){


    },

    onReady : function()
    {
        self = this;

        //desable magento search
        jQuery('.nav-container .search-btn').hide();

        jQuery('.back-to-top-btn').on('click', function(){
            jQuery('html, body').animate({scrollTop: 0}, 1500);
        });

        self.filterBtn = jQuery('.filter-categories');
        self.filterBlock = jQuery('.block-blog-categories');

        self.calendarBtn = jQuery('.filter-calendar');
        self.calendarBlock = jQuery('.archive-container');
        self.calendarHider = jQuery('.calendar-hider');
        self.siteHider = jQuery('#site-hidder');

        self.filterTimer = null;

        self.filterBtn.on('click', function(){
            if(jQuery(this).hasClass('closed')){
                self.filterOpen();
            } else{
                self.filterClose();
            }
        });

        self.calendarBtn.on('click', function(){
            if(jQuery(this).hasClass('closed')){
                self.calendarOpen();
            } else{
                self.calendarClose();
            }
        });

        self.calendarBlock.on('mouseleave', function(){
            self.filterTimer = setTimeout(function(){
                self.calendarClose();
            }, 500);
        });

        self.filterBlock.on('mouseleave', function(){
            self.filterTimer = setTimeout(function(){
                self.filterClose();
            }, 500);
        });

        self.calendarBlock.on('mouseenter', function(){
            if(self.filterTimer) clearTimeout(self.filterTimer);
        });

        self.filterBlock.on('mouseenter', function(){
            if(self.filterTimer) clearTimeout(self.filterTimer);
        });

        jQuery('.calendar .week a').on('mouseenter', function(){
            jQuery(this).find('.tooltip').stop(true, false).fadeIn('fast');
        });
        jQuery('.calendar .week a').on('mouseleave', function(){
            jQuery(this).find('.tooltip').stop(true, false).fadeOut('fast');
        });
    },

    filterOpen : function(){
        if(self.filterTimer) clearTimeout(self.filterTimer);
        self.calendarClose();
        self.siteHider.stop(true,false).fadeIn('fast');
        self.filterBlock.css({zIndex: 200});
        self.filterBlock.stop(true, false).slideDown('slow', function(){
            self.filterBtn.removeClass('closed');
        });
    },

    filterClose : function(){
        self.siteHider.stop(true,false).fadeOut('slow');
        self.filterBlock.css({zIndex: 199});
        self.filterBlock.stop(true, false).slideUp('slow', function(){
            self.filterBtn.addClass('closed');
        });
    },

    calendarOpen : function(){
        if(self.filterTimer) clearTimeout(self.filterTimer);
        self.filterClose();
        self.siteHider.stop(true,false).fadeIn('fast');
        self.calendarBlock.css({zIndex: 200});
        self.calendarBlock.stop(true, false).slideDown('slow', function(){
            self.calendarBtn.removeClass('closed');
        });
    },

    calendarClose : function(){
        self.siteHider.stop(true, false).fadeOut('slow');
        self.calendarBlock.css({zIndex: 199});
        self.calendarBlock.stop(true, false).slideUp('slow', function(){
            self.calendarBtn.addClass('closed');
        });
    },

    getArchiveCalendar : function(url){
        self.calendarHider.fadeIn('fast', function(){
            jQuery.get(url).done(function( data ) {
                self.calendarBlock.html(jQuery(data).find('#calendar'));
                self.calendarHider.fadeOut();
            });
        });

    }
};



jQuery(function($)
{
    EvisuBlog.onReady();

});


jQuery(window).load(function(){

var $container = jQuery('#post-list');
// initialize
$container.masonry({
  columnWidth: 4,
  itemSelector: '.item'
});

});