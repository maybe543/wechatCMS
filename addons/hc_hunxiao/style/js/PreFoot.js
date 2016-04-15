// JavaScript Document
$(window).load(function(){
	$("#loading").hide();
	})
$(function(){
	$("#distribution-apply").click(function(event){
		event.preventDefault();
		$("#distribution-tip").fadeIn();
		setTimeout(function(){
		$("#distribution-tip").fadeOut();
			},4000)
		}   	 
	);	
	//close advertisement
	$("#advertisement-close").click(function(){
		$("#advertisement-apptip").hide();
		$("#fromesb-wechat").animate({
			top:0
			});
		})
	//contact float
	$("#contFloat").click(function(event){
		event.preventDefault();
		$("#contFloat-detail").show();
		})
	$("#contFloat-detail-close").click(function(){
		$("#contFloat-detail").hide();
		})
		
	//shopping progress
	//$("#addCart,#buyBtn1").click(function(e){			
//	  $("#mask").show();
//	  $('body').css("overflow","hidden");
//	  $("#s_buy").slideDown();
//	  $("#addcart_way").removeClass("addcart-way")
//	  if(e.target.id=="buyBtn1"){
//		  $("#submit_ok").attr("href","order/cart.html");
//		  $("#submit_ok").unbind();
//		  }
//	})
//	$("#mask,#icon_close").click(function(){
//		$("#s_buy").slideUp();
//		$("#mask").hide();
//		$('body').css("overflow","auto");
//		})
//	$("#submit_ok").click(function(){
//			   $("#s_buy").slideUp();
//		$("#mask").hide();
//		$('body').css("overflow","auto");
//		$("#addcart_way").show();
//		$("#addcart_way").addClass("addcart-way");
//		setTimeout(function(){
//			$("#success_tip_line").fadeIn();		
//			},1000);
//		setTimeout(function(){
//			$("#success_tip_line").fadeOut();		
//			},3000);		    		   
//        });
    //collect
	$("#collect-link").on("click",function(){
			$("#collect-tip").show();
			})
		$("#a-know").on("click",function(){
			$("#collect-tip").hide();
			})
	//share
	$("#share-link").on("click",function(){
			$("#share-tip").show();
			})
		$("#a-know").on("click",function(){
			$("#share-tip").hide();
			})
	//app download close
	$("#appdown-close").on("click",function(){
		$("#apptip").hide();
		})	
    //youhuiquan
	$("#youhuiquan").find("a").on("click",function(event){
		  event.preventDefault();  
		  if($(this).hasClass("first")==true){
			  $("#success_tip_line").show();
			  setTimeout(function(){
				  $("#success_tip_line").hide();
				  },1500)
			  }
		  else{
			  $("#youhuiquan-alert").show();			  
			  }		  
		  })
	  $("#btn-share").on("click",function(event){
		  event.preventDefault();
		  $("#youhuiquan-alert").hide();
		  $("#mask-share").show();		  
		  })
	   $("#mask-share").on("click",function(){
		    $("#mask-share").hide();	
		   })
	  $("#youhuiquan-close").on("click",function(){
		  $("#youhuiquan-alert").hide();
		  });
	 //form somebody close
	 $("#fromesb-close").on("click",function(){
		 $("#fromesb-wechat").hide();
		 })
	//ios4 fixed
	var isIOS = (/iphone|ipad/gi).test(navigator.appVersion);
	if (isIOS) {
	$('#s_buy').on('focus', 'input', function () {
	$('.head').addClass('relative');
	$('.mod_slider').css("margin-top","10px")
	}).on('focusout', 'input', function () {
	$('.head').removeClass('relative');
	$('.mod_slider').css("margin-top","50px")
	});
	}
	//menu float
	var menufloatclicknumber=0;
	  function menufloatin(){
		  $(".menu-c").removeClass("out");
		  $("#menufloat").addClass("show")
		  $(".mask_menu").fadeIn();
		  $("#menufloat-c").show();
		  $(".menu-c-inner").removeClass("outer");
		  $(".menu-c-inner").addClass("in")
		  $(".menu-c-inner a").show();
		  menufloatclicknumber=1;
		  }
	  function menufloatout(){
		  $("#menufloat").removeClass("show")
		  $(".mask_menu").fadeOut();
		  $(".menu-c-inner").removeClass("in")
		  $(".menu-c-inner").addClass("outer")
		  $(".menu-c-inner a").hide();
		  $(".menu-c").addClass("out");
		  menufloatclicknumber=0;		
		  }	   
	  $("#menufloat").click(function(){
		  if(menufloatclicknumber==0){
		     menufloatin();
			 }
		  else{
		  	 menufloatout();
			  }			 	
		  })
		$(".mask_menu").click(function(){
			menufloatout();
			})
})