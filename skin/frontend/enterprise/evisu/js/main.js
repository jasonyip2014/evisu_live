jQuery('img:not(.loaded)').css({'opacity': 0 });

var EvisuNavigation =
{
    /* Main menu animation class */
    over : false,
    self : null,
    menuCategoryImage : null,
    timer : null,

    init : function(container){
        var self = this;
        navLi = jQuery(container + ' > li');
        menuCategoryImage = jQuery(container + ' #menu-category-image');

        //Shop Open|Close
        navLi.on('mouseenter', function(){clearTimeout(self.timer)});
        navLi.on('click', '.main-menu-item', function() {self.shopMouseEnter(jQuery(this).parent())});
        navLi.on('mouseleave', function() {self.shopMouseLeave(jQuery(this))});

        //Category thumbnail change
        /*
        jQuery(document).on('mouseenter', container + ' .level-2 a', function() {
            menuCategoryImage.stop(true, true).animate({opacity:0}, 'fast');
            menuCategoryImage.attr('src', jQuery(this).attr('rel'));
        });
        jQuery(document).on('mouseleave', container + ' .level-2 a', function() {
            menuCategoryImage.stop(true, true).animate({opacity:0}, 'fast');
            menuCategoryImage.attr('src', menuCategoryImage.attr('rel'));
        });
        */
    },

    shopMouseEnter : function(element){
        var self = this;
        if(!self.over)
        {
            navLiChild = element.find(' > .shop-menu-container');
            if (!navLiChild){
                return;
            }
            else{
                navLiChild.stop(true,true).fadeIn('fast');
            }
            self.over = true;
        }
        else
        {
            navLiChild.stop(true,true).fadeOut('fast');
            self.over = false;
        }
    },

    shopMouseLeave : function(element){
        var self = this;
        navLiChild = element.parent().find(' .shop-menu-container');
        if (!navLiChild){
            return;
        }
        else{
            self.timer = setTimeout(function(){
                if (self.over == true){
                    return;
                }
                else{
                    navLiChild.stop(true,true).fadeOut('fast');
                }
            }, 500);
        }
        self.over = false;
    }
};

var AjaxBasket = {
    currentItem : 0,
    itemWidth : '16.2%',
    itemsCount : 0,
    itemsVisible : 6,
    nextButton : null,
    prevButton : null,

    prev : function(){
        jQuery('#ajax-basket-item-0').animate({marginLeft:'+=' + this.itemWidth});
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
        else
        {
            this.nextButton.hide();
            this.prevButton.hide();
        }
    },
    next : function(){
        jQuery('#ajax-basket-item-0').animate({marginLeft:'-=' + this.itemWidth});
        this.currentItem++;
        this.buttonBehavior();
    },
    init : function(itemsCount){
        this.itemsCount = itemsCount;
        this.nextButton = jQuery('#basket-next-item');
        this.prevButton = jQuery('#basket-previous-item');
        var self = this;

            //jQuery('#mini-cart').css({textAlign:'left'});


        this.prevButton.on('click', function(){
            self.prev();
            return false;
        });
        this.nextButton.on('click', function(){
            self.next();
            return false;
        });
        if(this.itemsCount > this.itemsVisible)
        {
            this.nextButton.show();
        }
    },
    resize : function(){
        var self = this;
        if(self.itemsCount > 0)
        {
            if(jQuery(window).width() > 1500)
            {
                this.itemWidth = '16.2%';
                jQuery('#mini-cart li').css({width:this.itemWidth});
                self.itemsVisible = 6;
            }
            else
            {
                this.itemWidth = '33%';
                jQuery('#mini-cart li').css({width:this.itemWidth});
                self.itemsVisible = 3;
            }
            self.buttonBehavior();
        }
        //self.itemWidth = jQuery('#ajax-basket-item-0').width();
        //jQuery('#ajax-basket-item-0').css({marginLeft:'-' + (self.itemWidth * self.currentItem) + 'px'});
    }
};

