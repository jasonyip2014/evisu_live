jQuery(function($){
	
	//Load Page
	
	$('.main-section .image-holder').css('position','fixed');
	$('.image-holder img').css('display','none').delay( 1000 ).fadeIn( 800 );
	$('.main-section > p > span.first').css('display','none').delay( 2000 ).fadeIn( 800 );
	$('.main-section > p > span.second').css('display','none').delay( 3000 ).fadeIn( 800 );
	$('.quick-navigation .explore-more-btn').css('display','none').delay( 4000 ).fadeIn( 600 ).fadeOut( 200 ).fadeIn( 800 );
	//$('.section-holder-1 > div').css('display','none');
	$('.section-holder-1 > div.home_page_section1_text_block').show('fast');
	
	$(".home_page_section1_text_block").sticky({ topSpacing: 0});
	$(".home_page_section3_text_block").sticky({ topSpacing: 0});
	$('.section-holder-3 > div.block-2').sticky({ topSpacing:400, wrapperClassName:'newsBlock', className:'newsStick'});
	
	
	$('.quick-navigation .back-btn').on('click',function(){
		$('html,body').stop().animate({scrollTop : 0},1500);
	});
	
	$('.quick-navigation .explore-more-btn').on('click',function(){
			var	$topPosition = $(window).scrollTop();
			if($(window).width()>1600){
				if($topPosition<2080){
					$('html,body').stop().animate({scrollTop : 2080},1500);
				}else if($topPosition<2550){
					$('html,body').stop().animate({scrollTop : 2550},1500);
				}else if($topPosition<3295){
					$('html,body').stop().animate({scrollTop : 3295},1500);
				}else if($topPosition<3670){
					$('html,body').stop().animate({scrollTop : 3670},1500);
				}else if($topPosition<4680){
					$('html,body').stop().animate({scrollTop : 4680},1500);
				}else if($topPosition<5220){
					$('html,body').stop().animate({scrollTop : 5220},1500);
				}
			}else{
				if($topPosition<1920){
					$('html,body').stop().animate({scrollTop : 1920},1500);
				}else if($topPosition<2132){
					$('html,body').stop().animate({scrollTop : 2132},1500);
				}else if($topPosition<2770){
					$('html,body').stop().animate({scrollTop : 2770},1500);
				}else if($topPosition<3090){
					$('html,body').stop().animate({scrollTop : 3090},1500);
				}else if($topPosition<4265){
					$('html,body').stop().animate({scrollTop : 4265},1500);
				}else if($topPosition<4788){
					$('html,body').stop().animate({scrollTop : 4788},1500);
				}
			}
		});
	
	$(window).scroll(function(){
		var	$topPosition = $(window).scrollTop();
		
		//Back Button Enable/Disable
		if($topPosition>0){
			$('.quick-navigation .back-btn').fadeIn( 800 );
		}else if($topPosition==0){
			$('.quick-navigation .back-btn').fadeOut( 800 );
		}
		
		//Section One Animation
		var $positionSection1 = $('.section-holder-1').position().top - $topPosition,
			$positionSection2 = $('.section-holder-2').position().top - $topPosition,
			$positionSection3 = $('.section-holder-3').position().top - $topPosition,
			$windowHeight = $(window).height(),
			$section1Text = $(".home_page_section1_text_block"),
			$section3Text = $(".home_page_section3_text_block"),
			$section1TextLeft = $section1Text.css('left'),
			$animate1 = $positionSection1*(-2),
			$animate2 = $positionSection2*(1),
			$animate3 = $positionSection3*(4);

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
				
				var $topSection2Block1 = $animate2/11;
				$('.section-holder-2 > div.block-1').stop().css({ "top": $topSection2Block1 });
				if($topSection2Block1<=35){
					$('.section-holder-2 > div.block-1').fadeTo( 300, 0 );
				}else{
					$('.section-holder-2 > div.block-1').fadeTo( 300, 1 );
				}
				
				var $topSection2Block2 = ($animate2/3)-100;
				$('.section-holder-2 > div.block-2').stop().css({ "top": $topSection2Block2 });
				if($topSection2Block2<=17){
					$('.section-holder-2 > div.block-2').fadeTo( 300, 0 );
				}else{
					$('.section-holder-2 > div.block-2').fadeTo( 300, 1 );
				}
				
				var $topSection2Block3 = ($animate2)+($('.section-holder-2 > div.block-1').height()/2);
				$('.section-holder-2 > div.block-3').stop().css({ "top": $topSection2Block3 });
				if($topSection2Block3<=160){
					$('.section-holder-2 > div.block-3').fadeTo( 300, 0 );
				}else{
					$('.section-holder-2 > div.block-3').fadeTo( 300, 1 );
				}
				
				var $topSection2Block4 = ($animate2/6)+($('.section-holder-2 > div.block-1').height()-100);
				$('.section-holder-2 > div.block-4').stop().css({ "top": $topSection2Block4 });
				if($topSection2Block4<=320){
					$('.section-holder-2 > div.block-4').fadeTo( "slow", 0 );
				}else{
					$('.section-holder-2 > div.block-4').fadeTo( "slow", 1 );
				}
				
				var $topSection2Block5 = ($animate2/2)+$('.section-holder-2 > div.block-1').height()+$('.section-holder-2 > div.block-2').height()+400;
				$('.section-holder-2 > div.block-5').stop().css({ "top": $topSection2Block5 });
				if($topSection2Block5<=979){
					$('.section-holder-2 > div.block-5').fadeTo( "slow", 0 );
				}else{
					$('.section-holder-2 > div.block-5').fadeTo( "slow", 1 );
				}
				
				var $topSection2Block6 = ($animate2)+$('.section-holder-2 > div.block-1').height()+$('.section-holder-2 > div.block-2').height()+$('.section-holder-2 > div.block-3').height()-200;
				$('.section-holder-2 > div.block-6').stop().css({ "top": $topSection2Block6 });
				if($topSection2Block6<=868){
					$('.section-holder-2 > div.block-6').fadeTo( "slow", 0 );
				}else{
					$('.section-holder-2 > div.block-6').fadeTo( "slow", 1 );
				}
				
				if($positionSection2<=620){
					$('.section-holder-1 .block-1 .section-description, .section-holder-1 .block-1 .section-main-link').stop().fadeTo( 300, 0 );
				}else{
					$('.section-holder-1 .block-1 .section-description, .section-holder-1 .block-1 .section-main-link').stop().fadeTo( 300, 1 );
				}
				
				var $bottomPosition3 = $positionSection3-400-$windowHeight;

				if($bottomPosition3<=0){
					$section1Text.css('margin-top',$bottomPosition3);
				}else{
					$section1Text.css('margin-top','0');
				}
				
				$('.section-holder-3 > div.block-3').stop().css({ "top": ($animate3/2.5)-1500 });
				$('.section-holder-3 > div.block-4').stop().css({ "top": ($animate3/1.5)-2800 });
				$('.section-holder-3 > div.block-5').stop().css({ "top": ($animate3/30)+400 });
				$('.section-holder-3 > div.block-6').stop().css({ "top": ($animate3/10)+300 });
				$('.section-holder-3 > div.block-7').stop().css({ "top": ($animate3/40)+350 });
				
			}else{
				var $animate1 = $positionSection1*(-4),
					$animate2 = $positionSection2/3,
					$animate3 = $positionSection3*(4);
					
				$('.section-holder-1 > div.home_page_section1_image_panel1').stop().css({ "bottom": $animate1/1.4 });
				$('.section-holder-1 > div.home_page_section1_image_panel2').stop().css({ "bottom": $animate1/1.55 });
				$('.section-holder-1 > div.home_page_section1_image_panel3').stop().css({ "bottom": $animate1/1.6 });
				$('.section-holder-1 > div.home_page_section1_image_panel4').stop().css({ "bottom": $animate1/1.8 });
				$('.section-holder-1 > div.home_page_section1_image_panel5').stop().css({ "bottom": $animate1/2.9 });
				$('.section-holder-1 > div.home_page_section1_image_panel6').stop().css({ "bottom": $animate1/2.8 });
				$('.section-holder-1 > div.home_page_section1_image_panel7').stop().css({ "bottom": $animate1/2 });
				$('.section-holder-1 > div.home_page_section1_image_panel8').stop().css({ "bottom": $animate1/2.5 });
				
				var $topSection2Block1 = $animate2/11;
				$('.section-holder-2 > div.block-1').stop().css({ "top": $topSection2Block1 });
				if($topSection2Block1<=30){
					$('.section-holder-2 > div.block-1').fadeTo( "slow", 0 );
				}else{
					$('.section-holder-2 > div.block-1').fadeTo( "slow", 1 );
				}
				
				var $topSection2Block2 = ($animate2/3)-100;
				$('.section-holder-2 > div.block-2').stop().css({ "top": $topSection2Block2 });
				if($topSection2Block2<=17){
					$('.section-holder-2 > div.block-2').fadeTo( "slow", 0 );
				}else{
					$('.section-holder-2 > div.block-2').fadeTo( "slow", 1 );
				}
				
				var $topSection2Block3 = ($animate2)+($('.section-holder-2 > div.block-1').height()/2);
				$('.section-holder-2 > div.block-3').stop().css({ "top": $topSection2Block3 });
				if($topSection2Block3<=350){
					$('.section-holder-2 > div.block-3').fadeTo( "slow", 0 );
				}else{
					$('.section-holder-2 > div.block-3').fadeTo( "slow", 1 );
				}
				
				var $topSection2Block4 = ($animate2/6)+($('.section-holder-2 > div.block-1').height()-100);
				$('.section-holder-2 > div.block-4').stop().css({ "top": $topSection2Block4 });
				if($topSection2Block4<=320){
					$('.section-holder-2 > div.block-4').fadeTo( "slow", 0 );
				}else{
					$('.section-holder-2 > div.block-4').fadeTo( "slow", 1 );
				}
				
				var $topSection2Block5 = ($animate2/2)+$('.section-holder-2 > div.block-1').height()+$('.section-holder-2 > div.block-2').height()+400;
				$('.section-holder-2 > div.block-5').stop().css({ "top": $topSection2Block5 });
				if($topSection2Block5<=979){
					$('.section-holder-2 > div.block-5').fadeTo( "slow", 0 );
				}else{
					$('.section-holder-2 > div.block-5').fadeTo( "slow", 1 );
				}
				
				var $topSection2Block6 = ($animate2)+$('.section-holder-2 > div.block-1').height()+$('.section-holder-2 > div.block-2').height()+$('.section-holder-2 > div.block-3').height()-200;
				$('.section-holder-2 > div.block-6').stop().css({ "top": $topSection2Block6 });
				if($topSection2Block6<=868){
					$('.section-holder-2 > div.block-6').fadeTo( "slow", 0 );
				}else{
					$('.section-holder-2 > div.block-6').fadeTo( "slow", 1 );
				}
				
				if($positionSection2<=1400){
					$('.section-holder-1 .block-1 .section-description, .section-holder-1 .block-1 .section-main-link').stop().fadeTo( 300, 0 );
				}else{
					$('.section-holder-1 .block-1 .section-description, .section-holder-1 .block-1 .section-main-link').stop().fadeTo( 300, 1 );
				}
				
				var $bottomPosition3 = $positionSection3-400-$windowHeight;
				if($bottomPosition3<=0){
					$section1Text.css('margin-top',$bottomPosition3);
				}else{
					$section1Text.css('margin-top','0');
				}
				$('.section-holder-3 > div.block-3').stop().css({ "top": ($animate3/2.5)-1100 });
				$('.section-holder-3 > div.block-4').stop().css({ "top": ($animate3/1.5)-2100 });
				$('.section-holder-3 > div.block-5').stop().css({ "top": ($animate3/30)+500 });
				$('.section-holder-3 > div.block-6').stop().css({ "top": ($animate3/10)+500 });
				$('.section-holder-3 > div.block-7').stop().css({ "top": ($animate3/13)+500 });
			}
			
			
		}else{
			$('.main-section .image-holder').css('margin-top','0');
		}
	});
	
	$(window).resize(function(){
		if($(window).width()<1600){
			$('.section-holder-2').css('margin-top','-1100px');
			$('.section-holder-3').css('margin-top','-800px');
		}else{
			$('.section-holder-2').css('margin-top','-500px');
			$('.section-holder-3').css('margin-top','-800px');
		}
		$(".home_page_section1_text_block").sticky('update');
		$(".home_page_section3_text_block").sticky('update');
		$('.section-holder-3 > div.block-2').sticky('update');
	});
	$(window).resize();
	$('html,body').scrollTop(0);
});
