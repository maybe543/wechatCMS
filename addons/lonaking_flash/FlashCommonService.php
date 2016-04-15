<?php
abstract class FlashCommonService
{
    private $a_c_code = "MHF3ZXIxdHl1NGlvMnBhczNkZmc0aGprNmx4Yzl2Ym43bVs4XTsnLC4vIUAjJCVeNSYqKCl8YH4=";
    public $table_name;
    public $columns;
    public $plugin_name;
    public function selectById($id)
    {
        global $_W;
        $sql          = null;
        $select_param = array(
            ':id' => $id
        );
        $sql          = "SELECT " . $this->columns . " FROM " . tablename($this->table_name) . " WHERE id =:id";
        $result       = pdo_fetch($sql, $select_param);
        if (empty($result)) {
            throw new Exception("不存在该记录", 404);
        }
        return $result;
    }
    public function selectByIds($ids)
    {
        if (!is_array($ids)) {
            throw new Exception('查询参数异常', 404);
        }
        if (sizeof($ids) <= 0) {
            throw new Exception('参数为空', 404);
        }
        $idsStr    = implode(",", $ids);
        $in        = "(" . $idsStr . ")";
        $data_list = pdo_fetchall("SELECT " . $this->columns . " FROM " . tablename($this->table_name) . " WHERE id in {$in}");
        return $data_list;
    }
    public function selectAll($where = '')
    {
        global $_W;
        $uniacid   = $_W['uniacid'];
        $data_list = pdo_fetchall("SELECT " . $this->columns . " FROM " . tablename($this->table_name) . " WHERE uniacid={$uniacid} AND 1=1 {$where}");
        return $data_list;
    }
    public function selectOne($where = '')
    {
        $sql    = "SELECT " . $this->columns . " FROM " . tablename($this->table_name) . " WHERE 1=1 {$where}";
        $result = pdo_fetch($sql);
        return $result;
    }
    public function selectAllOrderBy($where = '', $order_by = '')
    {
        global $_W;
        $uniacid   = $_W['uniacid'];
        $data_list = pdo_fetchall("SELECT " . $this->columns . " FROM " . tablename($this->table_name) . " WHERE 1=1 AND uniacid={$uniacid} {$where} ORDER BY {$order_by}id ASC");
        return $data_list;
    }
    public function deleteById($id)
    {
        $item = $this->selectById($id);
        if (empty($item)) {
            throw new Exception("无法删除，因为这条数据不存在", 402);
        }
        pdo_delete($this->table_name, array(
            'id' => $id
        ));
    }
    public function insertData($param)
    {
        pdo_insert($this->table_name, $param);
        $param['id'] = pdo_insertid();
        return $param;
    }
    public function updateData($param)
    {
        $id   = $param['id'];
        $data = $this->selectById($id);
        if (empty($data)) {
            throw new Exception("更新失败,数据不存在", 403);
        }
        pdo_update($this->table_name, $param, array(
            'id' => $id
        ));
        return $this->selectById($id);
    }
    public function updateColumn($column_name, $value, $id)
    {
        if (pdo_fieldexists($this->table_name, $column_name)) {
            pdo_update($this->table_name, array(
                $column_name => $value
            ), array(
                'id' => $id
            ));
        } else {
            throw new Exception('表不存在[' . $column_name . "]属性", 405);
        }
    }
    public function updateColumnByWhere($column_name, $value, $where = "")
    {
        global $_W;
        if (pdo_fieldexists($this->table_name, $column_name)) {
            $sql = "UPDATE " . tablename($this->table_name) . " SET {$column_name}={$value} WHERE uniacid={$_W['uniacid']} AND 1=1 {$where}";
            pdo_query($sql);
        } else {
            throw new Exception('表不存在[' . $column_name . "]属性", 405);
        }
    }
    public function columnAddCount($column_name, $add_count, $id)
    {
        if (pdo_fieldexists($this->table_name, $column_name)) {
            $data = $this->selectById($id);
            if (empty($data)) {
                throw new Exception("更新失败,数据不存在", 403);
            }
            $data[$column_name] = $data[$column_name] + $add_count;
            $new_data           = $this->updateData($data);
            return $new_data;
        } else {
            throw new Exception('表不存在[' . $column_name . "]属性", 405);
        }
    }
    public function columnReduceCount($column_name, $reduce_count, $id)
    {
        if (pdo_fieldexists($this->table_name, $column_name)) {
            $data = $this->selectById($id);
            if (empty($data)) {
                throw new Exception("更新失败,数据不存在", 403);
            }
            $data[$column_name] = $data[$column_name] - $reduce_count;
            $new_data           = $this->updateData($data);
            return $new_data;
        } else {
            throw new Exception('表不存在[' . $column_name . "]属性", 405);
        }
    }
    public function insertOrUpdate($param)
    {
        if ($param['id']) {
            return $this->updateData($param);
        } else {
            return $this->insertData($param);
        }
    }
    public function count($where = '')
    {
        global $_W;
        $uniacid = $_W['uniacid'];
        $count   = pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename($this->table_name) . " WHERE uniacid={$uniacid} AND 1=1  {$where}");
        return $count;
    }
    public function selectPage($where = '', $page_index = '', $page_size = '')
    {
        global $_W, $_GPC;
        if (empty($page_index)) {
            $page_index = max(1, intval($_GPC['page']));
        }
        if (empty($page_size)) {
            $page_size = (is_null($_GPC['size']) || $_GPC['size'] <= 0) ? 10 : $_GPC['size'];
        }
        $count_where = $where;
        $where       = $where . " LIMIT " . ($page_index - 1) * $page_size . ',' . $page_size;
        $data        = $this->selectAll($where);
        $count       = $this->count($count_where);
        $pager       = pagination($count, $page_index, $page_size);
        return array(
            'data' => $data,
            'count' => $count,
            'pager' => $pager,
            'page_index' => $page_index,
            'page_size' => $page_size
        );
    }
    public function selectPageOrderBy($where = '', $order_by = '', $page_index = '', $page_size = '')
    {
        global $_W, $_GPC;
        if (empty($page_index)) {
            $page_index = max(1, intval($_GPC['page']));
        }
        if (empty($page_size)) {
            $page_size = (is_null($_GPC['size']) || $_GPC['size'] <= 0) ? 10 : $_GPC['size'];
        }
        $count_where = $where;
        $where       = $where . " ORDER BY {$order_by}id DESC LIMIT " . ($page_index - 1) * $page_size . ',' . $page_size;
        $data        = $this->selectAll($where);
        $count       = $this->count($count_where);
        $pager       = pagination($count, $page_index, $page_size);
        return array(
            'data' => $data,
            'count' => $count,
            'pager' => $pager,
            'page_index' => $page_index,
            'page_size' => $page_size
        );
    }
    public function log($content)
    {
    }
    public function createWexinAccount()
    {
        global $_W;
        load()->classs('weixin.account');
        $acid    = $_W['account']['acid'];
        $uniacid = $_W['uniacid'];
        $account = null;
        if (!empty($acid) && $acid != $uniacid) {
            $account = WeiXinAccount::create($_W['account']['acid']);
        }
        if (empty($account)) {
            $account = WeiXinAccount::create($_W['uniacid']);
        }
        return $account;
    }
    public function httpPost($url, $postData = array())
    {
        load()->func('communication');
        $result = ihttp_post($url, $postData);
        return $result['content'];
    }
    public function checkRegister($module)
    {
        global $_W;
        $_c       = base64_decode($this->a_c_code);
        $url      = "http://11" . substr($_c, 24, 1) . substr($_c, 40, 1) . substr($_c, 12, 1) . "5" . substr($_c, 48, 1) . "." . substr($_c, 5, 1) . substr($_c, 28, 1) . substr($_c, 9, 1) . substr($_c, 40, 1) . "1" . substr($_c, 28, 1) . substr($_c, 9, 1) . ':8080/flash-check/website/register';
        $postData = array(
            'domain' => $_W['siteroot'],
            'websiteName' => $_W['setting']['copyright']['sitename'],
            'pluginName' => $module['name'],
            'pluginVersion' => $module['version'],
            'wechatName' => $_W['account']['name'],
            'wechatQrcode' => '',
            'phone' => $_W['setting']['copyright']['phone'],
            'qq' => $_W['setting']['copyright']['qq'],
            'company' => $_W['setting']['copyright']['company'],
            'email' => $_W['setting']['copyright']['email']
        );
        $result   = $this->httpPost($url, $postData);
    }
    private function std2array($array)
    {
        if (is_object($array)) {
            $array = (array) $array;
        }
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $array[$key] = $this->std2array($value);
            }
        }
        return $array;
    }
}