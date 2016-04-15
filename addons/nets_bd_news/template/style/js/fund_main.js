var Fund = {
	init:function(){
		this.bottomMenu();
	},
	bottomMenu:function(){
		Exp.bottomMenu('.bottom-menu','bottom-menu-up',300,'#content');
	},
	alertBox: function(opts){
        return Exp.alertBox({
            type:"validate",
            msg: opts.msg,
            animate:"alert-box-anim",
            bgAnimate: "alert-bg-anim",
            callBack:function(){
            	var self = this;
            	setTimeout(function(){
            		self.reset();
            	},1500);
            	if(opts.callBack){
            		opts.callBack();
            	}
            }
        });
    }
};

Fund.init();
 