<?php

/**
 * @Project WORK SCHEDULES 4.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sat, 11 Jun 2016 23:45:51 GMT
 */

if (!defined('NV_MOD_WORK_SCHEDULES')) {
    die('Stop!!!');
}

$page_title = $lang_module['mana_pagetitle'];
$key_words = $description = 'no';

if (!defined('NV_IS_MANAGER_ADMIN')) {
    $link = 'javascript:loginForm();';
    $contents = nv_info_theme($lang_module['manager_note'], $lang_module['manager_note2'], $link, 'error');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

// Xóa lịch tuần
if ($nv_Request->isset_request('delete', 'post')) {
    $id = $nv_Request->get_int('id', 'post', 0);
    $listid = $nv_Request->get_string('listid', 'post', '');

    if ($listid != '') {
        $del_array = array_filter(array_unique(array_map('intval', explode(',', $listid))));
    } elseif ($id) {
        $del_array = array($id);
    }

    foreach ($del_array as $id) {
        $sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . $id;
        $id = $db->query($sql)->fetchColumn();

        if (empty($id))
            die('NO_' . $id);

        $sql = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id = ' . $id;
        $db->query($sql);
    }

    $nv_Cache->delMod($module_name);

    include NV_ROOTDIR . '/includes/header.php';
    echo 'OK_' . $id;
    include NV_ROOTDIR . '/includes/footer.php';
}

// Thay đổi trạng thái
if ($nv_Request->isset_request('changestatus', 'post')) {
    $id = $nv_Request->get_int('id', 'post', 0);
    $listid = $nv_Request->get_string('listid', 'post', '');
    $action = $nv_Request->get_string('action', 'post', '');

    if ($listid != '') {
        $del_array = array_filter(array_unique(array_map('intval', explode(',', $listid))));
    } elseif ($id) {
        $del_array = array($id);
    }

    if ($action == 'public') {
        $status = 1;
    } else {
        $status = 0;
    }

    foreach ($del_array as $id) {
        $sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . $id;
        $id = $db->query($sql)->fetchColumn();

        if (empty($id))
            die('NO_' . $id);

        $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET status = ' . $status . ' WHERE id = ' . $id;
        $db->query($sql);
    }

    $nv_Cache->delMod($module_name);

    include NV_ROOTDIR . '/includes/header.php';
    echo $lang_module['actionsuccess'];
    include NV_ROOTDIR . '/includes/footer.php';
}

$per_page = 50;
$page = 1;

if (isset($array_op[1])) {
    if (preg_match("/^page\-([0-9]+)$/", $array_op[1], $m)) {
        $page = intval($m[1]);
    } else {
        header('Location: ' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op, true));
        die();
    }

    if (isset($array_op[2])) {
        header('Location: ' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op, true));
        die();
    }
}

$db->sqlreset()->select('COUNT(*)')->from(NV_PREFIXLANG . '_' . $module_data . '_rows');

$num_items = $db->query($db->sql())->fetchColumn();

$db->select('*')->order('status DESC, id DESC')->limit($per_page)->offset(($page - 1) * $per_page);
$result = $db->query($db->sql());

$array = array();
$array_users = array();

while ($row = $result->fetch()) {
    $row['url_edit'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=add/edit-' . $row['id'];

    $array[] = $row;
    $array_users[$row['post_id']] = array();
}


// Lấy thông tin người đăng ký
if (!empty($array_users)) {
    $sql = 'SELECT userid, username, first_name, last_name FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid IN(' . implode(',', array_keys($array_users)) . ')';
    $result = $db->query($sql);

    while ($row = $result->fetch()) {
        $array_users[$row['userid']] = trim(implode(' ', array($row['last_name'], $row['first_name'])));

        if (empty($array_users[$row['userid']])) {
            $array_users[$row['userid']] = $row['username'];
        }
    }
}

$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
$generate_page = nv_alias_page($page_title, $base_url, $num_items, $per_page, $page);

if ($page > 1) {
    $page_title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_global['page'] . ' ' . $page;

    if (empty($array)) {
        header('Location: ' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op, true));
        die();
    }
}

$array_list_action = array(
    'delete' => $lang_global['delete'],
    'public' => $lang_module['mana_action_public'],
    'stop' => $lang_module['mana_action_stop']
);

$contents = nv_manager_list_theme($array, $array_users, $generate_page, $array_list_action, $array_field_config);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
