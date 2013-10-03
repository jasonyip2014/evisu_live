var EvisuNavigation =
{
    /* Main menu animation class */
    over : false,
    self : null,
    menuCategoryImage : null,

    init : function(container){
        var self = this;
        navLi = jQuery(container + ' > li');
        menuCategoryImage = jQuery(container + ' #menu-category-image');

        //Shop Open|Close
        navLi.on('mouseenter', function() {self.shopMouseEnter(jQuery(this))});
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
        navLiChild = element.find(' > ul');
        if (!navLiChild){
            return;
        }
        else{
            navLiChild.stop(true,true).fadeIn('fast');
        }
        self.over = true;
    },

    shopMouseLeave : function(element){
        var self = this;
        navLiChild = element.find(' > ul');
        if (!navLiChild){
            return;
        }
        else{
            setTimeout(function(){
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




//============onDocumentReady==============
jQuery(function($)
{
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
        //$('body').fadeIn();
    })
    $(document).ready(function(){
        //$('body').fadeIn();
       
    });
});
