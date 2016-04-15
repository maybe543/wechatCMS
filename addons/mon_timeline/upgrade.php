<?php


/*
if (!pdo_fieldexists('mon_shake', 'follow_dlg_tip')) {
    pdo_query("ALTER TABLE " . tablename('mon_shake') . " ADD `follow_dlg_tip` varchar(500) ;");

}


if (!pdo_fieldexists('mon_shake', 'follow_btn_name')) {
    pdo_query("ALTER TABLE " . tablename('mon_shake') . " ADD  `follow_btn_name` varchar(20) ;");

}



if (!pdo_fieldexists('mon_shake', 'shake_day_limit')) {
    pdo_query("ALTER TABLE " . tablename('mon_shake') . " ADD  `shake_day_limit` int(3) default 0 ;");

}


if (!pdo_fieldexists('mon_shake', 'total_limit')) {
    pdo_query("ALTER TABLE " . tablename('mon_shake') . " ADD  `total_limit` int(3) default 0 ;");

}*/

if (!pdo_fieldexists('mon_qmshake', 'rule')) {
    pdo_query("ALTER TABLE " . tablename('mon_qmshake') . " ADD `rule` varchar(2000) ;");
}


if (!pdo_fieldexists('mon_qmshake_prize', 'p_summary')) {
    pdo_query("ALTER TABLE " . tablename('mon_qmshake_prize') . " ADD `p_summary` varchar(500) ;");
}



if (!pdo_fieldexists('mon_qmshake', 'prize_limit')) {
    pdo_query("ALTER TABLE " . tablename('mon_qmshake') . " ADD `prize_limit` int(3) default 0 ;");
}




if (!pdo_fieldexists('mon_qmshake_record', 'pname')) {
    pdo_query("ALTER TABLE " . tablename('mon_qmshake_record') . " ADD `pname` varchar(200) ;");
}




if (!pdo_fieldexists('mon_qmshake_record', 'djtime')) {
    pdo_query("ALTER TABLE " . tablename('mon_qmshake_record') . " ADD `djtime` int(10) ;");
}


if (!pdo_fieldexists('mon_qmshake', 'dpassword')) {
    pdo_query("ALTER TABLE " . tablename('mon_qmshake') . " ADD `dpassword` varchar(20) ;");
}



if (!pdo_fieldexists('mon_qmshake', 'top_banner_title')) {
    pdo_query("ALTER TABLE " . tablename('mon_qmshake') . " ADD `top_banner_title` varchar(100) ;");
}

if (!pdo_fieldexists('mon_qmshake', 'top_banner_show')) {
    pdo_query("ALTER TABLE " . tablename('mon_qmshake') . " ADD `top_banner_show` int(1) default 0 ;");
}

if (!pdo_fieldexists('mon_qmshake', 'title_bg')) {
    pdo_query("ALTER TABLE " . tablename('mon_qmshake') . " ADD `title_bg` varchar(300);");
}


if (!pdo_fieldexists('mon_qmshake', 'copyright')) {
    pdo_query("ALTER TABLE " . tablename('mon_qmshake') . " ADD `copyright` varchar(50);");
}

if (!pdo_fieldexists('mon_qmshake', 'randking_count')) {
    pdo_query("ALTER TABLE " . tablename('mon_qmshake') . " ADD `randking_count` int(3) ;");
}


if (!pdo_fieldexists('mon_qmshake', 'shake_bg')) {
    pdo_query("ALTER TABLE " . tablename('mon_qmshake') . " ADD `shake_bg` varchar(300) ;");
}

if (!pdo_fieldexists('mon_qmshake', 'index_bg')) {
    pdo_query("ALTER TABLE " . tablename('mon_qmshake') . " ADD `index_bg` varchar(300) ;");
}

