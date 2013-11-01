jQuery(function($){
    //loader
    jQuery('.col-main').css({'opacity':0});
    jQuery('.site-loader').show();


    // =============== quick navigation buttons behavior ================
    jQuery('.explore-more-btn').on('click', function(){
        jQuery('html,body').stop(true, false).animate({scrollTop : '+=' + jQuery(window).height()},'slow');
    });
    jQuery('.back-btn').on('click', function(){
        jQuery('html,body').stop(true, false).animate({scrollTop : '-=' + jQuery(window).height()},'slow');
    });

    $(window).load(function(){
        $('.explore-more-btn').fadeIn('slow');
    });

    // ================= product grid image ========================
    $('.products-grid .images-holder').on('mouseenter', function(){
        var container = $(this);
        container.find('.img-2').css('opacity', 0);
        container.find('.img-1').stop(true,false);
        container.find('.img-1').animate({opacity: 0},'fast');
        container.find('.img-2').animate({opacity: 0.4},'fast');
        container.parent().next().css('zIndex', 4);
    });

    $('.products-grid .hover-container').on('mouseleave', function(){
        var container = $(this).parent().parent();
        container.prev().find('.img-2').stop(true,false);
        container.prev().find('.img-2').animate({opacity: 0},'fast');
        container.prev().find('.img-1').animate({opacity: 1},'fast');
        container.css('zIndex', 2);
    });
    // =================================================================

    $(window).scroll(function(){
        if(window.pageYOffset > 100){
            $('.explore-more-btn').addClass('little');
            $('.back-btn').fadeIn(100);
        }
        else{
            $('.explore-more-btn').removeClass('little');
            $('.back-btn').fadeOut(100);
        }
        if(window.pageYOffset == jQuery(document).height() - jQuery(window).height())
        {
            jQuery('.explore-more-btn').hide();
        }
        else
        {
            jQuery('.explore-more-btn').show();
        }
    });

});

jQuery(window).load(function(){
    //loader
    jQuery('.col-main').stop(true, true).animate({opacity: 1}, 'fast');
    jQuery('.site-loader').fadeOut('fast');
});
