<?php

/**
 * @Project WORK SCHEDULES 4.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sat, 11 Jun 2016 23:45:51 GMT
 */

if (!defined('NV_SYSTEM')) {
    die('Stop!!!');
}

// Các trường dữ liệu
$array_field_config = [];
$result_field = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_field ORDER BY weight ASC');
while ($row_field = $result_field->fetch()) {
    $language = unserialize($row_field['language']);
    $row_field['title'] = (isset($language[NV_LANG_DATA])) ? $language[NV_LANG_DATA][0] : $row['field'];
    $row_field['description'] = (isset($language[NV_LANG_DATA])) ? nv_htmlspecialchars($language[NV_LANG_DATA][1]) : '';
    if (!empty($row_field['field_choices'])) {
        $row_field['field_choices'] = unserialize($row_field['field_choices']);
    } elseif (!empty($row_field['sql_choices'])) {
        $row_field['sql_choices'] = explode('|', $row_field['sql_choices']);
        $query = 'SELECT ' . $row_field['sql_choices'][2] . ', ' . $row_field['sql_choices'][3] . ' FROM ' . $row_field['sql_choices'][1];
        $result = $db->query($query);
        $weight = 0;
        while (list ($key, $val) = $result->fetch(3)) {
            $row_field['field_choices'][$key] = $val;
        }
    }
    $array_field_config[$row_field['field']] = $row_field;
}

define('NV_MOD_WORK_SCHEDULES', true);

if (defined('NV_IS_SPADMIN')) {
    define('NV_IS_MANAGER_ADMIN', true);
} elseif (defined('NV_IS_USER') and !empty($module_info['admins']) and !empty($user_info['userid']) and in_array($user_info['userid'], explode(',', $module_info['admins']))) {
    define('NV_IS_MANAGER_ADMIN', true);
}

require NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php';
