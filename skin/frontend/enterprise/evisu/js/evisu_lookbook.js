var LookBook = {
    positions : [],
    currentId : null,
    galleryId : null,
    config : [],
    orgWindowWidth : 1920,
    origGalleryHeight : 0,
    shareData : [],
    gallery :
    {
        width : 0,
        visibleWidth : 0,
        visibleCount : 0,
        visibleItemWidth : 0
    },

    init : function(config, positions, currentId)
    {
        //init def
        var self = this;
        this.config = config;
        this.positions = positions;
        this.currentId = currentId;
        this.galleryId = currentId;


        //==========================behavior===========================
        //archive
        jQuery('#archive-btn').on('click', function(){self.showArchive()});
        jQuery('#archive').find('.archive-close-btn').on('click', function(){self.hideArchive()});

        //looks change
        jQuery('#main-section').find('.next-btn').on('click', function(){self.next()});
        jQuery('#main-section').find('.prev-btn').on('click', function(){self.prev()});

        if(!Mobile.yes){
            //gallegy
            jQuery('#gallery-btn').on('click', function(){self.toggleGallery(jQuery(this))});
            jQuery('#thumbnail-section').find('.next-btn').on('click', function(){self.nextGallery()});
            jQuery('#thumbnail-section').find('.prev-btn').on('click', function(){self.prevGallery()});
            jQuery('#thumbnail-section').find('li').on('mouseenter',function(){
                jQuery(this).stop(true,false).animate({opacity: 1}, 'fast');
            });
            jQuery('#thumbnail-section').find('li').on('mouseleave',function(){
                jQuery(this).stop(true,false).animate({opacity: 0.4}, 'fast');
            });
        }
        jQuery('#thumbnail-section').find('li').on('click', function(){self.showLook(jQuery(this).data('id'),false)});


        //esc
        jQuery(document).keydown(function(e){if(e.keyCode == 27){self.hideAll()}});

        this.origGalleryHeight = jQuery('#thumbnail-section').find('ul').height();

    },

    initOnLoad : function(){
        this.showGalleryNavigation();
        this.getGalleryProp();

        if(Mobile.yes){
            var $bigImages =  jQuery('#thumbnail-section').find('img');
            jQuery.each($bigImages, function(index, img){
                $img = jQuery(img);
                if($img.hasClass('ladscape')){
                    var left_range = ($img.width() - 212) / 2;
                    $img.css({marginLeft: '-' + left_range + 'px'});
                } else {
                    var top_range = ($img.height() - 318) / 2;
                    $img.css({marginTop: '-' + top_range + 'px'});
                }
            });
        }
    },

    resize : function()
    {
        var self = this;
        this.getGalleryProp();

        //resize gallery toDo Optimization (from choice.)
        var element;
        var container = jQuery('#thumbnail-section');
        self.galleryId = self.galleryId;
        var marginMax = self.gallery.width - container.find('ul').width();
        var marginLeft = 0;
        for(var i = 0; i < self.positions[self.galleryId]; i++)
        {
            element = container.find('#gall-trumb-' + self.config[i].id);
            marginLeft += element.width() + parseFloat(element.css('marginRight'));
        }
        if(marginLeft <= 0)
        {
            marginLeft = 0;
            self.galleryId = self.config[0].id;
        }
        else if(marginLeft > marginMax)
        {
            marginLeft = marginMax;

            self.galleryId = self.config[self.positions['length'] - 1].id;
        }
        container.find('ul>li:first').css({marginLeft : -marginLeft});
    },

    prev : function()
    {
        var self = this;
        self.showLook(self.getPrevId(),true);
    },

    next : function()
    {
        var self = this;
        self.showLook(self.getNextId(),true);
    },

    showLook : function(id, animate)
    {

        var self = this;


        self.choiceGalleryItem(id);
        var mainSectionContainer = jQuery('#main-section');
        var mainImage = new Image();
        mainSectionContainer.find('.image-holder').addClass('loading');
        mainImage.onload = function()
        {
            mainSectionContainer.find('img').attr({src:self.config[self.positions[id]].main_image});
            mainSectionContainer.find('img').delay(500).animate({opacity:1} ,'slow');
            mainSectionContainer.find('.image-holder').removeClass('loading');

        };
        mainSectionContainer.find('img').stop(true,true).animate({opacity:0}, 'slow', function(){
            mainImage.src = self.config[self.positions[id]].main_image;
        });

        self.currentId = id;
        if(!Mobile.yes){
            var productSectionContainer = jQuery('#product-section');
            productSectionContainer.stop(true,false).slideUp('fast', function(){
                var assocProduct = self.config[self.positions[id]].assoc_product;
                if(assocProduct)
                {
                    var content = '' +
                    '<div class="product">' +
                        '<div class= name>' +self.config[self.positions[id]].title + '</div>' +
                        '<div class="assoc-products">';
                            for(var ass_prod in assocProduct.associated_products)
                            {
                                if(assocProduct.associated_products.hasOwnProperty(ass_prod))
                                {
                                    content += '' +
                                    '<div class="assoc-product">' +
                                        '<div class="name">' + assocProduct.associated_products[ass_prod].name + '</div>'+
                                        '<div class="second-name">' + assocProduct.associated_products[ass_prod].second_name + '</div>';
                                        if(assocProduct.associated_products[ass_prod].price_old != assocProduct.associated_products[ass_prod].price)
                                        {
                                            content += '' +
                                            '<div class="old-price">' + assocProduct.associated_products[ass_prod].price_old + '</div>';
                                        }
                                        content +=''+
                                        '<div class="price">' + assocProduct.associated_products[ass_prod].price + '</div>' +
                                    '</div>';
                                }
                            }
                            content += '' +
                            '<div class="buttons">' +
                                '<a href="' + assocProduct.url + '" >Shop The Look</a>' +
                                '<a href="' + assocProduct.wishlistUrl + '" >Add To Wishlist</a>' +
                            '</div>' +
                            '<div class="social-share">Share</div>' +
                            '<ul class="social-media">' +
                                '<li><a href="javascript: void(0);" title="Mail" class="mail" onclick="LookBook.mailToFriend()">Mail</a></li>' +
                                '<li><a onclick="LookBook.shareTwitter();" href="javascript: void(0)" title="Twitter" class="twitter">Twitter</a></li>' +
                                '<li><a onclick="LookBook.shareFacebook()" href="javascript: void(0)" title="Facebook" class="facebook">Facebook</a></li>' +
                                '<li><a onclick="LookBook.shareWeibo()" href="javascript: void(0)"class="weibo" title="Weibo" >Weibo</a></li>' +
                                '<li><a onclick="LookBook.sharePinterest()" href="javascript: void(0)" title="Pinterest" class="pinterest">Pinterest</a></li>' +
                                '<li><a onclick="LookBook.shareGooglePlus()" href="javascript: void(0)"title="Google+" class="gplus" >G+</a></li>' +
                            '</ul>' +
                        '</div>' +
                    '</div>' +
                    '<div class="media">' +
                        '<div class="prev-btn no-display">Prev</div>' +
                        '<ul>';
                            var itemsCount = 0;
                            for(var ass_prod in assocProduct.associated_products)
                            {
                                if(assocProduct.associated_products.hasOwnProperty(ass_prod))
                                {
                                    content += '' +
                                    '<li>' +
                                        '<div class="image-holder">' +
                                        '<img width="250px" height="520px" src="' + assocProduct.associated_products[ass_prod].image + '" alt="" />' +
                                        '</div>' +
                                    '</li>';
                                }
                                itemsCount++;
                            }
                        content += '' +
                        '</ul>' +
                        '<div class="next-btn no-display">Next</div>' +
                    '</div>';
                    self.setShareData(id);
                    jQuery("#product-section").html(content);

                    productSectionContainer.delay('1000').stop(true,false).slideDown('slow', function(){ProductCarousel.init(itemsCount)});
                }
            });
        }
    },

    showGalleryNavigation : function()
    {
        var galleryWidth = 0;
        var container = jQuery('#thumbnail-section');
        jQuery.each(container.find('ul>li'), function(index, item){
            galleryWidth += jQuery(item).width() + parseInt(jQuery(item).css('marginRight'));
        });
        if(container.find('ul').width() < galleryWidth)
        {
            container.find('.prev-btn').fadeIn();
            container.find('.next-btn').fadeIn();
        }
        else
        {
            container.find('.prev-btn').fadeOut();
            container.find('.next-btn').fadeOut();
        }
    },

    showArchive : function()
    {
        jQuery('#archive').fadeIn('slow');
    },

    hideArchive : function()
    {
        jQuery('#archive').fadeOut('normal');
    },

    toggleGallery : function(btn) {
        if(btn.hasClass('opened'))
        {
            jQuery('#thumbnail-section').slideUp('normal');
            btn.removeClass('opened').addClass('closed').html(btn.data('closedtext'));
        }
        else
        {
            jQuery('#thumbnail-section').slideDown('normal');
            btn.removeClass('closed').addClass('opened').html(btn.data('openedtext'));
        }
    },

    hideAll : function()
    {
        this.hideArchive();
    },

    nextGallery : function()
    {
        var self = this;
        var nextId = self.getNextGalleryId();
        var container = jQuery('#thumbnail-section');
        if(self.positions[nextId] != self.gallery.visibleCount - 1)
        {
            var element = container.find('#gall-trumb-' +  nextId);
            var marginLeft = element.offset().left + element.width() + parseFloat(element.css('marginLeft')) - container.find('ul').width() - container.find('ul').offset().left;
            container.find('ul>li:first').stop(true,true).animate({marginLeft : '-=' + marginLeft},'slow');
        }
        else
        {
            container.find('ul>li:first').stop(true,true).animate({marginLeft : 0},'slow');
        }
        self.galleryId = nextId;
    },

    prevGallery : function() {
        var self = this;
        var prevId = self.getPrevGalleryId();
        var container = jQuery('#thumbnail-section');
        if(self.positions[prevId] != self.positions['length'] - 1)
        {
            var element = container.find('#gall-trumb-' +  prevId);
            var marginLeft = jQuery('#thumbnail-section').find('ul').offset().left - element.offset().left; //- (self.gallery.visibleWidth - self.gallery.visibleItemWidth);
            container.find('ul>li:first').stop(true,true).animate({marginLeft : '+=' + marginLeft},'slow');
        }
        else
        {
            container.find('ul>li:first').stop(true,true).animate({marginLeft : -(self.gallery.width - self.gallery.visibleWidth)},'slow');
        }
        self.galleryId = prevId;
    },

    getGalleryProp : function() {
        var self = this;
        var width = 0;
        var container = jQuery('#thumbnail-section');
        container.find('ul').height(self.origGalleryHeight * (jQuery(window).width() / self.orgWindowWidth));
        jQuery.each(container.find('.image-holder'),function(index,element){
            jQuery(element).width(jQuery(element).find('img').width());
        });
        self.gallery.visibleWidth = container.find('ul').width();
        self.gallery.visibleCount = 0;
        self.gallery.visibleItemWidth = 0;
        self.gallery.offsetLeft = container.find('ul').offset().left;


        for(var i = 0; i < self.positions['length']; i++)
        {
            element = container.find('#gall-trumb-' + self.config[i].id);

            self.config[i].galleryItemWidth = element.width() + parseFloat(element.css('marginRight'));
            width += self.config[i].galleryItemWidth;
            if(width <= self.gallery.visibleWidth)
            {
                self.gallery.visibleItemWidth = width;
                self.gallery.visibleCount++;
            }
        }
        self.gallery.width = width;

    },

    choiceGalleryItem : function(itemId)
    {

        var self = this;

        var container = jQuery('#thumbnail-section');
        container.find('.active').stop(true,false).animate({opacity: 0.4}, 'fast', function(){jQuery(this).removeClass('active')});
        element = container.find('#gall-trumb-' + itemId).stop(true,false).animate({opacity: 1}, 'fast', function(){jQuery(this).addClass('active')});
        jQuery('html,body').animate({scrollTop:0}, 'slow');
        if(!Mobile.yes){
            //if(self.galleryId != itemId)
            //{
                var element;
                self.galleryId = itemId;
                var marginMax = self.gallery.width - container.find('ul').width();
                var marginLeft = 0;
                for(var i = 0; i < self.positions[itemId]; i++)
                {
                    element = container.find('#gall-trumb-' + self.config[i].id);
                    marginLeft += element.width() + parseFloat(element.css('marginRight'));
                }
                if(marginLeft <= 0)
                {
                    marginLeft = 0;
                    self.galleryId = self.config[0].id;
                }
                else if(marginLeft > marginMax)
                {
                    marginLeft = marginMax;

                    self.galleryId = self.config[self.positions['length'] - 1].id;
                }
                container.find('ul>li:first').animate({marginLeft : -marginLeft},'slow');
            //}
        }
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
        return self.config[nextId]['id'];

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
        return self.config[prevId]['id'];
    },

    getNextGalleryId : function() {
        var self = this;
        var nextPosition;
        var currentPosition = self.positions[self.galleryId];

        if(currentPosition >= self.positions.length - 1)
        {
            nextPosition = self.gallery.visibleCount - 1;
        }
        else if(currentPosition < self.gallery.visibleCount - 1)
        {
            nextPosition = self.gallery.visibleCount;
        }
        else
        {
            nextPosition = currentPosition + 1;
        }
        return self.config[nextPosition]['id'];
    },

    getPrevGalleryId : function() {
        var self = this;
        var prevPosition;
        var currentPosition = self.positions[self.galleryId];

        if(currentPosition <= 0)
        {
            prevPosition = self.positions['length'] - 1;
        }
        //else if(currentPosition == self.gallery.visibleCount)
        //{
        //    prevPosition = 0;
        //}
        else if(currentPosition >  self.positions['length'] - self.gallery.visibleCount)
        {
            prevPosition = currentPosition - self.gallery.visibleCount + 1;
        }
        else
        {
            prevPosition = currentPosition - 1;
        }
        return self.config[prevPosition]['id'];
    },

    setShareData : function(id)
    {
        var shareData = this.config[this.positions[id]].shareData;
        this.shareData['url'] = encodeURIComponent(shareData['url']);
        this.shareData['title'] = encodeURIComponent(shareData['name']);
        this.shareData['summary'] = encodeURIComponent(shareData['description']);
        this.shareData['image'] = encodeURIComponent(shareData['image']);
    },
    //shared functions
    shareTwitter : function()
    {
        window.open('https://twitter.com/intent/tweet?text=' + this.shareData['title'] + ': &url=' + this.shareData['url'],'sharer','toolbar=0,status=0,width=548,height=325');
    },

    shareFacebook : function()
    {
        window.open('http://www.facebook.com/sharer.php?s=100&amp;p[title]=' + this.shareData['title'] + '&amp;p[summary]=' + this.shareData['summary'] + '&amp;p[url]=' + this.shareData['url'] + '&amp;&amp;p[images][0]=' + this.shareData['image'],'sharer','toolbar=0,status=0,width=548,height=325');
    },

    sharePinterest : function()
    {
        window.open('http://pinterest.com/pin/create/button/?url=' + this.shareData['url'] + '&media=' + this.shareData.image + '&description=' + this.shareData['title'] + ' - ' + this.shareData['summary'],'sharer','toolbar=0,status=0,width=548,height=325');
    },

    shareGooglePlus : function()
    {
        window.open('https://plus.google.com/share?url=' + this.shareData['url'],'sharer','toolbar=0,status=0,width=548,height=325');
    },

    shareWeibo : function()
    {
        window.open('http://service.weibo.com/share/share.php?url=' + this.shareData['url'] + '&title=' + this.shareData['title'] + '&pic=' + this.shareData['image'],'sharer','toolbar=0,status=0,width=548,height=325')
    },

    mailToFriend : function()
    {
        return sa_tellafriend(decodeURIComponent(this.shareData['url']),'email');
    }
    //end shared functions
};

