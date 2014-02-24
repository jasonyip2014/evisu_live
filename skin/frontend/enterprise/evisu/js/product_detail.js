/* Product Detail Page Scripts */

//super_attribute_fix (refresh custom selects) =============================
Product.Config.prototype.fillSelect_orig = Product.Config.prototype.fillSelect;
Product.Config.prototype.fillSelect = function(element){
    Product.Config.prototype.fillSelect_orig.apply(this, arguments);
    //alert(element.config.code);
    jQuery(element).customSelectRefresh();
    //if(jQuery())
};

//OOS configurable behavior===========================
Product.Config.prototype.configureElement_orig = Product.Config.prototype.configureElement;
Product.Config.prototype.configureElement = function(element){
    Product.Config.prototype.configureElement_orig.apply(this, arguments);

    // set default qty value
    $qtyField = jQuery('#qty');
    if($qtyField.val() == '')
    {
        $qtyField.val(1);
        $qtyField.customSelectRefresh();
    }

    if(AlertOOS.productType == 'grouped')
    {
        //disable grouped error message
        jQuery('#grouped-error-msg').fadeOut();
    }

    if(AlertOOS.productType != 'grouped') //disable preorder behavior for grouped product
    {

        // OOS & Preorder puttons behavior
        var optionId = element.value;
        var options = element.config.options;
        for(var option in options)
        {
            if(options.hasOwnProperty(option))
            {
                if(options[option].id == optionId)
                {
                    if(options[option].outOfStock)
                    {
                        jQuery('.add-to-cart').hide();
                        jQuery('.qty-block').hide();

                        AlertOOS.productId = options[option].productId;
                        AlertOOS.sku = options[option].sku;
                        jQuery('#oos-block').show();
                    }
                    else
                    {
                        if(parseInt(options[option].qty) <= 0)
                        {
                            jQuery(".btn-cart").html(AlertOOS.labels['preorder_button']);
                        }
                        else
                        {
                            jQuery(".btn-cart").html(AlertOOS.labels['add_button']);
                        }
                        jQuery('#oos-block').hide();
                        jQuery('.qty-block').show();
                        jQuery('.add-to-cart').show();
                    }
                }
            }
        }
    }
};



var MailToFriend = {
    showModalWindow : function(url){
        jQuery.arcticmodal({
            type: 'ajax',
            url: url,
            ajax: {
                type: 'GET',
                cache: false,
                dataType: 'html',
                success: function(data, el, responce) {
                    var content = jQuery('<div class="box-modal">' +
                        '<div class="box-modal_close arcticmodal-close">X</div>' +
                        '<p><b /></p><p />' +
                        '</div>');
                    //$('B', h).html(responce.title);
                    jQuery('P:last', content).html(jQuery(responce).find('.col-main'));
                    data.body.html(content);
                }
            }
        });
    }
};

var AlertOOS = {
    labels : {},
    productType : null,
    productId : null,
    sku : null,
    varienForm : null,

    init : function(){
        var self = this;
        self.varienForm = new VarienForm('alertoos-form');
        jQuery('#alertoos-form').on('submit', function() {
            if (self.varienForm.validator.validate()) {
                var form = jQuery(this);
                var url = form.attr('action');
                jQuery.post(url, form.serialize(), function(data){
                    jQuery('#alertoos-result').html(data);
                });
            }
            return false;
        });
    }
};

