var EvisuNavigation =
{
    /* Main menu animation class */
    over : false,
    self : null,

    init : function(container){
        self = this;
        navLi = jQuery(container + ' > li');
        navLi.on('mouseenter', function() {self.mouseEnter(jQuery(this))});
        navLi.on('mouseleave', function() {self.mouseLeave(jQuery(this))});
    },

    mouseEnter : function(element){
        navLiChild = element.find(' > ul');
        if (!navLiChild){
            return;
        }
        else{
            navLiChild.stop().fadeIn(300);
        }
        self.over = true;
    },

    mouseLeave : function(element){
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
                    navLiChild.stop().fadeOut(300);
                }
            }, 500);
        }
        self.over = false;
    }
};


//onDocumentReady
jQuery(function($)
{
    $('#custom-currency-selector').customSelect();
	$('html,body').scrollTop(0);
    // === Navigation Animation
    //EvisuNavigation.init('#nav');

    $('img').on('load', function() {
        $(this).css('opacity', 1);
    });
});

