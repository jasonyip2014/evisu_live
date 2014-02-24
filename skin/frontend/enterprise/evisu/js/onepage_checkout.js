/**
 * Created with JetBrains PhpStorm.
 * User: nikolayk
 * Date: 2/19/14
 * Time: 5:09 PM
 * To change this template use File | Settings | File Templates.
 */
jQuery(function($){
    //scroll page for the next step
    jQuery('.back-link').on('click', function(){
        jQuery('html').animate({scrollTop:191});
    });
    jQuery('.button').on('click', function(){
        jQuery('html').animate({scrollTop:191});
    });
});