// Full size script
var FullSizeImage =
{

    fullSize : false,
    container : null,
    show : function()
    {
        if(!this.fullSize)
        {
            this.fullSize = true;
            var image = this.container.find('img');
            //jQuery('#main-image').width('220px').height('495px'); //ToDo Move To Css
            //image.height('100%').width('100%'); //ToDo Move To Css
            jQuery('.mousetrap').hide();//jzoom hide
            image.animate({opacity:0},'fast').attr('src',image.data('image'));
            this.container.css({zIndex:'1001'});
            this.container.addClass('full-size-state');
            this.container.animate({width:(jQuery(window).width() - parseInt(this.container.parent().parent().css('paddingLeft')) * 2) + 'px'}, function(){
                jQuery('#full_img_close').show();
                jQuery('.full-size-hider').fadeIn();
            });
        }
    },
    close : function()
    {
        self = this;
        if(this.fullSize)
        {
            this.fullSize = false;
            var image = this.container.find('img');
            image.animate({opacity:0},'fast').attr('src',image.attr('rel'));
            jQuery('.full-size-hider').fadeOut('fast');
            this.container.animate({width:'87.1%',left:0}, function(){
                self.container.css({zIndex:'0'});
                jQuery('.mousetrap').show(); //jzoom show
                self.container.removeClass('full-size-state');
            });

            jQuery('#full_img_close').hide();

        }
    },
    init : function() {
        this.container = jQuery('#main-image');
        jQuery('.btn-fullsize').click(function(){FullSizeImage.show()});
        jQuery('#full_img_close').click(function(){FullSizeImage.close()});
        jQuery('.full-size-hider').click(function(){FullSizeImage.close()});

        jQuery(document).keydown(function(e) {
            if(e.keyCode == 27 && FullSizeImage.fullSize)
            {
               FullSizeImage.close();  // esc
            }
        });
    },
    resize : function(){
        self = this;
        if(self.fullSize)
        {
            self.container.css({width:(jQuery(window).width() - parseInt(this.container.parent().parent().css('paddingLeft')) * 2) + 'px'});
        }
    }
};



jQuery(function($){

    // Init enrerprise Tabs
    var collateralTabs = new Enterprise.Tabs('collateral-tabs');
    collateralTabs.select();

    if(!Mobile.yes){
        jQuery('#main-image .product-img-zoom').CloudZoom();
    }

    //disable grouped error message when qty is changed
    if(AlertOOS.productType == 'grouped')
    {
        jQuery('.qty').on('change', function(){
            jQuery('#grouped-error-msg').fadeOut();
        });
    }

    //PEC videopanel behavior
    VideoPanel.init('.video_panel');

    // mail to friend //
    jQuery('#send-to-friend-btn').on('click', function() {
        MailToFriend.showModalWindow(jQuery(this).attr('href'));
        return false;
    });

    //image full size button behavior
    FullSizeImage.init();

    //========================== prev, next button behavior ===================================================

    $('.product-view .previous-product-btn').on('mouseenter', function(){
        $('.product-view .previous-product-holder .image-holder').stop(true, false).animate({opacity:.5});
        $('.product-view .previous-product .description-holder').stop(true, false).animate({opacity:1});
    });
    
    $('.product-view .previous-product-holder').on('mouseleave', function(){
        $(this).find('.image-holder').stop(true, false).animate({opacity:0});
        $(this).find('.description-holder').stop(true, false).animate({opacity:0});
    });

    $('.product-view .next-product-btn').on('mouseenter', function(){
        $('.product-view .next-product-holder .image-holder').stop(true, false).animate({opacity:.5});
        $('.product-view .next-product .description-holder').stop(true, false).animate({opacity:1});
    });
    
    $('.product-view .next-product-holder').on('mouseleave', function(){
        $(this).find('.image-holder').stop(true, false).animate({opacity:0});
        $(this).find('.description-holder').stop(true, false).animate({opacity:0});
    });

});

jQuery(window).resize(function(){
    //full size image resize
    FullSizeImage.resize();
    //jZoom resize
    //if(FullSizeImage.fullSize)
    //{
        jQuery('.mousetrap').width(FullSizeImage.container.width()).height(FullSizeImage.container.height());
    //}
    //tabs resize
    jQuery('.product-collateral').height(jQuery('.tab-container').height() + 60);
});

jQuery(window).load(function(){
    jQuery('.product-collateral').height(jQuery('.tab-container').height() + 60);
});

// Mobile

jQuery(window).load(function(){
    jQuery('.info-btn').on('click', function(){
        if(!jQuery('.info-holder').hasClass('active')){
            jQuery('.info-holder').addClass('active');
            jQuery('.info-btn').addClass('active');
        }   
        else{
            jQuery('.info-holder').removeClass('active');
            jQuery('.info-btn').removeClass('active');
        }
    });
});