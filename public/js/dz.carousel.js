function GriyaCarousel()
{

	/*  testimonial one function by = owl.carousel.js */
	jQuery('.front-view-slider').owlCarousel({
		loop:false,
		margin:30,
		nav:true,
		autoplaySpeed: 3000,
		navSpeed: 3000,
		paginationSpeed: 3000,
		slideSpeed: 3000,
		smartSpeed: 3000,
		autoplay: false,
		animateOut: 'fadeOut',
		dots:true,
		navText: ['', ''],
		responsive:{
			0:{
				items:1
			},
			
			480:{
				items:1
			},			
			
			767:{
				items:1
			},
			1750:{
				items:1
			}
		}
	})
	jQuery('.image-gallery').owlCarousel({
		loop:false,
		margin:30,
		nav:true,
		autoplaySpeed: 3000,
		navSpeed: 3000,
		paginationSpeed: 3000,
		slideSpeed: 3000,
		smartSpeed: 3000,
		autoplay: false,
		navText: ['<i class="fas fa-chevron-left"></i>', '<i class="fas fa-chevron-right"></i>'],
		responsive:{
			0:{
				items:1
			},
			
			480:{
				items:1
			},			
			
			767:{
				items:2
			},
			1750:{
				items:3
			}
		}
	})
}

jQuery(window).on('load',function(){
	setTimeout(function(){
		GriyaCarousel();
	}, 1000); 
});