// ajax search ==========================================
var SearchAutocomplete = {
    minQuery : 2,
    timeout : null,

    init : function(){
        var self = this;
        jQuery('#main-search-input').on('keyup', function(){
            var query = this.value;

            if (query.length >= self.minQuery)
            {
                clearTimeout(self.timeout);

                self.timeout = setTimeout(function() {
                    jQuery('#search-autocomplete-loader').fadeIn('fast');
                    jQuery.ajax(
                    {
                        type: 'get',
                        url: jQuery('#search_mini_form').attr('action'),
                        data: 'q=' + query + '&ajax=1',
                        dataType: 'html',
                        cache: true,

                        success: function(data)
                        {
                            jQuery('#search-autocomplete-loader').fadeOut('fast');

                            jQuery('#search_autocomplete').html(jQuery(data).find('#quick-search-result-list'));
                            if(Mobile.yes){
                                jQuery('#search_autocomplete').find('.no-mobile').remove();
                            }
                        },
                        error:  function(xhr, str)
                        {
                            console.log('error');
                        }
                    });
                }, 500);
            }
        });

        jQuery(document).on('click', '#quick-search-view-all-btn', function(){
            jQuery('#search_mini_form').submit();
        });

        jQuery('#search_mini_form').submit(function(){
            if(jQuery('#main-search-input').val().length < self.minQuery)
            {
                return false;
            }
        });

        jQuery('.search-btn').on('click',function(){
            Enterprise.TopCart.hideCart();
            jQuery('.quick-search').stop(true,false).slideToggle('normal');
            jQuery('#site-hidder').fadeToggle('fast');

        });

        jQuery('#search-autocomplete-close-btn').on('click',function(){
            jQuery('.quick-search').stop(true,false).slideUp('normal');
            jQuery('#site-hidder').fadeOut('fast');
        });

        jQuery(document).on('click', '.top-cart > .block-title', function(){
            jQuery('.quick-search').stop(true,false).slideUp('normal');
            jQuery('#site-hidder').fadeOut('fast');
        });
    }
};


// ============ video_panel behavior=================
var VideoPanel = {
    block : '.video_panel',
    link : '.video-overlay-holder',
    coverImage : '.image-holder',
    video : '.video-wrapper',

    init : function(block){
        this.block = block;
        var self = this;
        jQuery(this.block + ' ' + this.link).on('click', function(){
            self.linkClick(this);
            return false;
        });

    },

    linkClick : function(element){
        var link = jQuery(element);
        var coverImage = link.next();
        var frame = link.prev().children(':first');

        frame.attr('src', frame.data('src'));
        link.addClass('loading');

        frame.load(function() {
            var player = $f(this);

            player.addEvent('ready', function() {
                link.removeClass('loading');
                coverImage.fadeOut('fast');
                link.hide();

                player.addEvent('finish', function() {
                    link.show();
                    coverImage.fadeIn('fast');
                });
            });
        });
    }
};

var Mobile = {
    yes: false,
    isIPad: false,
    cssFile: null,

    mobileUserAgents: [
        'android',
        'iphone',
        'blackberry',
        'blazer',
        'bolt',
        'symbian',
        'doris',
        'dorothy',
        'fennec',
        'gobrowser',
        'iemobile',
        'iris',
        'maemo',
        'minimo',
        'netfront',
        'opera mini',
        'opera mobi',
        'semc-browser',
        'skyfire',
        'teasharck',
        'teleca',
        'uzard web'
    ],
    // Returns the version of Internet Explorer or a -1 for other browsers.
    init: function(cssFile){
        var self = this;
        self.cssFile = cssFile;
        /*jQuery.each(self.mobileUserAgents, function(index, userAgent){
            if(navigator.userAgent.toLowerCase().indexOf(userAgent) != -1){
                self.yes = true;
                return false;
            }
        });*/

        //self.yes = true;// enable mobile version
        if(navigator.userAgent.toLowerCase().indexOf('ipad') != -1)
        {
            self.isIPad = true;
            jQuery('body').addClass('ipad');
        }
        if(screen.width <= 1023 && !self.isIPad)
        {
            self.yes = true;
        }
        if(self.yes)
        {
            //add mobile css
            jQuery('head').append('<link rel="stylesheet" href="' + Mobile.cssFile + '" type="text/css" />');
        }
    },
    remove: function(){
        var self = this;
        if(self.yes){
            jQuery('.no-mobile').remove();
        } else {
            jQuery('.mobile-only').remove();
        }
    }
};

