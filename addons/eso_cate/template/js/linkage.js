/**
 * 联动菜单
 * @param objid
 * @param url
 * @param parentid
 * @param num
 * @param async
 */
function linkage(objid, url, parentid, num, async){
    var obj = $("#"+objid);
    var objval = obj.val();
    if (obj.length < 1) return;
    if (num > 0 && parentid == 0) return;
    obj.hide();

    var _num = num + 1;
    var inhtml = '',inoption = '';

    inhtml = '<select id="__linkage" name="__linkage_'+_num+'" data-n="'+_num+'" onchange="linkage(\''+objid+'\', \''+url+'\', this.value, '+_num+')">';
    if (num == 0){
        inhtml+= '<option value="0">请选择省份</option>';
    }else if (num == 1){
        inhtml+= '<option value="0">请选择市</option>';
    }else if (num == 2){
        inhtml+= '<option value="0">请选择区(县)</option>';
    }else if (num == 3){
        inhtml+= '<option value="0">请选择街道</option>';
    }

    var linktext = "";
    $("select#__linkage").each(function(){
        if ($(this).attr("data-n") > num) {
            $(this).remove();
        }else{
            if ($(this).val() > 0) {
                if (linktext != ""){
                    linktext+= "||" + $(this).find('option:selected').text();
                }else{
                    linktext+= $(this).find('option:selected').text();
                }
                linktext+= ":" + $(this).val();
            }
        }
    });
    obj.val(linktext);

    if (num > 0){
        var objin = $("select[name=__linkage_"+num+"]");
    }else{
        var objin = obj;
    }
    if (async !== false) async = true;
    $.ajax({
        type: "GET",
        url: url + parentid,
        dataType: "json",
        async: async,
        success: function (msg) {
            if (msg.length < 1){
                return;
            }
            for(var i=0;i<msg.length;i++){
                inoption+= '<option value="'+msg[i].id+'">'+msg[i].name+'</option>';
            }
            objin.after(inhtml+inoption+'</select>');
            if (num == 0 && objval != ""){
                setTimeout(function(){
                    var vi = objval.split('||');
                    for (i=0; i<vi.length; i++) {
                        if (vi[i].indexOf(':') == -1) break;
                        var vi2 = vi[i].split(':');
                        if (isNaN(vi2[1])) break;
                        $("select[name=__linkage_"+(i+1)+"]").val(vi2[1]);
                        linkage(objid, url, vi2[1], (i+1), false);
                    }
                },100);
            }
        },
        error: function (msg) {
        }
    });
}
