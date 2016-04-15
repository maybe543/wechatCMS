var gcountServer = Array.prototype.pop.apply($('script')).src.match(/(http:\/\/[^/]+).+/).pop(),
    map = { 'a.cdn.mtq.tvm.cn' : 'a.iwmh.mtq.tvm.cn' },
    _host = gcountServer.match('http://(.+)')[1];
    
if(map[_host]){
    gcountServer = 'http://'+map[_host];
}

function  U(url){
    var urlInfo;
    function init(url){
        return urlInfo = parseUrl(url);
    }
    
    function parseUrl(url){
        var a = url.split('?'),
             _arr = a[0].split('://'),
             num = _arr[1].indexOf('/');
        return {
          'url' : url,
          'protocal':_arr[0],
          'host' : _arr[1].substr(0,num),
          'uri' : _arr[1].substr(num),
          'argsStr':a[1],
        };
    }
    
    function handlerInfo(routejson){
        return urlInfo;
    }
    
    function getCurrentUrl(){
        return window.location.href;
    }
  
    if(!url){
        url = getCurrentUrl();
    }
    init(url);
    
    //开放的接口慢慢扩展
    return {
        'info' : urlInfo,
        'handlerInfo' : handlerInfo
    };
} 

String.prototype.trim = function (letter){
  var re ;
  if(!letter){
    letter = '\\s'
  }
  re = new RegExp("^"+letter+"*([^"+letter+"][\\s\\S]*[^"+letter+"])"+letter+"*$","i");
  return this.replace( re ,'$1');
}
String.prototype.ltrim = function (letter){
  var re ;
  if(!letter){
    letter = '\\s'
  }
  re = new RegExp("^"+letter+"*([^"+letter+"][\\s\\S]*)$","i");
  return this.replace( re ,'$1');
}
String.prototype.rtrim = function (letter){
  var re ;
  if(!letter){
    letter = '\\s'
  }
  re = new RegExp("^([\\s\\S]*[^"+letter+"])"+letter+"*$","i");
  return this.replace( re ,'$1');
}

function Statistic(serverName,keysJson){
    var countServer;
    if('string' !== typeof arguments[0]){
        keysJson =  arguments[0];
        countServer = gcountServer;
    }else{
        countServer = serverName;
    }
    
    if(!keysJson){
        console.log('缺少参数keysJson');
        return;
    }
    
    var uriInfo     = U().handlerInfo(),
        argsStr     = uriInfo.argsStr ?  uriInfo.argsStr : '',
        arr         = argsStr.match(/token=([^&]*)/),
        countUrl    = countServer.rtrim('/') + '/rest/statistic/count',
        getCountUrl = countServer.rtrim('/') + '/rest/statistic/getCount',
        pageKey     = argsStr,
        _paramjson  = keysJson.pageParam ? keysJson.pageParam : null,
        _paramArr   = [],
        _paramStr   = '',
        appid       = keysJson.appid,    
        funcKey     = keysJson.funcKey,
        token       = keysJson.token ? keysJson.token : (arr && arr[1]?arr[1]:'' ),
        title       = keysJson.title ? keysJson.title : $('title').html(),
        url         = uriInfo.url;
        
    if(_paramjson){
        for (var i in _paramjson) {
            _paramArr.push(i+'='+_paramjson[i]);
        }
        
        _paramStr  = _paramArr.join('&');
        if(url.indexOf('?') == -1){
            url = url+'?'+_paramStr;
        }else{
            url = url+'&'+_paramStr;
        }
    }
    
    function setCount(){
        $.ajax({
             url:countUrl,
             dataType:"jsonp",
             data : {appid:appid,funcKey:funcKey,pageKey:url,title:title,token:token},
             jsonpCallback : 'jsonpcallback',
             jsonp:"jsonpcallback",
        });
    }
    
    function getCount(callback){
        $.ajax({
             url:getCountUrl,
             dataType:"jsonp",
             data : {appid:appid,funcKey:funcKey,pageKey:url,title:title,token:token},
             jsonpCallback : callback ? callback :'getCountcallBack',
             jsonp:"jsonpcallback",
        });
    }
    
    function praise(operate,callback){
        $.ajax({
             url:countServer.rtrim('/') + '/rest/statistic/praise',
             dataType:"jsonp",
             data : {appid:appid,funcKey:funcKey,pageKey:url,title:title,token:token,operate:operate},
             jsonpCallback : callback ? callback :'praisecallBack',
             jsonp:"jsonpcallback",
        });
    }
    
    function share(callback){
        $.ajax({
             url:countUrl,
             dataType:"jsonp",
             data : {appid:appid,funcKey:funcKey,pageKey:url,title:title,token:token,operate:2},
             jsonpCallback : callback ? callback :'sharecallBack',
             jsonp:"jsonpcallback",
        });
    }
    
    return {
        setCount:setCount,
        getCount : getCount,
        praise : praise,
        share:share
    }
}

function getCountcallBack(res){
    console.log(res);
}

function sharecallBack(res){
    console.log('分享成功');
}