jQuery(function($){
	
	//Load Page
	
	$('.main-section .image-holder').css('position','fixed');
	$('.image-holder img').css('display','none').delay( 1000 ).fadeIn( 800 );
	$('.main-section > p').css('display','none').delay( 2000 ).fadeIn( 800 );
	$('.quick-navigation .explore-more-btn').css('display','none').delay( 3000 ).fadeIn( 600 ).fadeOut( 200 ).fadeIn( 800 );
	//$('.section-holder-1 > div').css('display','none');
	$('.section-holder-1 > div.home_page_section1_text_block').show('fast');
		
	$(window).scroll(function(){
		var	$topPosition = $(window).scrollTop();
		
		//Back Button Enable/Disable
		if($topPosition>0){
			$('.quick-navigation .explore-more-btn').fadeOut( 800 );
			$('.quick-navigation .back-btn').fadeIn( 800 );
		}else if($topPosition==0){
			$('.quick-navigation .explore-more-btn').fadeIn( 800 );
			$('.quick-navigation .back-btn').fadeOut( 800 );
		}
		
		//Section One Animation
		var $positionSection1 = $('.section-holder-1').position().top - $topPosition,
			$positionSection2 = $('.section-holder-2').position().top - $topPosition,
			$section1Text = $(".home_page_section1_text_block"),
			$section1TextLeft = $section1Text.css('left'),
			$animate1 = $positionSection1*(-2),
			$animate2 = $positionSection2*(1);
			
		$section1Text.sticky({ topSpacing: 0});
		if($section1Text.hasClass("is-sticky")){
			$('.main-section .image-holder').css('margin-top',$positionSection1);
			if($(window).width()>1600){
				$('.section-holder-1 > div.home_page_section1_image_panel1').stop().css({ "bottom": $animate1/1.2 });
				$('.section-holder-1 > div.home_page_section1_image_panel2').stop().css({ "bottom": $animate1/1.5 });
				$('.section-holder-1 > div.home_page_section1_image_panel3').stop().css({ "bottom": $animate1/1.4 });
				$('.section-holder-1 > div.home_page_section1_image_panel4').stop().css({ "bottom": $animate1/1.7 });
				$('.section-holder-1 > div.home_page_section1_image_panel5').stop().css({ "bottom": $animate1/5.7 });
				$('.section-holder-1 > div.home_page_section1_image_panel6').stop().css({ "bottom": $animate1/5.5 });
				$('.section-holder-1 > div.home_page_section1_image_panel7').stop().css({ "bottom": $animate1/2 });
				$('.section-holder-1 > div.home_page_section1_image_panel8').stop().css({ "bottom": $animate1/3 });
				
				$('.section-holder-2 > div.block-1').stop().css({ "top": $animate2/11 });
				$('.section-holder-2 > div.block-2').stop().css({ "top": ($animate2/3)-100 });
				$('.section-holder-2 > div.block-3').stop().css({ "top": ($animate2)+($('.section-holder-2 > div.block-1').height()/2) });
				$('.section-holder-2 > div.block-4').stop().css({ "top": ($animate2/6)+($('.section-holder-2 > div.block-1').height()-100) });
				$('.section-holder-2 > div.block-5').stop().css({ "top": ($animate2/2)+$('.section-holder-2 > div.block-1').height()+$('.section-holder-2 > div.block-2').height()+400 });
				$('.section-holder-2 > div.block-6').stop().css({ "top": ($animate2)+$('.section-holder-2 > div.block-1').height()+$('.section-holder-2 > div.block-2').height()+$('.section-holder-2 > div.block-3').height()-200 });
			}else{
				var $animate1 = $positionSection1*(-4),
					$animate2 = $positionSection2/3;
				$('.section-holder-1 > div.home_page_section1_image_panel1').stop().css({ "bottom": $animate1/1.4 });
				$('.section-holder-1 > div.home_page_section1_image_panel2').stop().css({ "bottom": $animate1/1.55 });
				$('.section-holder-1 > div.home_page_section1_image_panel3').stop().css({ "bottom": $animate1/1.6 });
				$('.section-holder-1 > div.home_page_section1_image_panel4').stop().css({ "bottom": $animate1/1.8 });
				$('.section-holder-1 > div.home_page_section1_image_panel5').stop().css({ "bottom": $animate1/2.9 });
				$('.section-holder-1 > div.home_page_section1_image_panel6').stop().css({ "bottom": $animate1/2.8 });
				$('.section-holder-1 > div.home_page_section1_image_panel7').stop().css({ "bottom": $animate1/2 });
				$('.section-holder-1 > div.home_page_section1_image_panel8').stop().css({ "bottom": $animate1/2.5 });
				
				$('.section-holder-2 > div.block-1').stop().css({ "top": $animate2/11 });
				$('.section-holder-2 > div.block-2').stop().css({ "top": ($animate2/3)-100 });
				$('.section-holder-2 > div.block-3').stop().css({ "top": ($animate2)+($('.section-holder-2 > div.block-1').height()/2) });
				$('.section-holder-2 > div.block-4').stop().css({ "top": ($animate2/6)+($('.section-holder-2 > div.block-1').height()-100) });
				$('.section-holder-2 > div.block-5').stop().css({ "top": ($animate2/2)+$('.section-holder-2 > div.block-1').height()+$('.section-holder-2 > div.block-2').height()+400 });
				$('.section-holder-2 > div.block-6').stop().css({ "top": ($animate2)+$('.section-holder-2 > div.block-1').height()+$('.section-holder-2 > div.block-2').height()+$('.section-holder-2 > div.block-3').height()-200 });
			}
			
			
		}else{
			$('.main-section .image-holder').css('margin-top','0');
		}
	});
	
	$(window).resize(function(){
		if($(window).width()<1600){
			$('.section-holder-2').css('margin-top','-1100px');
		}else{
			$('.section-holder-2').css('margin-top','-500px');
		}
	});
	$(window).resize();
	$('html,body').scrollTop(0);
});
