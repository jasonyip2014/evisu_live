var HomePageAnimation =
{
    self : null,
    defaultWindowWidth : 1920,
    btnBack : null,
    btnMore : null,
    scrollTop : 0,
    scrollTopOld : 0,
    direct : 'down',

    init : function()
    {
        self = this;
        self.header.init();
        self.section1.init();
        self.section2.init();
        self.section3.init();
        self.header.btnBack = jQuery('.quick-navigation .back-btn');
        self.header. btnMore = jQuery('.quick-navigation .explore-more-btn');
        //self.header. btnMore.css('display', 'none'); //to CSS
        //self.header. btnBack.css('display', 'none'); //to CSS
        //self.section2.section.css('display', 'none'); //
        //self.section3.section.css('display', 'none'); //
        HomePageAnimation.windowResize();
    },

    header :
    {
        img : null,
        text : null,
        section : null,
        height : 0,

        init : function()
        {
            self.header.section = jQuery('.main-section-holder');
            self.header.img = self.header.section.find('img').parent();
            self.header.text = self.header.section.find('p');
            self.header.height =  self.header.section.height();
            //self.header.img.css('display', 'none');// to CSS
            //self.header.text.css('display', 'none'); //to CSS
            //console.log('header init');
        },
        animate : function()
        {
            //self.header.img.css('position', 'fixed'); //to css
           /* if(self.scrollTop == 0)
            {
                self.header.img.fadeIn('slow', function(){
                    self.header.text.fadeIn('slow', function()
                    {
                        self.header. btnMore.fadeIn('slow', function(){
                            self.header. btnMore.fadeOut('slow', function(){
                                self.header. btnMore.fadeIn('slow');
                            });
                        });
                    });
                });
            }
            if(self.scrollTop >= 1 && self.scrollTop <3)
            {
                var offset = self.header.img.offset();
                self.header.img.css('left', offset.left);
                self.header.img.css('top', offset.top);
                self.header.img.css('position', 'fixed');
            }
            if(self.scrollTop >= 300 && self.scrollTop <=303)
            {
                var offset = self.header.img.offset();
                self.header.img.css('left', offset.left);
                self.header.img.css('top', offset.top);
                self.header.img.css('position', 'absolute');
            }*/
        }
    },
    section1 :
    {
        section : null,
        height : 0,
        block1 : {
            obj : null,
            trigger :
            {
                up : [],
                down : [],
                look : function(animation)
                {
                    self.section1.block1.trigger.up[animation] = !!(self.direct == 'up');
                    self.section1.block1.trigger.down[animation] = !!(self.direct == 'down');
                },
                unlook : function(animation)
                {
                    self.section1.block1.trigger.up[animation] = !!(self.direct != 'up');
                    self.section1.block1.trigger.down[animation] = !!(self.direct != 'down');
                }
            },
            prop : {}
        },
        block2 : null,
        block3 : null,
        block4 : null,
        block5 : null,
        block6 : null,
        block7 : null,
        block8 : null,
        block9 : null,
        init : function()
        {
            self.section1.section = jQuery('.section-holder-1');
            self.section1.block1.obj = self.section1.section.find('.section').find('.block-1');
            self.section1.block2 = self.section1.section.find('.section').find('.block-2');
            self.section1.block3 = self.section1.section.find('.section').find('.block-3');
            self.section1.block4 = self.section1.section.find('.section').find('.block-4');
            self.section1.block5 = self.section1.section.find('.section').find('.block-5');
            self.section1.block6 = self.section1.section.find('.section').find('.block-6');
            self.section1.block7 = self.section1.section.find('.section').find('.block-7');
            self.section1.block8 = self.section1.section.find('.section').find('.block-8');
            self.section1.block9 = self.section1.section.find('.section').find('.block-9');
            self.section1.height = self.section1.section.height();

            console.log('section1 init');
        },
        animate : function()
        {
            console.log('section1 animate');
            //Down
            if(self.scrollTop > 1080 && self.scrollTop <= 2080)
            {
                self.animateLib.scrollPause(self.section1.block1, 'down');
            }
            if(self.scrollTop > 2080)
            {
                self.animateLib.scrollContinue(self.section1.block1, 'down');
            }

            //Up
//            if(self.scrollTop < 2081)
//            {
//                self.animateLib.scrollPause(self.section1.block1, 'up');
//            }
//            if(self.scrollTop < 108)
//            {
//                self.animateLib.scrollContinue(self.section1.block1, 'up');
//            }

        }
    },
    section2 :
    {
        section : null,
        height : 0,
        init : function()
        {
            self.section2.section = jQuery('.section-holder-2');
            self.section2.height =  self.section2.section.height();
        }
    },
    section3 :
    {
        section :null,
        height : 0,
        init : function()
        {
            self.section3.section = jQuery('.section-holder-3');
            self.section3.height =  self.section3.section.height();
        }
    },

    animate : function()
    {
        self.header.animate();
        self.section1.animate();
    },

    animateLib :
    {
        scrollPause : function(element, direct)
        {
            if(!element.trigger[direct]['scrollPause'] && self.direct == direct)
            {
                element.trigger.look('scrollPause');
                var offset = element.obj.offset();
                element.obj.css('left', offset.left);
                element.obj.css('top', offset.top - self.scrollTop);
                element.obj.css('position', 'fixed');

            }
        },
        scrollContinue : function(element, direct)
        {
            if(!element.trigger[direct]['scrollContinue'] && self.direct == direct)
            {
                element.trigger.look('scrollContinue');
//                console.log(element.trigger[direct]['scroll']);
                var offset = element.obj.offset();
                //element.obj.css('left', offset.left);
//                console.log(element.obj.position().top);
//                console.log(element.obj.offset().top);
//                console.log(self.section1.section.position().top);
//                console.log(self.section1.section.offset().top);
//                console.log(self.section1.section.parent().position().top);
//                console.log(self.section1.section.parent().offset().top);
                //console.log(element.obj.position().top);

                element.obj.css('left',  element.obj.position().left - element.obj.parent().position().left);

                element.obj.css('top', offset.top - self.section1.section.offset().top + element.obj.position().top);
                element.obj.css('position', 'absolute');
                //element.obj.animate({top :'+=100'});
                console.log(element.obj.offset().top);
            }
        }
    },

    scrollPage : function()
    {
        self.scrollTopOld = self.scrollTop;
        self.scrollTop = jQuery(window).scrollTop();
        if(self.scrollTop > self.scrollTopOld)
        {
            self.direct = 'down';
        }
        else
        {
            self.direct = 'up';
        }
        jQuery('#d').html(self.scrollTop + ' (' + self.direct + ')');
        if(self.scrollTop > 0)
        {
            self.header.btnMore.fadeOut('fast');
            self.header.btnBack.fadeIn('slow');
            self.header.text.fadeOut('slow');
        }
        else
        {
            self.header.text.fadeIn('slow');
            self.header.btnBack.fadeOut('fast');
            self.header.btnMore.fadeIn('slow');
        }
        //self.animate();
    },
    windowResize: function()
    {
        jQuery(window).scrollTop(0);
        //section height resize
        var windowWidth = jQuery(window).width();
        self.section1.section.height((self.header.height * windowWidth) / self.defaultWindowWidth);
        self.section1.section.height((self.section1.height * windowWidth) / self.defaultWindowWidth);
        self.section2.section.height((self.section2.height * windowWidth) / self.defaultWindowWidth);
        self.section3.section.height((self.section3.height * windowWidth) / self.defaultWindowWidth);
    }
};

