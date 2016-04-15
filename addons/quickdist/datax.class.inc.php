<?php
 class QuickDistDataX{
    public function getMemberInfoByLevel1(){
        global $_W;
        xload() -> classs('member');
        $_member = new member();
        $mc = $_member -> getMemberInfoByLevel($_W['weid'], $_W['fans']['from_user'], 1);
        return $this -> genMemberInfoList($mc);
    }
    public function getMemberInfoByLevel2(){
        global $_W;
        xload() -> classs('member');
        $_member = new member();
        $mc = $_member -> getMemberInfoByLevel($_W['weid'], $_W['fans']['from_user'], 2);
        return $this -> genMemberInfoList($mc);
    }
    public function getMemberInfoByLevel3(){
        global $_W;
        xload() -> classs('member');
        $_member = new member();
        $mc = $_member -> getMemberInfoByLevel($_W['weid'], $_W['fans']['from_user'], 3);
        return $this -> genMemberInfoList($mc);
    }
}