var ProductCarousel = {
    currentItem : 0,
    itemWidth : '33.3%',
    itemsCount : 0,
    itemsVisible : 3,
    nextButton : null,
    prevButton : null,

    prev : function(){
        jQuery('#product-section .media li:first').animate({marginLeft:'+=' + this.itemWidth});
        this.currentItem--;
        this.buttonBehavior();
    },
    buttonBehavior : function()
    {
        if(this.itemsCount > this.itemsVisible)
        {
            if(this.currentItem == 0){
                this.nextButton.show();
                this.prevButton.hide();
            }
            else if(this.currentItem == this.itemsCount - this.itemsVisible)
            {
                this.nextButton.hide();
                this.prevButton.show();
            }
            else
            {
                this.nextButton.show();
                this.prevButton.show();
            }
        }
    },
    next : function(){
        jQuery('#product-section .media li:first').animate({marginLeft:'-=' + this.itemWidth});
        this.currentItem++;
        this.buttonBehavior();
    },
    init : function(itemsCount){
        this.itemsCount = itemsCount;
        this.nextButton = jQuery('#product-section').find('.next-btn');
        this.prevButton = jQuery('#product-section').find('.prev-btn');
        var self = this;
        if(this.itemsCount > this.itemsVisible)
        {
            this.nextButton.show();
            this.prevButton.on('click', function(){
                self.prev();
                return false;
            });
            this.nextButton.on('click', function(){
                self.next();
                return false;
            });
        }
        this.resize();
        //this.buttonBehavior();
    },
    resize : function(){
        var self = this;
        if(self.itemsCount > 0)
        {
            if(jQuery(window).width() > 1024)
            {
                this.itemWidth = '33.3333%';
                jQuery('#product-section .media li').css({width:this.itemWidth});
                self.itemsVisible = 3;
            }
            else
            {
                this.itemWidth = '50%'
                jQuery('#product-section .media li').css({width:this.itemWidth});
                self.itemsVisible = 2;
            }
            self.buttonBehavior();
        }
    }
};

jQuery(window).load(function(){
    LookBook.initOnLoad();
});


jQuery(window).resize(function(){
    if(!Mobile.yes){
        LookBook.resize();
        ProductCarousel.resize();
    }
});