jQuery(window).scroll(function($)
{
    HomePageAnimation.scrollPage();
});

jQuery(function($){
    HomePageAnimation.init();
    console.log('init');




    // === Disabling Scroll
    /*var keys = [37, 38, 39, 40];

    function preventDefault(e) {
      e = e || window.event;
      if (e.preventDefault)
          e.preventDefault();
      e.returnValue = false;
    }

    function keydown(e) {
        for (var i = keys.length; i--;) {
            if (e.keyCode === keys[i]) {
                preventDefault(e);
                return;
            }
        }
    }

    function wheel(e) {
      preventDefault(e);
    }

    function disable_scroll() {
      if (window.addEventListener) {
          window.addEventListener('DOMMouseScroll', wheel, false);
      }
      window.onmousewheel = document.onmousewheel = wheel;
      document.onkeydown = keydown;
    }

    function enable_scroll() {
        if (window.removeEventListener) {
            window.removeEventListener('DOMMouseScroll', wheel, false);
        }
        window.onmousewheel = document.onmousewheel = document.onkeydown = null;
    }*/


    //$('html,body').animate({scrollTop:0},10);
    /*$(window).on('mousewheel',function(){
        var scrollTop = $(window).scrollTop(),
            section = $('.section');
        if (scrollable == false){
            disable_scroll();
        }
        else
            enable_scroll();
    });*/

    $(window).load(function(){
//        $(window).resize();
        jQuery(window).scrollTop(0);
        //HomePageAnimation.animate();
        //console.log('animate');
    });

    $(window).resize(function(){
       HomePageAnimation.windowResize();
    });
});


