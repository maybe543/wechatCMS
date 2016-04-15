// JavaScript Document
$(function(){
	//tab


	//shopping progress
	$("#addCart,#buyBtn1").click(function(e){			
	  $("#mask").show();
	  $('body').css("overflow","hidden");
	  $("#s_buy").slideDown();
	  $("#addcart_way").removeClass("addcart-way")
	  if(e.target.id=="buyBtn1"){
		  //$("#submit_ok").attr("href","order/cart.html");
		//  $("#submit_ok").unbind();
		  }
	})
	$("#mask,#icon_close").click(function(){
		$("#s_buy").slideUp();
		$("#mask").hide();
		$('body').css("overflow","auto");
		})
    //share
	$("#share").on("click",function(){
		$("#share-tip").fadeIn();
		})
	$("#a-know").on("click",function(){
		$("#share-tip").fadeOut();
		})
	//$("#submit_ok").click(function(){
//	    $("#s_buy").slideUp();
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
//       });
	
	})