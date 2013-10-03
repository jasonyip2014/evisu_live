function SimulateChange(id){
//    console.log(id);
	$(id).simulate('change');
}
(function($){
    $.fn.customSelectRefresh = function(){
        var self = this;
        var customValue = changeText($(self).find('option:selected').text());
        var customSelect = $(self).parent().find('.custom-select');
        customSelect.empty();
        customSelect.prepend('<a href="#" class="custom-select-btn">'+customValue+'</a>');
        customSelect.append('<ul class="custom-select-drop"></ul>');
        customSelect.find('.custom-select-drop').css('display', 'none');
        var customDrop = customSelect.find('.custom-select-drop:first'),
            customBtn = customDrop.prev();

        var customDropElements = '';
        var $opts = $(self).find('option');
        for (var i=0; i < $opts.length; i++) {
            var optionValue = $($opts[i]).attr('value');
//            var optionText = $($opts[i]).text();
            var optionText = changeText($($opts[i]).text());

            customDropElements += '<li><a href="#" value="' + optionValue +'">' + optionText + '</a></li>';
        }
        $(customDrop).append(customDropElements);
        var $newOpt = customDrop.find('li a');
        customBtn.click(function(e){
            if ($(this).closest(customSelect).hasClass('active') === false){
                $('.custom-select-drop').slideUp(100).closest('.custom-select').removeClass('active');
                customDrop.slideDown(100);
                $(this).closest(customSelect).addClass('active');
            }
            else{
                customDrop.slideUp(100);
                $(this).closest(customSelect).removeClass('active');
            }
            e.preventDefault();
        });
        customDrop.find('li a:contains("'+customValue+'")').addClass('selected');
        $newOpt.click(function(e){
            $(this).closest(customDrop).slideUp(100).closest(customSelect).removeClass('active');
            customBtn.text($(this).text());
            customDrop.find('li a.selected').removeClass('selected');
            $(this).addClass('selected');

            $(self).find('option:selected').removeAttr('selected');
            $(self).find('option:contains("'+$(this).text()+'")').attr('selected', 'selected');

            //$(self).removeAttr('selected');
            $(self).val($(this).attr('value'));
            SimulateChange($(self).attr('id'));

            e.preventDefault();
        });
        $(self).each(function(){
            $.each(this.attributes, function(i, attribute){
                var name = attribute.name;
                var value = attribute.value;
                if (name != 'class' && name != 'style'){
                    if (name !== 'id' && name !== 'name') {
                        customBtn.attr(name, value);
                    }

                }
            });
        });
        $(self).find('option').each(function(){
            $.each(this.attributes, function(i, attribute){
                var name = attribute.name;
                var value = attribute.value;
                if (name != 'class' && name != 'style'){
                    $(this).attr(name, value);
                }
            });
        });

        function changeText(textValue, removeIt)
        {
            var replacedValues = ['Choose an Option...', 'Please select region, state or province'];
            //jQuery.inArray
            if (jQuery.inArray(textValue, replacedValues) != -1) {
                if (removeIt) {
                    return false;
                }
                if($(self).data('defvalue'))
                {
                    textValue = $(self).data('defvalue');
                }
                else
                {
                    textValue = 'Select';
                }
            }
            return textValue;
        }

        $(document).click(function(){
            $('.custom-select.active').removeClass('active');
            $('.custom-select-drop').slideUp(100);
        });
    };
    $.fn.customSelect = function(){
        $(this).each(function(){
            var self = this;
            var customWr = $(self).wrap('<div class="custom-select-wrapper"></div>').parent();
            customWr.append('<div class="custom-select"></div>');
            var customSelect = customWr.find('.custom-select:first');
    //            customValue = $(self).find('option:selected').text();

            $('.custom-select').click(function(e){
                e.stopPropagation();
            });

            $(self).customSelectRefresh();
        });


        return this;
    };
}(jQuery));

function changeState(element)
{
    document.getElementById(element).triggerEvent('change');
}

Element.prototype.triggerEvent = function(eventName)
{
    if (document.createEvent)
    {
        var evt = document.createEvent('HTMLEvents');
        evt.initEvent(eventName, true, true);

        return this.dispatchEvent(evt);
    }

    if (this.fireEvent)
        return this.fireEvent('on' + eventName);
};
