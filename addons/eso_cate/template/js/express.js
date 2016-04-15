jQuery.expressUllist = function(vobj, iobj) {
    var _expressItem=[{"id":1,"code":"aae","name":"AAE快递"},{"id":2,"code":"anjie","name":"安捷快递"},{"id":3,"code":"anneng","name":"安能物流"},{"id":4,"code":"anxun","name":"安迅物流"},{"id":5,"code":"aoshuo","name":"奥硕物流"},{"id":6,"code":"aramex","name":"Aramex国际快递"},{"id":7,"code":"baiqian","name":"百千诚国际物流"},{"id":8,"code":"balunzhi","name":"巴伦支"},{"id":9,"code":"baotongda","name":"宝通达"},{"id":10,"code":"benteng","name":"成都奔腾国际快递"},{"id":11,"code":"changtong","name":"长通物流"},{"id":12,"code":"chengguang","name":"程光快递"},{"id":13,"code":"chengji","name":"城际快递"},{"id":14,"code":"chengshi100","name":"城市100"},{"id":15,"code":"chuanxi","name":"传喜快递"},{"id":16,"code":"chuanzhi","name":"传志快递"},{"id":17,"code":"chukouyi","name":"出口易物流"},{"id":18,"code":"citylink","name":"CityLinkExpress"},{"id":19,"code":"coe","name":"东方快递"},{"id":20,"code":"coscon","name":"中国远洋运输(COSCON)"},{"id":21,"code":"cszx","name":"城市之星"},{"id":22,"code":"dada","name":"大达物流"},{"id":23,"code":"dajin","name":"大金物流"},{"id":24,"code":"datian","name":"大田物流"},{"id":25,"code":"dayang","name":"大洋物流快递"},{"id":26,"code":"debang","name":"德邦物流"},{"id":27,"code":"dhl","name":"DHL快递"},{"id":28,"code":"diantong","name":"店通快递"},{"id":29,"code":"disifang","name":"递四方速递"},{"id":30,"code":"dpex","name":"DPEX快递"},{"id":31,"code":"dsu","name":"D速快递"},{"id":32,"code":"ees","name":"百福东方物流"},{"id":33,"code":"ems","name":"EMS快递"},{"id":34,"code":"eyoubao","name":"E邮宝"},{"id":35,"code":"fanyu","name":"凡宇快递"},{"id":36,"code":"fardar","name":"Fardar"},{"id":37,"code":"fedex","name":"国际Fedex"},{"id":38,"code":"fedexcn","name":"Fedex国内"},{"id":39,"code":"feibao","name":"飞豹快递"},{"id":40,"code":"feihang","name":"原飞航物流"},{"id":41,"code":"feihu","name":"飞狐快递"},{"id":42,"code":"feite","name":"飞特物流"},{"id":43,"code":"feiyuan","name":"飞远物流"},{"id":44,"code":"fengda","name":"丰达快递"},{"id":45,"code":"gangkuai","name":"港快速递"},{"id":46,"code":"gaotie","name":"高铁快递"},{"id":47,"code":"gdyz","name":"广东邮政物流"},{"id":48,"code":"gnxb","name":"邮政国内小包"},{"id":49,"code":"gongsuda","name":"共速达物流|快递"},{"id":50,"code":"guanda","name":"冠达快递"},{"id":51,"code":"guotong","name":"国通快递"},{"id":52,"code":"haihong","name":"山东海红快递"},{"id":53,"code":"haolaiyun","name":"好来运快递"},{"id":54,"code":"haosheng","name":"昊盛物流"},{"id":55,"code":"hebeijianhua","name":"河北建华快递"},{"id":56,"code":"henglu","name":"恒路物流"},{"id":57,"code":"huacheng","name":"华诚物流"},{"id":58,"code":"huahan","name":"华翰物流"},{"id":59,"code":"huahang","name":"华航快递"},{"id":60,"code":"huangmajia","name":"黄马甲快递"},{"id":61,"code":"huaqi","name":"华企快递"},{"id":62,"code":"huayu","name":"华宇物流"},{"id":63,"code":"huitong","name":"汇通快递"},{"id":64,"code":"hutong","name":"户通物流"},{"id":65,"code":"hwhq","name":"海外环球快递"},{"id":66,"code":"jiaji","name":"佳吉快运"},{"id":67,"code":"jiayi","name":"佳怡物流"},{"id":68,"code":"jiayu","name":"佳宇物流"},{"id":69,"code":"jiayunmei","name":"加运美快递"},{"id":70,"code":"jiete","name":"捷特快递"},{"id":71,"code":"jinda","name":"金大物流"},{"id":72,"code":"jingdong","name":"京东快递"},{"id":73,"code":"jingguang","name":"京广快递"},{"id":74,"code":"jinyue","name":"晋越快递"},{"id":75,"code":"jiuyi","name":"久易快递"},{"id":76,"code":"jixianda","name":"急先达物流"},{"id":77,"code":"jldt","name":"嘉里大通物流"},{"id":78,"code":"kangli","name":"康力物流"},{"id":79,"code":"kcs","name":"顺鑫(KCS)快递"},{"id":80,"code":"kuaijie","name":"快捷快递"},{"id":81,"code":"kuaitao","name":"快淘速递"},{"id":82,"code":"kuaiyouda","name":"快优达速递"},{"id":83,"code":"kuanrong","name":"宽容物流"},{"id":84,"code":"kuayue","name":"跨越快递"},{"id":85,"code":"lanhu","name":"蓝弧快递"},{"id":86,"code":"lejiedi","name":"乐捷递快递"},{"id":87,"code":"lianhaotong","name":"联昊通快递"},{"id":88,"code":"lijisong","name":"成都立即送快递"},{"id":89,"code":"lindao","name":"上海林道货运"},{"id":90,"code":"longbang","name":"龙邦快递"},{"id":91,"code":"menduimen","name":"门对门快递"},{"id":92,"code":"minbang","name":"民邦快递"},{"id":93,"code":"mingliang","name":"明亮物流"},{"id":94,"code":"minsheng","name":"闽盛快递"},{"id":95,"code":"nell","name":"尼尔快递"},{"id":96,"code":"nengda","name":"港中能达快递"},{"id":97,"code":"nsf","name":"新顺丰（NSF）快递"},{"id":98,"code":"ocs","name":"OCS快递"},{"id":99,"code":"peixing","name":"陪行物流"},{"id":100,"code":"pinganda","name":"平安达"},{"id":101,"code":"pingyou","name":"中国邮政平邮"},{"id":102,"code":"quanchen","name":"全晨快递"},{"id":103,"code":"quanfeng","name":"全峰快递"},{"id":104,"code":"quanritong","name":"全日通快递"},{"id":105,"code":"quanyi","name":"全一快递"},{"id":106,"code":"ririshun","name":"日日顺物流"},{"id":107,"code":"riyu","name":"日昱物流"},{"id":108,"code":"rpx","name":"RPX保时达"},{"id":109,"code":"ruifeng","name":"瑞丰速递"},{"id":110,"code":"saiaodi","name":"赛澳递"},{"id":111,"code":"santai","name":"三态速递"},{"id":112,"code":"scs","name":"伟邦(SCS)快递"},{"id":113,"code":"shengan","name":"圣安物流"},{"id":114,"code":"shengbang","name":"晟邦物流"},{"id":115,"code":"shengfeng","name":"盛丰物流"},{"id":116,"code":"shenghui","name":"盛辉物流"},{"id":117,"code":"shentong","name":"申通快递"},{"id":118,"code":"shiyun","name":"世运快递"},{"id":119,"code":"shunfeng","name":"顺丰快递"},{"id":120,"code":"suchengzhaipei","name":"速呈宅配"},{"id":121,"code":"suijia","name":"穗佳物流"},{"id":122,"code":"sure","name":"速尔快递"},{"id":123,"code":"sutong","name":"速通物流"},{"id":124,"code":"tiantian","name":"天天快递"},{"id":125,"code":"tnt","name":"TNT快递"},{"id":126,"code":"tongzhishu","name":"高考录取通知书"},{"id":127,"code":"ucs","name":"合众速递"},{"id":128,"code":"ups","name":"UPS快递"},{"id":129,"code":"usps","name":"USPS快递"},{"id":130,"code":"wanbo","name":"万博快递"},{"id":131,"code":"weitepai","name":"微特派"},{"id":132,"code":"xianglong","name":"祥龙运通快递"},{"id":133,"code":"xinbang","name":"新邦物流"},{"id":134,"code":"xinfeng","name":"信丰快递"},{"id":135,"code":"xingchengzhaipei","name":"星程宅配快递"},{"id":136,"code":"xiyoute","name":"希优特快递"},{"id":137,"code":"yad","name":"源安达快递"},{"id":138,"code":"yafeng","name":"亚风快递"},{"id":139,"code":"yibang","name":"一邦快递"},{"id":140,"code":"yinjie","name":"银捷快递"},{"id":141,"code":"yishunhang","name":"亿顺航快递"},{"id":142,"code":"yousu","name":"优速快递"},{"id":143,"code":"ytfh","name":"北京一统飞鸿快递"},{"id":144,"code":"yuancheng","name":"远成物流"},{"id":145,"code":"yuantong","name":"圆通快递"},{"id":146,"code":"yuefeng","name":"越丰快递"},{"id":147,"code":"yuhong","name":"宇宏物流"},{"id":148,"code":"yumeijie","name":"誉美捷快递"},{"id":149,"code":"yunda","name":"韵达快递"},{"id":150,"code":"yuntong","name":"运通中港快递"},{"id":151,"code":"zengyi","name":"增益快递"},{"id":152,"code":"zhaijisong","name":"宅急送快递"},{"id":153,"code":"zhengzhoujianhua","name":"郑州建华快递"},{"id":154,"code":"zhima","name":"芝麻开门快递"},{"id":155,"code":"zhongtian","name":"济南中天万运"},{"id":156,"code":"zhongtie","name":"中铁快运"},{"id":157,"code":"zhongtong","name":"中通快递"},{"id":158,"code":"zhongxinda","name":"忠信达快递"},{"id":159,"code":"zhongyou","name":"中邮物流"}];
    //
    vobj.css("position","re");
    var _t = vobj.offset().top;     		//控件的定位点高
    var _h = vobj.outerHeight();  		    //控件本身的高
    var _w = vobj.outerWidth();  		    //控件本身的宽
    var _l = vobj.offset().left;    		//控件的定位点宽
    //
    var ljson = eval(_expressItem);
    var _html = "<ul>";
    for(var i=0; i<ljson.length; i++) { _html+= '<li data-id="'+ljson[i].id+'" data-code="'+ljson[i].code+'">'+ljson[i].name+'</li>'; }
    _html+= '</ul>';
    $("#__jQuery-express").remove();
    $intemp = $('<div id="__jQuery-express" style="display:none;">' + _html + '</span>');
    $intemp.css({
        top:_t+_h,
        left:_l,
        width:_w,
        position:'absolute',
        'background-color': '#ffffff',
        'z-index': '123456789'
    });
    $intemp.find("ul").css({
        border: '1px solid #cccccc',
        'max-height': '220px',
        'overflow': 'auto',
        'list-style': 'none',
        'margin': '0',
        'padding': '0'
    });
    $intemp.find("li").css({
        'padding': '4px 6px',
        'border-bottom': '1px dashed #cccccc',
        'list-style': 'none'
    });
    $(document.body).append($intemp);
    vobj.focus(function(){
        $intemp.show();
        $intemp.css({
            top:vobj.offset().top+vobj.outerHeight(),
            left:vobj.offset().left
        });
    }).blur(function(){
        $intemp.hide();
    }).keyup(function(){
        if (iobj){
            iobj.val("");
            var curVal = vobj.val();
            $intemp.find("li").each(function(){
                var textValue = $(this).text();
                if (textValue.indexOf(curVal) != -1) {
                    $(this).show();
                }else{
                    $(this).hide();
                }
                if (textValue == curVal) {
                    iobj.val($(this).attr('data-code'));
                }
            });
            $intemp.show();
        }
    });
    $intemp.find("li").mousedown(function(){
        vobj.val($(this).text());
        if (iobj) iobj.val($(this).attr('data-code'));
    });
}