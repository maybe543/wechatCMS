<?php
defined('IN_IA') or exit('Access Denied');
class DBUtil
{
    const TABLE_KANJIA = "bf_kanjia";
    const TABLE_KANJIA_RECORD = "bf_kanjia_record";
    const TABLE_KANJIA_HELP = "bf_kanjia_help";
    const TABLE_KANJIA_ORDER = "bf_kanjia_order";
    const TABLE_KANJIA_SHOP = "bf_kanjia_shop";
    const TABLE_ACCOUNT_USERS = "uni_account_users";
    const TABLE_WECHATS = "account_wechats";
    const TABLE_MODULES = "modules";
    const TABLE_FANS = "mc_mapping_fans";
    const TABLE_USERS_PERMISSION = "users_permission";
    const TABLE_USERS = "users";
    public static function getUsersPermission($where, $params, $op = "AND")
    {
        return pdo_fetch("SELECT * FROM " . tablename(DBUtil::TABLE_USERS_PERMISSION) . " WHERE $where", $params, $op);
    }
    public static function saveUsersPermission($data)
    {
        return pdo_insert(DBUtil::TABLE_USERS_PERMISSION, $data);
    }
    public static function getAccountUsersSelect($select, $where, $params, $op = "AND")
    {
        return pdo_fetchall("SELECT $select FROM " . tablename(DBUtil::TABLE_ACCOUNT_USERS) . " WHERE $where", $params, $op);
    }
    public static function getAccountUsers($where, $params, $op = "AND")
    {
        return pdo_fetch("SELECT * FROM " . tablename(DBUtil::TABLE_ACCOUNT_USERS) . " WHERE $where", $params, $op);
    }
    public static function getUsersCountWhere($where, $params)
    {
        return pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename(DBUtil::TABLE_USERS) . " WHERE $where", $params);
    }
    public static function getUsersWhere($where, $params, $page = 1, $pagesize = "", $op = "AND")
    {
        $sql = "SELECT * FROM " . tablename(DBUtil::TABLE_USERS) . " WHERE $where";
        if (!empty($pagesize)) {
            $sql .= " LIMIT " . ($page - 1) * $pagesize . "," . $pagesize;
        }
        return pdo_fetchall($sql, $params, $op);
    }
    public static function getUser($where, $params, $op = "AND")
    {
        return pdo_fetch("SELECT * FROM " . tablename(DBUtil::TABLE_USERS) . " WHERE $where", $params, $op);
    }
    public static function saveAccountUsers($data)
    {
        return pdo_insert(DBUtil::TABLE_ACCOUNT_USERS, $data);
    }
    public static function getFans($where, $params, $op = "AND")
    {
        return pdo_fetch("SELECT * FROM " . tablename(DBUtil::TABLE_FANS) . " WHERE $where", $params, $op);
    }
    public static function getWechatSelect($select, $where, $params, $op = "AND")
    {
        return pdo_fetch("SELECT $select FROM " . tablename(DBUtil::TABLE_WECHATS) . " WHERE $where", $params, $op);
    }
    public static function getModuleSelect($select, $where, $params, $op = "AND")
    {
        return pdo_fetch("SELECT $select FROM " . tablename(DBUtil::TABLE_MODULES) . " WHERE $where", $params, $op);
    }
    public static function getKanjiaShopWhere($where, $params, $page = 1, $pagesize = "", $op = "AND")
    {
        $sql = "SELECT * FROM " . tablename(DBUtil::TABLE_KANJIA_SHOP) . " WHERE $where";
        if (!empty($pagesize)) {
            $sql .= " LIMIT " . ($page - 1) * $pagesize . "," . $pagesize;
        }
        return pdo_fetchall($sql, $params, $op);
    }
    public static function getKanjiaShopCountWhere($where, $params)
    {
        return pdo_fetchcolumn("SELECT COUNT(id) FROM " . tablename(DBUtil::TABLE_KANJIA_SHOP) . " WHERE $where", $params);
    }
    public static function getKanjiaShop($where, $params, $op = "AND")
    {
        return pdo_fetch("SELECT * FROM " . tablename(DBUtil::TABLE_KANJIA_SHOP) . " WHERE $where", $params, $op);
    }
    public static function saveKanjiaShop($data)
    {
        return pdo_insert(DBUtil::TABLE_KANJIA_SHOP, $data);
    }
    public static function updateKanjiaShop($data, $params, $op = "AND")
    {
        return pdo_update(DBUtil::TABLE_KANJIA_SHOP, $data, $params, $op);
    }
    public static function delKanjiaShop($params, $op = "AND")
    {
        return pdo_delete(DBUtil::TABLE_KANJIA_SHOP, $params, $op);
    }
    public static function getKanjiaOrderWhere($where, $params, $page = 1, $pagesize = "", $op = "AND")
    {
        $sql = "SELECT * FROM " . tablename(DBUtil::TABLE_KANJIA_ORDER) . " WHERE $where";
        if (!empty($pagesize)) {
            $sql .= " LIMIT " . ($page - 1) * $pagesize . "," . $pagesize;
        }
        return pdo_fetchall($sql, $params, $op);
    }
    public static function getKanjiaOrderCountWhere($where, $params)
    {
        return pdo_fetchcolumn("SELECT COUNT(id) FROM " . tablename(DBUtil::TABLE_KANJIA_ORDER) . " WHERE $where", $params);
    }
    public static function getKanjiaOrder($where, $params, $op = "AND")
    {
        return pdo_fetch("SELECT * FROM " . tablename(DBUtil::TABLE_KANJIA_ORDER) . " WHERE $where", $params, $op);
    }
    public static function saveKanjiaOrder($data)
    {
        return pdo_insert(DBUtil::TABLE_KANJIA_ORDER, $data);
    }
    public static function updateKanjiaOrder($data, $params, $op = "AND")
    {
        return pdo_update(DBUtil::TABLE_KANJIA_ORDER, $data, $params, $op);
    }
    public static function delKanjiaOrder($params, $op = "AND")
    {
        return pdo_delete(DBUtil::TABLE_KANJIA_ORDER, $params, $op);
    }
    public static function getKanjiaHelpWithRecordCount($where, $params)
    {
        return pdo_fetchcolumn("SELECT COUNT(a.`id`) FROM " . tablename(DBUtil::TABLE_KANJIA_HELP) . " AS a LEFT JOIN " . tablename(DBUtil::TABLE_KANJIA_RECORD) . " AS b ON a.`rid`=b.`id` WHERE $where", $params);
    }
    public static function getKanjiaHelpSelectWhere($select, $where, $params, $page = 1, $pagesize = "", $op = "AND")
    {
        $sql = "SELECT $select FROM " . tablename(DBUtil::TABLE_KANJIA_HELP) . " WHERE $where";
        if (!empty($pagesize)) {
            $sql .= " LIMIT " . ($page - 1) * $pagesize . "," . $pagesize;
        }
        return pdo_fetchall($sql, $params, $op);
    }
    public static function getKanjiaHelpWhere($where, $params, $page = 1, $pagesize = "", $op = "AND")
    {
        $sql = "SELECT * FROM " . tablename(DBUtil::TABLE_KANJIA_HELP) . " WHERE $where";
        if (!empty($pagesize)) {
            $sql .= " LIMIT " . ($page - 1) * $pagesize . "," . $pagesize;
        }
        return pdo_fetchall($sql, $params, $op);
    }
    public static function getKanjiaHelpCountWhere($where, $params)
    {
        return pdo_fetchcolumn("SELECT COUNT(id) FROM " . tablename(DBUtil::TABLE_KANJIA_HELP) . " WHERE $where", $params);
    }
    public static function getKanjiaHelp($where, $params, $op = "AND")
    {
        return pdo_fetch("SELECT * FROM " . tablename(DBUtil::TABLE_KANJIA_HELP) . " WHERE $where", $params, $op);
    }
    public static function saveKanjiaHelp($data)
    {
        return pdo_insert(DBUtil::TABLE_KANJIA_HELP, $data);
    }
    public static function updateKanjiaHelp($data, $params, $op = "AND")
    {
        return pdo_update(DBUtil::TABLE_KANJIA_HELP, $data, $params, $op);
    }
    public static function delKanjiaHelp($params, $op = "AND")
    {
        return pdo_delete(DBUtil::TABLE_KANJIA_HELP, $params, $op);
    }
    public static function getKanjiaRecordSelectWhere($select, $where, $params, $page = 1, $pagesize = 0, $op = "AND")
    {
        $sql = "SELECT $select FROM " . tablename(DBUtil::TABLE_KANJIA_RECORD) . " WHERE $where";
        if (!empty($pagesize)) {
            $sql .= " LIMIT " . ($page - 1) * $pagesize . "," . $pagesize;
        }
        return pdo_fetchall($sql, $params, $op);
    }
    public static function getKanjiaRecordWhere($where, $params, $page = 1, $pagesize = 0, $op = "AND")
    {
        $sql = "SELECT * FROM " . tablename(DBUtil::TABLE_KANJIA_RECORD) . " WHERE $where";
        if (!empty($pagesize)) {
            $sql .= " LIMIT " . ($page - 1) * $pagesize . "," . $pagesize;
        }
        return pdo_fetchall($sql, $params, $op);
    }
    public static function getKanjiaRecordCountWhere($where, $params, $op = "AND")
    {
        return pdo_fetchcolumn("SELECT COUNT(`id`) FROM " . tablename(DBUtil::TABLE_KANJIA_RECORD) . " WHERE $where", $params);
    }
    public static function getKanjiaRecordSelect($select, $where, $params, $op = "AND")
    {
        return pdo_fetch("SELECT $select FROM " . tablename(DBUtil::TABLE_KANJIA_RECORD) . " WHERE $where", $params, $op);
    }
    public static function getKanjiaRecord($where, $params, $op = "AND")
    {
        return pdo_fetch("SELECT * FROM " . tablename(DBUtil::TABLE_KANJIA_RECORD) . " WHERE $where", $params, $op);
    }
    public static function saveKanjiaRecord($data)
    {
        return pdo_insert(DBUtil::TABLE_KANJIA_RECORD, $data);
    }
    public static function updateKanjiaRecord($data, $params, $op = "AND")
    {
        return pdo_update(DBUtil::TABLE_KANJIA_RECORD, $data, $params, $op);
    }
    public static function delKanjiaRecord($params, $op = "AND")
    {
        return pdo_delete(DBUtil::TABLE_KANJIA_RECORD, $params, $op);
    }
    public static function getKanjiaSelectWhere($select, $where, $params, $page = 1, $pagesize = "", $op = "AND")
    {
        $sql = "SELECT $select FROM " . tablename(DBUtil::TABLE_KANJIA) . " WHERE $where";
        if (!empty($pagesize)) {
            $sql .= " LIMIT " . ($page - 1) * $pagesize . "," . $pagesize;
        }
        return pdo_fetchall($sql, $params, $op);
    }
    public static function getKanjiaCountWhere($where, $params)
    {
        return pdo_fetchcolumn("SELECT COUNT(*) AS `number` FROM " . tablename(DBUtil::TABLE_KANJIA) . " WHERE $where ", $params);
    }
    public static function getKanjiaWhere($where, $params, $page = 1, $pagesize = "", $op = "AND")
    {
        $sql = "SELECT * FROM " . tablename(DBUtil::TABLE_KANJIA) . " WHERE $where";
        if (!empty($pagesize)) {
            $sql .= " LIMIT " . ($page - 1) * $pagesize . "," . $pagesize;
        }
        return pdo_fetchall($sql, $params, $op);
    }
    public static function getKanjiaSelect($select, $where, $params, $op = "AND")
    {
        return pdo_fetch("SELECT $select FROM " . tablename(DBUtil::TABLE_KANJIA) . " WHERE $where", $params, $op);
    }
    public static function getKanjia($where, $params, $op = "AND")
    {
        return pdo_fetch("SELECT * FROM " . tablename(DBUtil::TABLE_KANJIA) . " WHERE $where", $params, $op);
    }
    public static function saveKanjia($data)
    {
        return pdo_insert(DBUtil::TABLE_KANJIA, $data);
    }
    public static function updateKanjia($data, $params, $op = "AND")
    {
        return pdo_update(DBUtil::TABLE_KANJIA, $data, $params, $op);
    }
    public static function delKanjia($params, $op = "AND")
    {
        return pdo_delete(DBUtil::TABLE_KANJIA, $params, $op);
    }
}
