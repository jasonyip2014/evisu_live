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
        self.setActive();
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
    },

    setActive: function(){
        var url = document.location.href;
        var find = false;
        jQuery.each(jQuery('#nav>li'),function(){
            var $this = jQuery(this);
            if(url.indexOf('/' + $this.data('url') + '/') + 1){
                $this.addClass('active');
                find = true;
                return false;
            }
        });
        if(!find){
            jQuery('#nav>li:first').addClass('active');
        }
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
        var self = this;
        this.itemsCount = itemsCount;
        this.nextButton = jQuery('#basket-next-item');
        this.prevButton = jQuery('#basket-previous-item');
       // jQuery('#cartHeader').on('click', function(){
        //    self.resize();
        //});

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
        jQuery('#mini-cart').find('li.item .btn-remove').on('click', function(e){
            e.preventDefault();
            jQuery('.ajax-basket-loader').fadeIn();
            jQuery.get(jQuery(this).attr('href')).done(function(data){
                jQuery('.top-cart-holder').html(jQuery(data).find('.top-cart')).find('#topCartContent').show();
                jQuery('.top-cart-holder').find('.block-title').addClass('expanded');
                var $miniCart = jQuery(data).find('#mini-cart');
                if($miniCart.length){
                    self.init($miniCart.find('li.item').length);
                    self.resize();
                } else {
                    setTimeout(function(){
                        self.init(0);
                        Enterprise.TopCart.hideCart();
                    },1000);
                }
                jQuery('.ajax-basket-loader').fadeOut();
            });
        });

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
        if(!Mobile.yes){
            jQuery( "#topCartContent .product-image img" ).css({'height': jQuery(window).height() - 490});
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
        var self = this;
        var $link = jQuery(element);
        $link.closest(self.block).css({zIndex:7});
        var coverImage = $link.next();
        var frame = $link.prev().children(':first');

        frame.attr('src', frame.data('src'));
        $link.addClass('loading');

        frame.load(function() {
            var player = $f(this);

            player.addEvent('ready', function() {
                $link.removeClass('loading');
                coverImage.fadeOut('fast');
                $link.hide();

                player.addEvent('finish', function() {
                    $link.show();
                    coverImage.fadeIn('fast');
                    $link.closest(self.block).css({zIndex:0});
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
            jQuery('body').addClass('mobile');
        }
        if(window.location.hash == '#mobile')
        {
            self.yes = true;
        };
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



var NewsletterSubscribe = {

    onReady: function(){
        var self = this;
        self.varienForm = new VarienForm('newsletter-validate-detail');
        self.form = jQuery('#newsletter-validate-detail');
        self.form.on('submit', function(){
            self.sendData();
            return false;
        });
    },
    sendData : function()
    {
        var self = this;
        var resultDiv = self.form.find('.result');

        if(self.varienForm.validator.validate())
        {
            resultDiv.hide();
            self.form.find('.loader').show();
            jQuery.post(self.form.attr('action'), self.form.serialize()).done(function(data){
                self.form.find('.loader').hide();
                //console.log(jQuery(data).find('.messages'));
                resultDiv.html(data);
                resultDiv.fadeIn('slow',function(){
                    resultDiv.delay(5000).fadeOut('slow');
                });
                //resultDiv.html('<ul><li class="success-msg"><ul><li><span>Thank you for your subscription.</span></li></ul></li></ul>');
            }).error(function(){
                resultDiv.html('request error');
            });
        }
    }
};

var SwitchStore = {
    location: null,
    ipCountryCode: '', //set in template
    skinUrl: null, //set in template
    currentStore: null, //set in template
    countryCookie: null, //set in init
    onReady: function(){
        var self = this;

        self.switchSelect = jQuery('#select-country');
        self.popupContainer = jQuery('#change-country-popup');
        self.siteHider = jQuery('#site-hidder');
        self.topHider = jQuery('#top-hider');
        self.init();
        self.switchSelect.customSelect();//after init (it's important)

        self.switchSelect.on('change', function(){
            var $this = jQuery(this);
            var $selectedItem = $this.find('option[value="' + $this.val() + '"]');
            self.popupContainer.find('.switch-currency').html($selectedItem.data('currency'));
            self.popupContainer.find('.switch-origin').html($selectedItem.data('origin'));
            self.location = $selectedItem.data('url');
            self.switchStore = $selectedItem.data('store');
            //console.log(self.location);
            jQuery('#change-country-popup-update-btn').html('Confirm');
        });

        // change country behavior

        jQuery('#change-country-popup-update-btn').on('click', function(){
            self.closePopup();
            //console.log(self.location);
            if(self.location){
                setLocation(self.location);
            }
            jQuery.cookie('__switch-county', self.switchSelect.val(), {expires: 366, path: '/' });
            jQuery.cookie('__switch-store', self.switchStore, {expires: 366, path: '/' });
        });
        if(!self.countryCookie){
            self.showPopup();
        }
    },
    showPopup: function(){
        var self = this;
        self.popupContainer.fadeIn('slow');
        self.siteHider.stop(true,true).fadeIn();
        self.topHider.stop(true,true).fadeIn();
    },
    closePopup: function(){
        var self = this;
        self.popupContainer.fadeOut('fast');
        self.siteHider.stop(true,true).fadeOut();
        self.topHider.stop(true,true).fadeOut();
    },
    init: function(){
        var self = this;
        var $selectedItem = [];
        self.countryCookie = jQuery.cookie('__switch-county');
        if(self.countryCookie){
            //set by cookie
            $selectedItem = self.switchSelect.find('option[value="' + self.countryCookie + '"]');
            //console.log(self.countryCookie);
            self.switchSelect.val(self.countryCookie);
            self.mode = 'cookie';
            //console.log('cookie ' + self.countryCookie);
        } else {
            //set by geoip
            if(self.ipCountryCode !=''){
                $selectedItem = self.switchSelect.find('option[value="' + self.ipCountryCode + '"]');
                self.mode = 'geoip';
                //console.log('geoip ' + self.ipCountryCode);
                //console.log($selectedItem);
            }
        }
        if(!$selectedItem.length || ($selectedItem.data('store') != self.currentStore && self.mode != 'geoip') ){
            //set first in current store
            jQuery.each(self.switchSelect.find('option'), function(){
                var $this = jQuery(this);
                if($this.data('store') == self.currentStore){
                    $selectedItem = $this;
                    self.mode="default"
                    //console.log('default ' + $this.attr('value'));
                    return false;
                }
            });
        }

        //change data
        jQuery('#change-country-btn').find('span').html($selectedItem.html());
        jQuery('#change-country-btn').find('img').attr({src: self.skinUrl + 'images/flags/' + $selectedItem.attr('value').toLowerCase() + '.png'});
        self.popupContainer.find('.switch-currency').html($selectedItem.data('currency'));
        self.popupContainer.find('.switch-origin').html($selectedItem.data('origin'));
        self.switchSelect.val($selectedItem.attr('value'));
        self.switchSelect.customSelectRefresh();
        self.switchStore = $selectedItem.data('store');


        //add behaviour
        if(!self.countryCookie){
            self.location = $selectedItem.data('url');
            jQuery('#change-country-popup-update-btn').html('Switch Country');
            jQuery('#change-country-popup .close-btn').hide();
        } else {
            jQuery('#change-country-btn').on('click', function(){
                self.showPopup();
            });
            jQuery('#site-hidder').on('click', function(){
                self.closePopup();
            });
            jQuery(document).on('keydown', function(e){
                if (e.keyCode == 27) { //escape
                    self.closePopup();
                }
            });
            jQuery('#top-hider').on('click', function(){
                self.closePopup();
            });
            jQuery('#change-country-popup .close-btn').on('click', function(){
                self.closePopup();
            });
        }
    }
};

//============onDocumentReady==============
jQuery(function($)
{
    if(!Mobile.yes){
        SwitchStore.onReady();
    }
    SearchAutocomplete.init();
    // === Navigation Animation
    EvisuNavigation.init('#nav');
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

    }
});

jQuery(window).resize(function(){
    AjaxBasket.resize();
});


// My Account Mobile

jQuery(window).load(function(){
    var $dashboard = jQuery('.dashboard');
    if($dashboard.length){
        $dashboard.find('.block-title').on('click', function(){
            if(Mobile.yes){
                var self = jQuery(this);
                if(!self.hasClass('active')){
                    $dashboard.find('.block-title').removeClass('active');
                    $dashboard.find('.block-wrapper').slideUp('normal');
                    self.next().stop(true, true).slideDown('normal');
                    self.addClass('active');
                }
                else{
                    self.next().stop(true, true).slideUp('normal');
                    self.removeClass('active');
                }
            }
        });
    }
    jQuery(window).resize();
});

// Fixed header

jQuery(function($){

    //myaccount resize
    var $dashboard = jQuery('.dashboard');
    if($dashboard.length){
        if(!Mobile.yes){
            var height = $dashboard.find('.address-block').height();
            $dashboard.find('.account-block').height(height);
            $dashboard.find('.info-block').height(height);
        }
    }
    if(!Mobile.yes){
        NewsletterSubscribe.onReady();
    }
    if(!Mobile.yes && !Mobile.isIPad){
        $(window).scroll(function(){
            var $topPosition = $(window).scrollTop();
            if ($topPosition > 24){
                $('.homepage-holder').addClass('fixed');
                $('.branding-holder').addClass('fixed');
                $('.top-bar').addClass('fixed');
            }
            else{
                $('.homepage-holder').removeClass('fixed');
                $('.branding-holder').removeClass('fixed');
                $('.top-bar').removeClass('fixed');
            }
        });
    }
});
