jQuery(function($){
	
	//Load Page
	
	
	
	$('.main-section .image-holder').css('position','fixed');
	$('.main-section .image-holder').css('display','none').delay( 1000 ).fadeIn( 800 );
	$('.main-section > p > span.first').css('display','none').delay( 2000 ).fadeIn( 800 );
	$('.main-section > p > span.second').css('display','none').delay( 3000 ).fadeIn( 800 );
	$('.quick-navigation .explore-more-btn').css('display','none').delay( 4000 ).fadeIn( 600 ).fadeOut( 200 ).fadeIn( 800 );
	//$('.section-holder-1 > div').css('display','none');
	$('.section-holder-1 > div.home_page_section1_text_block').show('fast');
	
	$(".home_page_section1_text_block").sticky({ topSpacing: 0});
	$(".home_page_section3_text_block").sticky({ topSpacing: 0});
	$('.section-holder-3 > div.block-2').sticky({ topSpacing:400, wrapperClassName:'newsBlock', className:'newsStick'});
	$('.section-holder-2 > div.block-1 .category-link').sticky({ topSpacing:450, wrapperClassName:'categorylink1Block', className:'categorylink1Stick'});
	$('.section-holder-2 > div.block-2 .category-link').sticky({ topSpacing:450, wrapperClassName:'categorylink2Block', className:'categorylink2Stick'});
	
	var iframe = $('#fvideo')[0],
		player = $f(iframe);
	player.addEvent('ready', function() {
		player.addEvent('finish', function() {
			$('.home_page_section1_image_panel4 .video-wrapper iframe').fadeOut(200);
			$('.home_page_section1_image_panel4 .video-overlay-holder').fadeIn(200);
			$('.home_page_section1_image_panel4 .image-holder').fadeIn(200);
		});
	});
	$(".video-overlay-holder").on('click',function(){
		var	$this = $(this),
			$video = $this.parent().find('div.video-wrapper').find('iframe');
		$this.fadeOut(800);
		$this.parent().find('div.image-holder').fadeOut(800);
		player.api("play");
	});
	
	$('.quick-navigation .back-btn').on('click',function(){
		//$('html,body').stop().animate({scrollTop : 0},1500);
	});
	$('.quick-navigation .explore-more-btn').on('click',function(){
			var	$topPosition = $(window).scrollTop();
			if($(window).width()>1600){
				if($topPosition<2080){
					$('html,body').stop().animate({scrollTop : 2080},6000);
				}else if($topPosition<2550){
					$('html,body').stop().animate({scrollTop : 2550},2000);
				}else if($topPosition<3240){
					$('html,body').stop().animate({scrollTop : 3240},2000);
				}else if($topPosition<3985){
					$('html,body').stop().animate({scrollTop : 3985},2000);
				}else if($topPosition<4680){
					$('html,body').stop().animate({scrollTop : 4680},2000);
				}else if($topPosition<5480){
					$('html,body').stop().animate({scrollTop : 5480},3000);
				}else if($topPosition<6015){
					$('html,body').stop().animate({scrollTop : 6015},2000);
				}
			}else{
				if($topPosition<1920){
					$('html,body').stop().animate({scrollTop : 1920},6000);
				}else if($topPosition<2132){
					$('html,body').stop().animate({scrollTop : 2132},2000);
				}else if($topPosition<2255){
					$('html,body').stop().animate({scrollTop : 2555},2000);
				}else if($topPosition<3140){
					$('html,body').stop().animate({scrollTop : 3140},2000);
				}else if($topPosition<3675){
					$('html,body').stop().animate({scrollTop : 3675},2000);
				}else if($topPosition<4350){
					$('html,body').stop().animate({scrollTop : 4350},3000);
				}else if($topPosition<4987){
					$('html,body').stop().animate({scrollTop : 4987},2000);
				}
			}
		});
	$('.quick-navigation .back-btn').on('click',function(){
		var	$topPosition = $(window).scrollTop();
		if($(window).width()>1600){
			if($topPosition>=6015){
				$('html,body').stop().animate({scrollTop : 5480},2000);
			}else if($topPosition>=5480){
				$('html,body').stop().animate({scrollTop : 4680},3000);
			}else if($topPosition>=4680){
				$('html,body').stop().animate({scrollTop : 3985},2000);
			}else if($topPosition>=3985){
				$('html,body').stop().animate({scrollTop : 3240},2000);
			}else if($topPosition>=3240){
				$('html,body').stop().animate({scrollTop : 2550},2000);
			}else if($topPosition>=2550){
				$('html,body').stop().animate({scrollTop : 2080},2000);
			}else if($topPosition>=2080){
				$('html,body').stop().animate({scrollTop : 0},6000);
			}
		}else{
			if($topPosition>=4987){
				$('html,body').stop().animate({scrollTop : 4350},2000);
			}else if($topPosition>=4350){
				$('html,body').stop().animate({scrollTop : 3675},3000);
			}else if($topPosition>=3675){
				$('html,body').stop().animate({scrollTop : 3140},2000);
			}else if($topPosition>=3140){
				$('html,body').stop().animate({scrollTop : 2555},2000);
			}else if($topPosition>=2555){
				$('html,body').stop().animate({scrollTop : 2132},2000);
			}else if($topPosition>=2132){
				$('html,body').stop().animate({scrollTop : 1920},2000);
			}else if($topPosition>=1920){
				$('html,body').stop().animate({scrollTop : 0},6000);
			}
		}
	});
	
	$(window).scroll(function(){
		var	$topPosition = $(window).scrollTop();
		//console.log($topPosition);
		//Back Button Enable/Disable
		if($topPosition>0){
			$('.quick-navigation .explore-more-btn').addClass('little');
			$('.quick-navigation .back-btn').fadeIn( 800 );
		}else if($topPosition==0){
			$('.quick-navigation .back-btn').fadeOut( 800 );
			$('.quick-navigation .explore-more-btn').removeClass('little');
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
			$animate3 = $positionSection3*(1),
			$section2Row1 = $('.section-holder-2 .block-1').offset().top - $topPosition + $('.section-holder-2 .block-1').height(),
			$section2Row2 = $('.section-holder-2 .block-3').offset().top - $topPosition + $('.section-holder-2 .block-3').height(),
			$section2Row3 = $('.section-holder-2 .block-5').offset().top - $topPosition + $('.section-holder-2 .block-5').height();


		if($section1Text.hasClass("is-sticky")){
			$('.main-section .image-holder').css('margin-top',$positionSection1);
			if($(window).width()>1600){
				
				if($section2Row1<600){
					if($('.section2item3').length==0&&$('.section2item5').length==0){
						$('.section-holder-2 .block-1 .categorylink1Stick > span > span').fadeOut('fast').remove();
					}
					if($('.section2item4').length==0&&$('.section2item6').length==0){
						$('.section-holder-2 .block-2 .categorylink2Stick > span > span').fadeOut('fast').remove();
					}
				}else{
					if($('.section-holder-2 .block-1 .categorylink1Stick > span > span').length==0){
						$('.section-holder-2 .block-1 .categorylink1Stick > span').append('<span style="display:none;">'+$('.section-holder-2 .block-1 .block-text').val()+'</span>');
						$('.section-holder-2 .block-1 .categorylink1Stick > span > span').fadeIn('fast');
					}
					if($('.section-holder-2 .block-2 .categorylink2Stick > span > span').length==0){	
						$('.section-holder-2 .block-2 .categorylink2Stick > span').append('<span style="display:none;">'+$('.section-holder-2 .block-2 .block-text').val()+'</span>');
						$('.section-holder-2 .block-2 .categorylink2Stick > span > span').fadeIn('fast');
					}
				}
				
				if($section2Row2<1240){
					if($section2Row2>540){
						if($('.section2item3').length==0){
							$('.section-holder-2 .block-1 .categorylink1Stick > span').append('<span style="display:none;" class="section2item3">'+$('.section-holder-2 .block-3 .category-link span > span').html()+'</span>');
							$('.section-holder-2 .block-1 .categorylink1Stick .section2item3').fadeIn('fast');
						}
						if($('.section2item4').length==0){
							$('.section-holder-2 .block-2 .categorylink2Stick > span').append('<span style="display:none;" class="section2item4">'+$('.section-holder-2 .block-4 .category-link span > span').html()+'</span>');
							$('.section-holder-2 .block-2 .categorylink2Stick .section2item4').fadeIn('fast');
						}
					}else{
						$('.section-holder-2 .block-1 .categorylink1Stick > span > span.section2item3').fadeOut('fast').remove();
						$('.section-holder-2 .block-2 .categorylink2Stick > span > span.section2item4').fadeOut('fast').remove();
					}
				}else{
					$('.section-holder-2 .block-1 .categorylink1Stick > span > span.section2item3').fadeOut('fast').remove();
					$('.section-holder-2 .block-2 .categorylink2Stick > span > span.section2item4').fadeOut('fast').remove();
				}
				
				if($section2Row3<720){
					if($('.section2item5').length==0){
						$('.section-holder-2 .block-1 .categorylink1Stick > span').append('<span style="display:none;" class="section2item5">'+$('.section-holder-2 .block-5 .category-link span > span').html()+'</span>');
						$('.section-holder-2 .block-1 .categorylink1Stick .section2item5').fadeIn('fast');
					}
					if($('.section2item6').length==0){
						$('.section-holder-2 .block-2 .categorylink2Stick > span').append('<span style="display:none;" class="section2item6">'+$('.section-holder-2 .block-6 .category-link span > span').html()+'</span>');
						$('.section-holder-2 .block-2 .categorylink2Stick .section2item6').fadeIn('fast');
					}
				}else{
					$('.section-holder-2 .block-1 .categorylink1Stick > span > span.section2item5').fadeOut('fast').remove();
					$('.section-holder-2 .block-2 .categorylink2Stick > span > span.section2item6').fadeOut('fast').remove();
				}
				
				if($section2Row3<618){
					$('.section-holder-2 .block-1 .categorylink1Stick').css('top',$section2Row3-110);
					$('.section-holder-2 .block-2 .categorylink2Stick').css('top',$section2Row3-110);
				}else{
					$('.section-holder-2 .block-1 .categorylink1Stick').css('top','500px');
					$('.section-holder-2 .block-2 .categorylink2Stick').css('top','500px');
				}
				
				if($topPosition<6015){
					$('.quick-navigation .explore-more-btn').fadeIn(800);
				}else{
					$('.quick-navigation .explore-more-btn').fadeOut(800);
				}
				$('.section-holder-1 > div.home_page_section1_image_panel1').stop().css({ "bottom": $animate1/1.2 });
				$('.section-holder-1 > div.home_page_section1_image_panel2').stop().css({ "bottom": $animate1/1.5 });
				$('.section-holder-1 > div.home_page_section1_image_panel3').stop().css({ "bottom": $animate1/1.4 });
				$('.section-holder-1 > div.home_page_section1_image_panel4').stop().css({ "bottom": $animate1/1.7 });
				$('.section-holder-1 > div.home_page_section1_image_panel5').stop().css({ "bottom": $animate1/5.7 });
				$('.section-holder-1 > div.home_page_section1_image_panel6').stop().css({ "bottom": $animate1/5.5 });
				$('.section-holder-1 > div.home_page_section1_image_panel7').stop().css({ "bottom": $animate1/2 });
				$('.section-holder-1 > div.home_page_section1_image_panel8').stop().css({ "bottom": $animate1/3 });
				
				/*var $topSection2Block1 = $animate2/11+50;
				if($topSection2Block1<=70){
					$('.section-holder-2 > div.block-1').fadeTo( 300, 0 );
				}else{
					$('.section-holder-2 > div.block-1').fadeTo( 300, 1 );
				}*/
				
				/*var $topSection2Block2 = ($animate2/3)-150;
				if($topSection2Block2<=17){
					$('.section-holder-2 > div.block-2').fadeTo( 300, 0 );
				}else{
					$('.section-holder-2 > div.block-2').fadeTo( 300, 1 );
				}*/
				
				/*var $topSection2Block3 = ($animate2)+($('.section-holder-2 > div.block-1').height()/2)+150;
				if($topSection2Block3<=160){
					$('.section-holder-2 > div.block-3').fadeTo( 300, 0 );
				}else{
					$('.section-holder-2 > div.block-3').fadeTo( 300, 1 );
				}*/
				
				/*var $topSection2Block4 = ($animate2/6)+($('.section-holder-2 > div.block-1').height()-100);
				if($topSection2Block4<=320){
					$('.section-holder-2 > div.block-4').fadeTo( "slow", 0 );
				}else{
					$('.section-holder-2 > div.block-4').fadeTo( "slow", 1 );
				}*/
				
				/*var $topSection2Block5 = ($animate2/2)+$('.section-holder-2 > div.block-1').height()+$('.section-holder-2 > div.block-2').height()+400;
				if($topSection2Block5<=979){
					$('.section-holder-2 > div.block-5').fadeTo( "slow", 0 );
				}else{
					$('.section-holder-2 > div.block-5').fadeTo( "slow", 1 );
				}*/
				
				/*var $topSection2Block6 = ($animate2)+$('.section-holder-2 > div.block-1').height()+$('.section-holder-2 > div.block-2').height()+$('.section-holder-2 > div.block-3').height()-200;
				if($topSection2Block6<=868){
					$('.section-holder-2 > div.block-6').fadeTo( "slow", 0 );
				}else{
					$('.section-holder-2 > div.block-6').fadeTo( "slow", 1 );
				}*/

				if($positionSection2<=1325){
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
				
				
				$('.section-holder-3 > div.block-3').stop().css({ "top": ($animate3/2.5)-100 });
				$('.section-holder-3 > div.block-4').stop().css({ "top": ($animate3/1.5)-300 });
				$('.section-holder-3 > div.block-5').stop().css({ "top": ($animate3/30)+600 });
				$('.section-holder-3 > div.block-6').stop().css({ "top": ($animate3/10)+630 });
				$('.section-holder-3 > div.block-7').stop().css({ "top": ($animate3/30)+500 });
			}else{
				if($section2Row1<600){
					if($('.section2item3').length==0&&$('.section2item5').length==0){
						$('.section-holder-2 .block-1 .categorylink1Stick > span > span').fadeOut('fast').remove();
					}
					if($('.section2item4').length==0&&$('.section2item6').length==0){
						$('.section-holder-2 .block-2 .categorylink2Stick > span > span').fadeOut('fast').remove();
					}
				}else{
					if($('.section-holder-2 .block-1 .categorylink1Stick > span > span').length==0){
						$('.section-holder-2 .block-1 .categorylink1Stick > span').append('<span style="display:none;">'+$('.section-holder-2 .block-1 .block-text').val()+'</span>');
						$('.section-holder-2 .block-1 .categorylink1Stick > span > span').fadeIn('fast');
					}
					if($('.section-holder-2 .block-2 .categorylink2Stick > span > span').length==0){	
						$('.section-holder-2 .block-2 .categorylink2Stick > span').append('<span style="display:none;">'+$('.section-holder-2 .block-2 .block-text').val()+'</span>');
						$('.section-holder-2 .block-2 .categorylink2Stick > span > span').fadeIn('fast');
					}
				}
				
				if($section2Row2<1040){
					if($section2Row2>540){
						if($('.section2item3').length==0){
							$('.section-holder-2 .block-1 .categorylink1Stick > span').append('<span style="display:none;" class="section2item3">'+$('.section-holder-2 .block-3 .category-link span > span').html()+'</span>');
							$('.section-holder-2 .block-1 .categorylink1Stick .section2item3').fadeIn('fast');
						}
						if($('.section2item4').length==0){
							$('.section-holder-2 .block-2 .categorylink2Stick > span').append('<span style="display:none;" class="section2item4">'+$('.section-holder-2 .block-4 .category-link span > span').html()+'</span>');
							$('.section-holder-2 .block-2 .categorylink2Stick .section2item4').fadeIn('fast');
						}
					}else{
						$('.section-holder-2 .block-1 .categorylink1Stick > span > span.section2item3').fadeOut('fast').remove();
						$('.section-holder-2 .block-2 .categorylink2Stick > span > span.section2item4').fadeOut('fast').remove();
					}
				}else{
					$('.section-holder-2 .block-1 .categorylink1Stick > span > span.section2item3').fadeOut('fast').remove();
					$('.section-holder-2 .block-2 .categorylink2Stick > span > span.section2item4').fadeOut('fast').remove();
				}
				
				if($section2Row3<720){
					if($('.section2item5').length==0){
						$('.section-holder-2 .block-1 .categorylink1Stick > span').append('<span style="display:none;" class="section2item5">'+$('.section-holder-2 .block-5 .category-link span > span').html()+'</span>');
						$('.section-holder-2 .block-1 .categorylink1Stick .section2item5').fadeIn('fast');
					}
					if($('.section2item6').length==0){
						$('.section-holder-2 .block-2 .categorylink2Stick > span').append('<span style="display:none;" class="section2item6">'+$('.section-holder-2 .block-6 .category-link span > span').html()+'</span>');
						$('.section-holder-2 .block-2 .categorylink2Stick .section2item6').fadeIn('fast');
					}
				}else{
					$('.section-holder-2 .block-1 .categorylink1Stick > span > span.section2item5').fadeOut('fast').remove();
					$('.section-holder-2 .block-2 .categorylink2Stick > span > span.section2item6').fadeOut('fast').remove();
				}
				
				if($section2Row3<618){
					$('.section-holder-2 .block-1 .categorylink1Stick').css('top',$section2Row3-110);
					$('.section-holder-2 .block-2 .categorylink2Stick').css('top',$section2Row3-110);
				}else{
					$('.section-holder-2 .block-1 .categorylink1Stick').css('top','500px');
					$('.section-holder-2 .block-2 .categorylink2Stick').css('top','500px');
				}
				
				if($topPosition<4987){
					$('.quick-navigation .explore-more-btn').fadeIn(800);
				}else{
					$('.quick-navigation .explore-more-btn').fadeOut(800);
				}
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
				
				/*var $topSection2Block1 = $animate2/11;
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
				
				var $topSection2Block3 = ($animate2)+($('.section-holder-2 > div.block-1').height()/2)+50;
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
				
				var $topSection2Block5 = ($animate2/2)+$('.section-holder-2 > div.block-1').height()+$('.section-holder-2 > div.block-2').height()+360;
				$('.section-holder-2 > div.block-5').stop().css({ "top": $topSection2Block5 });
				if($topSection2Block5<=979){
					$('.section-holder-2 > div.block-5').fadeTo( "slow", 0 );
				}else{
					$('.section-holder-2 > div.block-5').fadeTo( "slow", 1 );
				}
				
				var $topSection2Block6 = ($animate2)+$('.section-holder-2 > div.block-1').height()+$('.section-holder-2 > div.block-2').height()+$('.section-holder-2 > div.block-3').height()-400;
				$('.section-holder-2 > div.block-6').stop().css({ "top": $topSection2Block6 });
				if($topSection2Block6<=868){
					$('.section-holder-2 > div.block-6').fadeTo( "slow", 0 );
				}else{
					$('.section-holder-2 > div.block-6').fadeTo( "slow", 1 );
				}*/

				if($positionSection2<=1750){
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
				
				$('.section-holder-3 > div.block-3').stop().css({ "top": ($animate3/2.5)-400 });
				$('.section-holder-3 > div.block-4').stop().css({ "top": ($animate3/1.5)-750 });
				$('.section-holder-3 > div.block-5').stop().css({ "top": ($animate3/30)+700 });
				$('.section-holder-3 > div.block-6').stop().css({ "top": ($animate3/10)+850 });
				$('.section-holder-3 > div.block-7').stop().css({ "top": ($animate3/13)+600 });
			}
			
			
		}else{
			$('.main-section .image-holder').css('margin-top','0');
		}
	});
	
	$(window).resize(function(){
		if($(window).width()<1600){
			$('.section-holder-2').css('margin-top','-1100px');
			//$('.section-holder-3').css('margin-top','-700px');
		}else{
			$('.section-holder-2').css('margin-top','-500px');
			//$('.section-holder-3').css('margin-top','-640px');
		}
		$(".home_page_section1_text_block").sticky('update');
		$(".home_page_section3_text_block").sticky('update');
		$('.section-holder-3 > div.block-2').sticky('update');
	});
	$(window).resize();
	$('html,body').scrollTop(0);
});
