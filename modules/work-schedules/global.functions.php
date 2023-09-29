<?php

/**
 * @Project WORK SCHEDULES 4.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sat, 11 Jun 2016 23:45:51 GMT
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$global_array_show_type = [
    'week',
    'all'
];

if ($module_config[$module_name]['auto_delete'] and (empty($module_config[$module_name]['cron_next_check']) or $module_config[$module_name]['cron_next_check'] <= NV_CURRENTTIME)) {
    // Xóa lịch
    $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE e_time<' . (NV_CURRENTTIME - $module_config[$module_name]['auto_delete_time']));

    // Cập nhật CRON
    $config_name = 'cron_next_check';
    $config_value = NV_CURRENTTIME + $module_config[$module_name]['cron_interval'];

    try {
        $sql = 'UPDATE ' . NV_CONFIG_GLOBALTABLE . ' SET config_value=:config_value
        WHERE lang=' . $db->quote(NV_LANG_DATA) . ' AND module=' . $db->quote($module_name) . ' AND config_name=:config_name';
        $sth = $db->prepare($sql);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR);
        $sth->execute();
    } catch (PDOException $e) {
        trigger_error("Error set work-schedules config CRONS!!!", 256);
    }

    $nv_Cache->delMod('settings');
    $nv_Cache->delMod($module_name);
    unset($config_name, $config_value);
}

/**
 * nv_get_week_from_time()
 *
 * @param mixed $time
 * @return
 */
function nv_get_week_from_time($time)
{
    $week = 1;
    $year = date('Y', $time);
    $real_week = [
        $week,
        $year
    ];

    $time_per_week = 86400 * 7;
    $time_start_year = mktime(0, 0, 0, 1, 1, $year);
    $time_first_week = $time_start_year - (86400 * (date('N', $time_start_year) - 1));

    $addYear = true;
    $num_week_loop = nv_get_max_week_of_year($year) - 1;

    for ($i = 0; $i <= $num_week_loop; $i++) {
        $week_begin = $time_first_week + $i * $time_per_week;
        $week_next = $week_begin + $time_per_week;

        if ($week_begin <= $time and $week_next > $time) {
            $real_week[0] = $i + 1;
            $addYear = false;
            break;
        }
    }
    if ($addYear) {
        $real_week[1] = $real_week[1] + 1;
    }

    return $real_week;
}

/**
 * nv_get_max_week_of_year()
 *
 * @param mixed $year
 * @return
 */
function nv_get_max_week_of_year($year)
{
    $time_per_week = 86400 * 7;
    $time_start_year = mktime(0, 0, 0, 1, 1, $year);
    $time_first_week = $time_start_year - (86400 * (date('N', $time_start_year) - 1));

    if (date('Y', $time_first_week + ($time_per_week * 53) - 1) == $year) {
        return 53;
    } else {
        return 52;
    }
}

/**
 * nv_get_display_field_value()
 *
 * @param mixed $field
 * @param mixed $value
 * @return
 */
function nv_get_display_field_value($field, $value)
{
    if (empty($field) or empty($value)) {
        return '';
    }

    if ($field['field_type'] == 'date') {
        $value = nv_date('d/m/Y', intval($value));
    } elseif (in_array($field['field_type'], ['select', 'radio'])) {
        $value = isset($field['field_choices'][$value]) ? $field['field_choices'][$value] : $value;
    } elseif (in_array($field['field_type'], ['checkbox', 'multiselect'])) {
        $value = array_filter(explode(',', $value));
        $values = [];
        foreach ($value as $_value) {
            if (isset($field['field_choices'][$_value])) {
                $values[] = $field['field_choices'][$_value];
            } else {
                $values[] = $_value;
            }
        }
        $value = implode(', ', $values);
    }

    return $value;
}
