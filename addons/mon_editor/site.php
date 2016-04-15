<?php
/**
 * @author codeMonkey
 * qq:631872807
 */
defined('IN_IA') or exit('Access Denied');
define("MONEDITOR_MODULENAME", "mon_editor");
define("MONEDITOR_RES", "addons/" . MONEDITOR_MODULENAME . "/template/");
class Mon_EditorModuleSite extends WeModuleSite
{
    public function doWebeditor()
    {
        global $_W;

        $site=$_W['siteroot'];

        include $this->template('editor');
    }



    public function doWebindex()
    {

        include $this->template('index');
    }
    


}