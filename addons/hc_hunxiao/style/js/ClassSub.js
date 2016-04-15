var paras = cssItem.split('|');
if (csst != "") {
    for (var j = 0; j < paras.length; j++) {
        document.writeln("<link rel='stylesheet' type='text/css' href='../addons/hc_hunxiao/style/css/"+ paras[j] + cssv + ".css?" + csst + "' />");
    }
} else {
    for (var j = 0; j < paras.length; j++) {
        document.writeln("<link rel='stylesheet' type='text/css' href='../addons/hc_hunxiao/style/css/"+ paras[j] + cssv + ".css' />");
    }
}