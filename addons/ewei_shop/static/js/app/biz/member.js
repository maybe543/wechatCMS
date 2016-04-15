/*
 * 人人商城
 * 
 * @author ewei 狸小狐 QQ:22185157 
 */
define(['jquery','core'], function($,core){
    var member = {};
    
    //获取用户资料
    member.get = function(callback){
        
          core.json('member/info',{},function(ret){
                if(callback){
                    callback(ret);
                }
            },true);
    }

    return member;
    
});

