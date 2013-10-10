jQuery(function($){
    // =============== quick navigation buttons behavior ================
    jQuery('.explore-more-btn').on('click', function(){
        jQuery('html,body').stop(true, false).animate({scrollTop : '+=' + jQuery(window).height()},'slow');
    });
    jQuery('.back-btn').on('click', function(){
        jQuery('html,body').stop(true, false).animate({scrollTop : '-=' + jQuery(window).height()},'slow');
    });
});

jQuery(window).load(function(){
    // =============== quick navigation buttons behavior ================
    if(jQuery(document).height() > jQuery(window).height())
    {
        jQuery('.explore-more-btn').fadeIn('slow');
    }
});

jQuery(window).scroll(function(){
    // =============== quick navigation buttons behavior ================
    if(window.pageYOffset > 100){
        jQuery('.explore-more-btn').addClass('little');
        jQuery('.back-btn').fadeIn(100);
    }
    else{
        jQuery('.explore-more-btn').removeClass('little');
        jQuery('.back-btn').fadeOut(100);
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