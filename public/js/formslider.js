$(document).ready(()=>{
		
  $("#content-payment").css('display','none');

  $("#showContentData").click(function(){
    $("#content-data").animate({left:-800},500,function(){
      $("#content-data").hide();
      $("#content-payment").show();
    });
  })

  $("#showContentPayment").click(function(){
    $("#content-payment").hide();
    $("#content-data").show();
    $("#content-data").animate({left:0},500,function(){
    });
    
  })

});