var FitGuide = {
    positions : [],
    currentId : null,
    config : [],
    areaHeight : 0,
    menuTimer : null,
    slideNavigation : null,
    thumbnailNavigation : null,
    textNavigationItem : null,

    init : function(config, positions, currentId)
    {
        //init def
        var self = this;
        this.config = config;
        this.positions = positions;
        this.currentId = currentId;

        //set prop
        self.slideNavigation = jQuery('.slide-navigation');
        self.thumbnailNavigation = jQuery('.thumbnail-navigation');
        self.textNavigationItem = jQuery('.text-navigation .text');

        //behavior
        jQuery('#next-btn').on('click', function(){self.next()});
        jQuery('#prev-btn').on('click', function(){self.prev()});
        jQuery('.thumbnail-navigation .thumbnail').on('click', function(){self.thumbnailClick(jQuery(this))});

        this.menuBehavior();

        //init css
        jQuery('.left-image').css({'visibility': 'hidden'});
        jQuery('.right-image').css({'visibility': 'hidden'});

        if(!Mobile.yes){
            jQuery('.slide-navigation').css({position : 'relative', overflow:'hidden', height:0});
        }
        jQuery('.item-text-container').hide();

        jQuery('.fit-guide-container').css({opacity:0});


    },

    initOnLoad : function(){
        //init prop
        self.areaHeight = jQuery('.fit-guide-container').height();

        //init css
        jQuery('.left-image').css({'top': -self.areaHeight + 'px', 'visibility': 'visible'});
        jQuery('.right-image').css({'top': -self.areaHeight + 'px', 'visibility': 'visible'});
        jQuery('.fit-guide-container').animate({opacity:1});
    },

    menuBehavior: function(){
        self = this;
        if(!Mobile.yes){
            self.textNavigationItem.on('mouseenter',function(){
                jQuery('#thumbnail-' + jQuery(this).data('id')).addClass('hover');
                if(jQuery(window).width() >= 1024)
                {
                   self.thumbnailMenuShow();
                }
            });

            self.textNavigationItem.on('click',function(){
                jQuery('#thumbnail-' + jQuery(this).data('id')).addClass('hover');
                if(jQuery(window).width() <= 1024)
                {
                    self.thumbnailMenuShow();
                }
            });

            self.textNavigationItem.on('mouseleave',function(){
                jQuery('#thumbnail-' + jQuery(this).data('id')).removeClass('hover');
               self.thumbnailMenuHide();
            });

            self.slideNavigation.on('mouseenter',function(){self.thumbnailMenuShow()});
            self.slideNavigation.on('mouseleave',function(){self.thumbnailMenuHide()});
        } else {
            self.textNavigationItem.on('click',function(){
                self.thumbnailClick(jQuery(this));
            });
        }
    },

    thumbnailMenuShow : function(){
        var self = this;
        clearTimeout(self.menuTimer);
        var height = self.thumbnailNavigation.height();
        jQuery('#greyed-all').fadeIn();
        self.slideNavigation.stop(true,false).animate({height: height + 'px',top: -height + 'px', marginBottom: -height + 'px'});
    },

    thumbnailMenuHide : function(){
        var self = this;
        self.menuTimer = setTimeout(function(){
            jQuery('#greyed-all').fadeOut();
            self.slideNavigation.stop(true,false).animate({height:'0',top:'0', marginBottom:'0'});
        }, 500);
    },

    thumbnailClick : function(element){
        var id = element.data('id');
        if(this.currentId != id)
        {
            jQuery('.thumbnail.active').removeClass('active');
            jQuery('.text.active').removeClass('active');
            element.addClass('active');
            this.animateOut();
            this.choiseItem(id);
        }
    },

    choiseItem : function(id)
    {
        var self = this;
        self.currentId = id;
        self.animateIn();
        jQuery('#next-btn').find('img').attr('src', jQuery('#thumbnail-' + self.getNextId()).find('img').attr('src'));
        jQuery('#prev-btn').find('img').attr('src', jQuery('#thumbnail-' + self.getPrevId()).find('img').attr('src'));
    },

    animateIn : function()
    {
        var self = this;
        var currentItem = jQuery('#item-' + self.currentId);
        currentItem.find('.left-image').stop(true,false).delay(500).animate({'top':  0 + 'px'}, 1000);
        currentItem.find('.right-image').stop(true,false).delay(1000).animate({'top': 0 + 'px'}, 1000);
        currentItem.find('.item-text-container').stop(true,false).delay(1500).fadeIn('slow')
    },

    animateOut : function()
    {
        var self = this;
        var currentItem = jQuery('#item-' + self.currentId);
        currentItem.find('.left-image').stop(true,false).animate({'top': self.areaHeight + 'px'}, 1000, function(){
            jQuery(this).css({'top': '-' + self.areaHeight + 'px'});
        });
        currentItem.find('.right-image').stop(true,false).delay(500).animate({'top': self.areaHeight + 'px'}, 1000, function(){
            jQuery(this).css({'top': '-' + self.areaHeight + 'px'});
        });
        currentItem.find('.item-text-container').stop(true,false).delay(500).fadeOut('slow')
    },

    next : function()
    {
        var self = this;
        self.animateOut();
        self.choiseItem(self.getNextId())
    },

    prev : function() {
        var self = this;
        self.animateOut();
        self.choiseItem(self.getPrevId())
    },

    getNextId : function() {
        var self = this;
        var nextId;
        var currentPosition = self.positions[self.currentId];
        if(currentPosition == self.positions.length - 1)
        {
            nextId = 0;
        }
        else
        {
            nextId = currentPosition + 1;
        }
        return self.config[nextId]['entity_id'];

    },

    getPrevId : function() {
        var self = this;
        var prevId;
        var currentPosition = self.positions[self.currentId];
        if(currentPosition == 0)
        {
            prevId = self.positions.length - 1;
        }
        else
        {
            prevId = currentPosition - 1;
        }
        return self.config[prevId]['entity_id'];
    },

    containerResize : function(){
        var self = this;
        self.areaHeight = jQuery('.fit-guide-container .left-image').height();
        jQuery('.fit-guide-container').height(self.areaHeight);
        jQuery.each(jQuery('.item'), function(index, item){
            var jItem = jQuery(item);
            if(jItem.attr('id') != 'item-' + self.currentId)
            {
                jItem.find('.left-image').css({'top': -self.areaHeight + 'px'});
                jItem.find('.right-image').css({'top': -self.areaHeight + 'px'});
            }
        });

    }

};

jQuery(window).load(function(){
    FitGuide.containerResize();
    FitGuide.initOnLoad();
    FitGuide.choiseItem(FitGuide.currentId);
});


jQuery(window).resize(function(){
    FitGuide.containerResize();
});
