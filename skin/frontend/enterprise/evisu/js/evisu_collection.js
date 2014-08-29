var Collection;
Collection = {
    positions: [],
    currentId: null,
    galleryId: null,
    config: [],
    orgWindowWidth: 1920,
    origGalleryHeight: 0,
    shareData: [],
    gallery: {
        width: 0,
        visibleWidth: 0,
        visibleCount: 0,
        visibleItemWidth: 0
    },

    init: function (config, positions, currentId) {
        //init def
        var self = this;
        this.config = config;
        this.positions = positions;
        this.currentId = currentId;
        this.galleryId = currentId;
        this.origGalleryHeight = 317;

        //==========================behavior===========================
        //looks change
        self.mainSection = jQuery('#main-section');
        self.mainSection.find('.next-btn').on('click', function () {
            self.next()
        });
        self.mainSection.find('.prev-btn').on('click', function () {
            self.prev()
        });
        self.mainSection.find('.right-hider').on('click', function () {
            self.next()
        });
        self.mainSection.find('.left-hider').on('click', function () {
            self.prev()
        });

        //add fake images
        if (!Mobile.yes) {
            var firstChild = self.mainSection.find('.main-carousel > li:first-child').clone().removeProp('id');
            var lastChild = self.mainSection.find('.main-carousel > li:last-child').clone().removeProp('id');
            self.mainSection.find('.main-carousel').append(firstChild);
            self.mainSection.find('.main-carousel').append(firstChild.clone());
            self.mainSection.find('.main-carousel').prepend(lastChild);
            self.mainSection.find('.main-carousel').prepend(lastChild.clone());
        }

        //gallegy
        jQuery('#gallery-btn').on('click', function () {
            self.toggleGallery(jQuery(this))
        });
        self.thumbnaiSection = jQuery('#thumbnail-section');
        self.thumbnaiSection.find('.next-btn').on('click', function () {
            self.nextGallery()
        });
        self.thumbnaiSection.find('.prev-btn').on('click', function () {
            self.prevGallery()
        });
        self.thumbnaiSection.find('li').on('mouseenter', function () {
            jQuery(this).stop(true, false).animate({opacity: 1}, 'fast');
        });
        self.thumbnaiSection.find('li').on('mouseleave', function () {
            jQuery(this).stop(true, false).animate({opacity: 0.4}, 'fast');
        });
        self.thumbnaiSection.find('li').on('click', function () {
            self.showLook(jQuery(this).data('id'), false)
        });


        //esc
        jQuery(document).keydown(function (e) {
            if (e.keyCode == 27) {
                self.hideAll()
            }
        });


        //this.origCarouselHeight = self.mainSection.find('ul').height();

    },

    initOnLoad: function () {
        //this.origGalleryHeight = self.thumbnaiSection.find('img').height();

        //this.getGalleryProp();

        if (Mobile.yes) {
            var $bigImages = jQuery('#thumbnail-section').find('img');
            jQuery.each($bigImages, function (index, img) {
                $img = jQuery(img);
                if ($img.hasClass('ladscape')) {
                    var left_range = ($img.width() - 212) / 2;
                    $img.css({marginLeft: '-' + left_range + 'px'});
                } else {
                    var top_range = ($img.height() - 318) / 2;
                    $img.css({marginTop: '-' + top_range + 'px'});
                }
            });
        }

        this.resize();
        this.showGalleryNavigation();
        //init prop
        jQuery('#gallery-btn').trigger('click');
    },

    resize: function () {
        var self = this;
        if(!Mobile.yes){
            if (jQuery('#gallery-btn').hasClass('closed')) {
                self.thumbnaiSection.show();
            }
        }
        self.getGalleryProp();
        self.showLook(self.currentId);
        if(!Mobile.yes){
            //resize gallery toDo Optimization (from choice.)
            var element;
            var container = jQuery('#thumbnail-section');
            self.galleryId = self.galleryId;
            var marginMax = self.gallery.width - container.find('ul').width();
            var marginLeft = 0;
            for (var i = 0; i < self.positions[self.galleryId]; i++) {
                element = container.find('#gall-trumb-' + self.config[i].id);
                marginLeft += element.width() + parseFloat(element.css('marginRight'));
            }
            if (marginLeft <= 0) {
                marginLeft = 0;
                self.galleryId = self.config[0].id;
            }
            else if (marginLeft > marginMax) {
                marginLeft = marginMax;

                self.galleryId = self.config[self.positions['length'] - 1].id;
            }
            container.find('ul>li:first').css({marginLeft: -marginLeft});

            if (jQuery('#gallery-btn').hasClass('closed')) {
                self.thumbnaiSection.hide();
            }
        }
    },

    prev: function () {
        var self = this;
        self.showLook(self.getPrevId(), true);
    },

    next: function () {
        var self = this;
        self.showLook(self.getNextId(), true);
    },

    showLook: function (id, animate) {

        var self = this;


        self.choiceGalleryItem(id);
        self.choiceMainCarouselItem(id);
        self.currentId = id;


        var assocProduct = self.config[self.positions[id]].assoc_product;
        if (Mobile.yes) {
            var mobileProductBtn = jQuery('.product-button');
            mobileProductBtn.fadeOut('500');
            if (assocProduct) {
                mobileProductBtn.find('a').attr({href: assocProduct.url});
                mobileProductBtn.fadeIn('500');
            }
        }


    },

    showGalleryNavigation: function () {
        var galleryWidth = 0;
        var container = jQuery('#thumbnail-section');
        jQuery.each(container.find('ul>li'), function (index, item) {
            galleryWidth += jQuery(item).width() + parseInt(jQuery(item).css('marginRight'));
        });
        if (container.find('ul').width() < galleryWidth) {
            container.find('.prev-btn').fadeIn();
            container.find('.next-btn').fadeIn();
        }
        else {
            container.find('.prev-btn').fadeOut();
            container.find('.next-btn').fadeOut();
        }
    },

    toggleGallery: function (btn) {
        if (btn.hasClass('opened')) {
            jQuery('#thumbnail-section').slideUp('normal');
            btn.removeClass('opened').addClass('closed').html(btn.data('closedtext'));
        }
        else {
            jQuery('#thumbnail-section').slideDown('normal');
            btn.removeClass('closed').addClass('opened').html(btn.data('openedtext'));
        }
    },

    hideAll: function () {
        this.hideArchive();
    },

    nextGallery: function () {
        var self = this;
        var nextId = self.getNextGalleryId();
        var container = jQuery('#thumbnail-section');
        if (self.positions[nextId] != self.gallery.visibleCount - 1) {
            var element = container.find('#gall-trumb-' + nextId);
            var marginLeft = element.offset().left + element.width() + parseFloat(element.css('marginLeft')) - container.find('ul').width() - container.find('ul').offset().left;
            container.find('ul>li:first').stop(true, true).animate({marginLeft: '-=' + marginLeft}, 'slow');
        }
        else {
            container.find('ul>li:first').stop(true, true).animate({marginLeft: 0}, 'slow');
        }
        self.galleryId = nextId;
    },

    prevGallery: function () {
        var self = this;
        var prevId = self.getPrevGalleryId();
        var container = jQuery('#thumbnail-section');
        if (self.positions[prevId] != self.positions['length'] - 1) {
            var element = container.find('#gall-trumb-' + prevId);
            var marginLeft = jQuery('#thumbnail-section').find('ul').offset().left - element.offset().left; //- (self.gallery.visibleWidth - self.gallery.visibleItemWidth);
            container.find('ul>li:first').stop(true, true).animate({marginLeft: '+=' + marginLeft}, 'slow');
        }
        else {
            container.find('ul>li:first').stop(true, true).animate({marginLeft: -(self.gallery.width - self.gallery.visibleWidth)}, 'slow');
        }
        self.galleryId = prevId;
    },

    getGalleryProp: function () {
        var self = this;
        var width = 0;
        var container = jQuery('#thumbnail-section');
        if(!Mobile.yes){
            container.find('img').height(self.origGalleryHeight * (jQuery(window).width() / self.orgWindowWidth));
        }
        /*
        jQuery.each(container.find('.image-holder'), function (index, element) {
            jQuery(element).width(jQuery(element).find('img').width());
        });
        */
        self.gallery.visibleWidth = container.find('ul').width();
        self.gallery.visibleCount = 0;
        self.gallery.visibleItemWidth = 0;
        self.gallery.offsetLeft = container.find('ul').offset().left;


        for (var i = 0; i < self.positions['length']; i++) {
            element = container.find('#gall-trumb-' + self.config[i].id);

            self.config[i].galleryItemWidth = element.width() + parseFloat(element.css('marginRight'));
            width += self.config[i].galleryItemWidth;
            if (width <= self.gallery.visibleWidth) {
                self.gallery.visibleItemWidth = width;
                self.gallery.visibleCount++;
            }
        }
        self.gallery.width = width;

    },

    choiceGalleryItem: function (itemId) {

        var self = this;

        var container = jQuery('#thumbnail-section');
        container.find('.active').stop(true, false).animate({opacity: 0.4}, 'fast', function () {
            jQuery(this).removeClass('active')
        });
        element = container.find('#gall-trumb-' + itemId).stop(true, false).animate({opacity: 1}, 'fast', function () {
            jQuery(this).addClass('active')
        });
        if(Mobile.yes){
            jQuery('html,body').animate({scrollTop: jQuery('.main-carousel-holder').offset().top}, 'slow');
        } else {
            jQuery('html,body').animate({scrollTop: 0}, 'slow');
        }

        if (!Mobile.yes) {
            var element;
            self.galleryId = itemId;
            var marginMax = self.gallery.width - container.find('ul').width();
            if(marginMax < 0){
                container.find('ul').addClass('middle');
                container.find('ul>li:first').animate({marginLeft: 0}, 'slow');
            } else {
                container.find('ul').removeClass('middle');
                var marginLeft = 0;
                for (var i = 0; i < self.positions[itemId]; i++) {
                    element = container.find('#gall-trumb-' + self.config[i].id);
                    marginLeft += element.width() + parseFloat(element.css('marginRight'));
                }
                if (marginLeft <= 0) {
                    marginLeft = 0;
                    self.galleryId = self.config[0].id;
                }
                else if (marginLeft > marginMax) {
                    marginLeft = marginMax;

                    self.galleryId = self.config[self.positions['length'] - 1].id;
                }
                container.find('ul>li:first').animate({marginLeft: -marginLeft}, 'slow');
            }
        }
    },

    choiceMainCarouselItem: function (itemId) {
        var self = this;
        jQuery('.description-holder>.title').html(self.config[self.positions[itemId]].shareData['title']);
        jQuery('.description-holder>.description').html(self.config[self.positions[itemId]].shareData['summary'].replace(/([^>])\n/g, '$1<br/>'));
        jQuery('.collection-link').attr({href:self.config[self.positions[itemId]].target_url});

        if(self.config[self.positions[itemId]].id==357 ||self.config[self.positions[itemId]].id==358){//added by py on 20140814
            jQuery('.collection-link').hide();
        }else{
            jQuery('.collection-link').show();
        }
        //---------------end----------------------------------------
        var windowWidth;
        if (Mobile.isIPad || Mobile.yes) {
            windowWidth = (window.innerWidth > 0) ? window.innerWidth : screen.width;
        } else {
            windowWidth = jQuery(window).width();
        }

        var $currentElement = self.mainSection.find('#main-carousel-' + itemId);
        self.mainSection.find('.active').removeClass('active');
        $currentElement.addClass('active');

        var elementFullWidth = $currentElement.width() + parseInt($currentElement.css('margin-left')) + parseInt($currentElement.css('margin-right'));
        var neededOffset = (windowWidth - elementFullWidth) / 2;
        self.mainSection.find('.main-carousel').stop(true, true).animate({left: '-=' + ($currentElement.offset().left - parseInt($currentElement.css('margin-left')) - neededOffset)});
        if (!Mobile.yes) {
            var $leftHider = self.mainSection.find('.left-hider');
            var $rightHider = self.mainSection.find('.right-hider');
            $leftHider.stop(true, false).animate({width: neededOffset}, 'slow');
            $rightHider.stop(true, false).animate({width: neededOffset}, 'slow');
        }
    },

    getNextId: function () {
        var self = this;
        var nextId;
        var currentPosition = self.positions[self.currentId];
        if (currentPosition == self.positions.length - 1) {
            nextId = 0;
        }
        else {
            nextId = currentPosition + 1;
        }
        return self.config[nextId]['id'];

    },

    getPrevId: function () {
        var self = this;
        var prevId;
        var currentPosition = self.positions[self.currentId];
        if (currentPosition == 0) {
            prevId = self.positions.length - 1;
        }
        else {
            prevId = currentPosition - 1;
        }
        return self.config[prevId]['id'];
    },

    getNextGalleryId: function () {
        var self = this;
        var nextPosition;
        var currentPosition = self.positions[self.galleryId];

        if (currentPosition >= self.positions.length - 1) {
            nextPosition = self.gallery.visibleCount - 1;
        }
        else if (currentPosition < self.gallery.visibleCount - 1) {
            nextPosition = self.gallery.visibleCount;
        }
        else {
            nextPosition = currentPosition + 1;
        }
        return self.config[nextPosition]['id'];
    },

    getPrevGalleryId: function () {
        var self = this;
        var prevPosition;
        var currentPosition = self.positions[self.galleryId];

        if (currentPosition <= 0) {
            prevPosition = self.positions['length'] - 1;
        }
        //else if(currentPosition == self.gallery.visibleCount)
        //{
        //    prevPosition = 0;
        //}
        else if (currentPosition > self.positions['length'] - self.gallery.visibleCount) {
            prevPosition = currentPosition - self.gallery.visibleCount + 1;
        }
        else {
            prevPosition = currentPosition - 1;
        }
        return self.config[prevPosition]['id'];
    },

    setShareData: function (id) {
        var shareData = this.config[this.positions[id]].shareData;
        this.shareData['url'] = encodeURIComponent(shareData['url']);
        this.shareData['title'] = encodeURIComponent(shareData['name']);
        this.shareData['summary'] = encodeURIComponent(shareData['description']);
        this.shareData['image'] = encodeURIComponent(shareData['image']);
    },
    //shared functions
    shareTwitter: function () {
        window.open('https://twitter.com/intent/tweet?text=' + this.shareData['title'] + ': &url=' + this.shareData['url'], 'sharer', 'toolbar=0,status=0,width=548,height=325');
    },

    shareFacebook: function () {
        window.open('http://www.facebook.com/sharer.php?s=100&amp;p[title]=' + this.shareData['title'] + '&amp;p[summary]=' + this.shareData['summary'] + '&amp;p[url]=' + this.shareData['url'] + '&amp;&amp;p[images][0]=' + this.shareData['image'], 'sharer', 'toolbar=0,status=0,width=548,height=325');
    },

    sharePinterest: function () {
        window.open('http://pinterest.com/pin/create/button/?url=' + this.shareData['url'] + '&media=' + this.shareData.image + '&description=' + this.shareData['title'] + ' - ' + this.shareData['summary'], 'sharer', 'toolbar=0,status=0,width=548,height=325');
    },

    shareGooglePlus: function () {
        window.open('https://plus.google.com/share?url=' + this.shareData['url'], 'sharer', 'toolbar=0,status=0,width=548,height=325');
    },

    shareWeibo: function () {
        window.open('http://service.weibo.com/share/share.php?url=' + this.shareData['url'] + '&title=' + this.shareData['title'] + '&pic=' + this.shareData['image'], 'sharer', 'toolbar=0,status=0,width=548,height=325')
    },

    mailToFriend: function () {
        return sa_tellafriend(decodeURIComponent(this.shareData['url']), 'email');
    }
    //end shared functions
};

jQuery(function(){
    //jQuery('.col-main').css({opacity:0});
    jQuery('.site-loader').show();

    jQuery(window).load(function(){
        //loader
        Collection.initOnLoad();
        jQuery('.col-main').stop(true, true).animate({opacity: 1}, 'fast');
        jQuery('.site-loader').hide();
        jQuery(window).resize();
    });


    jQuery(window).resize(function(){
        if(!Mobile.yes){
            Collection.resize();
        }
    });
});