//============onDocumentReady==============
jQuery(function($)
{


    SearchAutocomplete.init();

    //init page

    $('#custom-currency-selector').customSelect();
	$('html,body').scrollTop(0);


    //$('img').css('opacity', 0); //hide img while loading move to begin of onready



    $('img:not(.loaded)').on('load', function() {
        $(this).stop(true, true).animate({opacity: 1}, 'fast'); // show img when loaded
    });

    //==============onDocumentLoad===============
    $(window).load(function(){

        AjaxBasket.resize();
        $('.img:not(.loaded)').stop(true, true).animate({opacity: 1}, 'fast'); // show img when loaded fiximgloadevent!!!
    });

    if(Mobile.yes){
        jQuery('.burger-menu-btn').on('click', function(){
            jQuery('.burger-menu').stop(true, true).slideToggle('normal');
        });

    } else {

        // === Navigation Animation
        EvisuNavigation.init('#nav');
        // change country behavior
        jQuery('#change-country-btn').on('click', function(){
            jQuery('#change-country-popup').fadeIn('slow');
            jQuery('#site-hidder').stop(true,true).fadeIn();
            jQuery('#top-hider').stop(true,true).fadeIn();
        });
        jQuery('#site-hidder').on('click', function(){
            jQuery('#change-country-popup').fadeOut('fast');
            jQuery('#site-hidder').stop(true,true).fadeOut();
            jQuery('#top-hider').stop(true,true).fadeOut();
        });
        jQuery(document).on('keydown', function(e){
            if (e.keyCode == 27) { //escape
                jQuery('#change-country-popup').fadeOut('fast');
                jQuery('#site-hidder').stop(true,true).fadeOut();
                jQuery('#top-hider').stop(true,true).fadeOut();
            }
        });
        jQuery('#top-hider').on('click', function(){
            jQuery('#change-country-popup').fadeOut('fast');
            jQuery('#site-hidder').stop(true,true).fadeOut();
            jQuery('#top-hider').stop(true,true).fadeOut();
        });
        jQuery('#change-country-popup .close-btn').on('click', function(){
            jQuery('#change-country-popup').fadeOut('fast');
            jQuery('#site-hidder').stop(true,true).fadeOut();
            jQuery('#top-hider').stop(true,true).fadeOut();
        });
    }

});

jQuery(window).resize(function(){
    AjaxBasket.resize();
});


// My Account Mobile

jQuery(window).load(function(){

    jQuery('.dashboard .block-title').on('click', function(){
        var self = jQuery(this);
        if(!self.hasClass('active')){
            jQuery('.dashboard .block-title').removeClass('active');
            jQuery('.dashboard .block-wrapper').slideUp('normal');
            self.next().stop(true, true).slideDown('normal');
            self.addClass('active');
        }
        else{
            self.next().stop(true, true).slideUp('normal');
            self.removeClass('active');
        }
    });
});

// Fixed header

jQuery(function($){
    if(!Mobile.yes && !Mobile.isIPad){
        $(window).scroll(function(){
            var $topPosition = $(window).scrollTop();
            if ($topPosition > 24){
                $('.homepage-holder').addClass('fixed');
                $('.branding-holder').addClass('fixed');
                $('.branding-container').addClass('fixed');
                $('.top-bar').addClass('fixed');
            }
            else{
                $('.homepage-holder').removeClass('fixed');
                $('.branding-holder').removeClass('fixed');
                $('.branding-container').removeClass('fixed');
                $('.top-bar').removeClass('fixed');
            }
        });
    }
});