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

$page_title = $lang_module['cfgSYS'];

if ($nv_Request->isset_request('submit', 'post')) {
    $array = array();
    $array['show_type'] = $nv_Request->get_title('show_type', 'post', '');
    $array['auto_delete'] = ($nv_Request->get_int('auto_delete', 'post', 0) ? 1 : 0);
    $array['auto_delete_time'] = $nv_Request->get_float('auto_delete_time', 'post', 0);
    $array['cron_interval'] = $nv_Request->get_float('cron_interval', 'post', 0);
    $array['show_navweek'] = ($nv_Request->get_int('show_navweek', 'post', 0) ? 1 : 0);
    $array['show_textweek'] = ($nv_Request->get_int('show_textweek', 'post', 0) ? 1 : 0);
    $array['show_btntool'] = ($nv_Request->get_int('show_btntool', 'post', 0) ? 1 : 0);
    $array['html_infotop'] = nv_editor_nl2br($nv_Request->get_editor('html_infotop', '', NV_ALLOWED_HTML_TAGS));
    
    if (!in_array($array['show_type'], $global_array_show_type)) {
        $array['show_type'] = $global_array_show_type[0];
    }
    
    // Set lại thời gian cron nếu thay đổi
    if (!$array['auto_delete']) {
        $array['cron_next_check'] = 0;
    }
    
    foreach ($array as $config_name => $config_value) {
        $sql = 'UPDATE ' . NV_CONFIG_GLOBALTABLE . ' SET config_value=:config_value 
        WHERE lang=' . $db->quote(NV_LANG_DATA) . ' AND module=' . $db->quote($module_name) . ' AND config_name=:config_name';
        $sth = $db->prepare($sql);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR);
        $sth->execute();
    }

    $nv_Cache->delMod('settings');
    Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
    die();
}

$array = array(
    'show_type' => $module_config[$module_name]['show_type'],
    'auto_delete' => $module_config[$module_name]['auto_delete'],
    'auto_delete_time' => $module_config[$module_name]['auto_delete_time'],
    'cron_interval' => $module_config[$module_name]['cron_interval'],
    'show_navweek' => $module_config[$module_name]['show_navweek'],
    'show_textweek' => $module_config[$module_name]['show_textweek'],
    'show_btntool' => $module_config[$module_name]['show_btntool'],
    'html_infotop' => $module_config[$module_name]['html_infotop']
);

if (defined('NV_EDITOR'))
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';

if (!empty($array['html_infotop']))
    $array['html_infotop'] = nv_htmlspecialchars($array['html_infotop']);

if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $array['html_infotop'] = nv_aleditor('html_infotop', '100%', '300px', $array['html_infotop']);
} else {
    $array['html_infotop'] = '<textarea style="width:100%;height:300px" name="html_infotop" class="form-group">' . $array['html_infotop'] . '</textarea>';
}

$xtpl = new XTemplate('config-sys.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);

$array['auto_delete'] = $array['auto_delete'] ? ' checked="checked"' : '';
$array['show_navweek'] = $array['show_navweek'] ? ' checked="checked"' : '';
$array['show_textweek'] = $array['show_textweek'] ? ' checked="checked"' : '';
$array['show_btntool'] = $array['show_btntool'] ? ' checked="checked"' : '';

$xtpl->assign('DATA', $array);

foreach ($global_array_show_type as $show_type) {
    $show_type = array(
        'key' => $show_type,
        'title' => $lang_module['cfgSYS_show_type_' . $show_type],
        'selected' => $show_type == $array['show_type'] ? ' selected="selected"' : ''
    );
    $xtpl->assign('SHOW_TYPE', $show_type);
    $xtpl->parse('main.show_type');
}

$array_time = array(1, 2, 3, 4, 5, 6, 7, 8, 12, 24, 48);
foreach ($array_time as $time) {
    $time = 86400 * 7 * $time;
    $auto_delete_time = array(
        'key' => $time,
        'title' => nv_convertfromSec($time),
        'selected' => $time == $array['auto_delete_time'] ? ' selected="selected"' : ''
    );
    $xtpl->assign('AUTO_DELETE_TIME', $auto_delete_time);
    $xtpl->parse('main.auto_delete_time');
}

$array_time = array(5, 30, 60, 180, 360, 720, 1440);
foreach ($array_time as $time) {
    $time = 60 * $time;
    $cron_interval = array(
        'key' => $time,
        'title' => nv_convertfromSec($time) . ' ' . $lang_module['cfgSYS_cron_interval_per'],
        'selected' => $time == $array['cron_interval'] ? ' selected="selected"' : ''
    );
    $xtpl->assign('CRON_INTERVAL', $cron_interval);
    $xtpl->parse('main.cron_interval');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
