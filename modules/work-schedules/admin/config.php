<?php

/**
 * @Project WORK SCHEDULES 4.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sat, 11 Jun 2016 23:45:51 GMT
 */

if (!defined('NV_IS_FILE_ADMIN'))
    die('Stop!!!');

$page_title = $lang_module['config'];

$groups_list = nv_groups_list();
$array = array(
    'group_add' => array_map("trim", array_filter(array_unique(explode(',', $module_config[$module_name]['group_add'])))),
    'group_edit' => array_map("trim", array_filter(array_unique(explode(',', $module_config[$module_name]['group_edit']))))
);

if ($nv_Request->isset_request('submit', 'post')) {
    $array['group_add'] = $nv_Request->get_typed_array('group_add', 'post', 'int', array());
    $array['group_edit'] = $nv_Request->get_typed_array('group_edit', 'post', 'int', array());

    $array['group_add'] = $array['group_add'] ? array_intersect($array['group_add'], array_keys($groups_list)) : array();
    $array['group_edit'] = $array['group_edit'] ? array_intersect($array['group_edit'], array_keys($groups_list)) : array();

    $group_add = implode(',', $array['group_add']);
    $group_edit = implode(',', $array['group_edit']);

    $sql = 'UPDATE ' . NV_CONFIG_GLOBALTABLE . ' SET config_value = :config_value WHERE lang = ' . $db->quote(NV_LANG_DATA) . ' AND module = ' . $db->quote($module_name) . ' AND config_name = \'group_add\'';
    $sth = $db->prepare($sql);
    $sth->bindParam(':config_value', $group_add, PDO::PARAM_STR);
    $sth->execute();

    $sql = 'UPDATE ' . NV_CONFIG_GLOBALTABLE . ' SET config_value = :config_value WHERE lang = ' . $db->quote(NV_LANG_DATA) . ' AND module = ' . $db->quote($module_name) . ' AND config_name = \'group_edit\'';
    $sth = $db->prepare($sql);
    $sth->bindParam(':config_value', $group_edit, PDO::PARAM_STR);
    $sth->execute();

    $nv_Cache->delMod('settings');
    Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
    die();
}

$xtpl = new XTemplate('config.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);

foreach ($groups_list as $group_id => $group_title) {
    $group_add = array(
        'key' => $group_id,
        'checked' => in_array($group_id, $array['group_add']) ? ' checked="checked"' : '',
        'title' => $group_title
    );

    $xtpl->assign('GROUP_ADD', $group_add);
    $xtpl->parse('main.group_add');

    $group_edit = array(
        'key' => $group_id,
        'checked' => in_array($group_id, $array['group_edit']) ? ' checked="checked"' : '',
        'title' => $group_title
    );

    $xtpl->assign('GROUP_EDIT', $group_edit);
    $xtpl->parse('main.group_edit');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
