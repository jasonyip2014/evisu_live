jQuery('img').css({'opacity': 0 });

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
        jQuery(document).on('mouseenter', container + ' .level-2 a', function() {
            menuCategoryImage.stop(true, true).animate({opacity:0}, 'fast');
            menuCategoryImage.attr('src', jQuery(this).attr('rel'));
        });
        jQuery(document).on('mouseleave', container + ' .level-2 a', function() {
            menuCategoryImage.stop(true, true).animate({opacity:0}, 'fast');
            menuCategoryImage.attr('src', menuCategoryImage.attr('rel'));
        });
    },

    shopMouseEnter : function(element){
        var self = this;
        if(!self.over)
        {
            navLiChild = element.find(' > ul');
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
        navLiChild = element.parent().find(' ul');
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
    itemWidth : '33.3333%',
    itemsCount : 0,
    itemsVisible : 3,
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
        if(this.itemsCount > this.itemsVisible)
        {
            jQuery('#mini-cart').css({textAlign:'left'});

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
    },
    resize : function(){
        var self = this;
        if(self.itemsCount > 0)
        {
            if(jQuery(window).width() > 1024)
            {
                this.itemWidth = '33.3333%';
                jQuery('#mini-cart li').css({width:this.itemWidth});
                self.itemsVisible = 3;
            }
            else
            {
                this.itemWidth = '50%'
                jQuery('#mini-cart li').css({width:this.itemWidth});
                self.itemsVisible = 2;
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



//============onDocumentReady==============
jQuery(function($)
{
    SearchAutocomplete.init();

    //init page

    $('#custom-currency-selector').customSelect();
	$('html,body').scrollTop(0);


    //$('img').css('opacity', 0); //hide img while loading move to begin of onready

    // === Navigation Animation
    EvisuNavigation.init('#nav');

    $('img').on('load', function() {
        $(this).stop(true, true).animate({opacity: 1}, 'fast'); // show img when loaded
    });

    //==============onDocumentLoad===============
    $(window).load(function(){

        AjaxBasket.resize();
        $('.img').stop(true, true).animate({opacity: 1}, 'fast'); // show img when loaded fiximgloadevent!!!

        //$('body').fadeIn();
    });
    $(document).ready(function(){
        //$('body').fadeIn();
       
    });
});

jQuery(window).resize(function(){
    AjaxBasket.resize();
});
