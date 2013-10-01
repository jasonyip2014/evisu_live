var FaceBook =
{
    login : function(url)
    {
        var width = '1000';
        var height = '500';
        var top = (jQuery(window).height() - height) / 2;
        var left = (jQuery(window).width() - width) / 2;
        var params = '\
            width=' + width + ',\n\
            height=' + height + ',\n\
            left=' + left + ',\n\
            top=' + top + ',\n\
            toolbar=0,\n\
            menubar=0,\n\
            location=0,\n\
            status=0,\n\
            scrollbars=0,\n\
            resizable=0\n\
        ';
        window.open(url, 'login', params);
        return false;
    }
};