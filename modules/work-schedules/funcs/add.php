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

$key_words = $description = 'no';

if (defined('NV_IS_MANAGER_ADMIN')) {
    $page_title = $lang_module['mana_add'];
} else {
    $page_title = $lang_module['ae_pagetitle'];
}
if (!defined('NV_EDITOR')) {
    define('NV_EDITOR', 'ckeditor');
}
if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

/**
 * add_result()
 *
 * @param mixed $array
 * @return
 */
function add_result($array)
{
    $string = json_encode($array);
    return $string;
}

if (!nv_user_in_groups($module_config[$module_name]['group_add']) and !defined('NV_IS_MANAGER_ADMIN')) {
    $link = 'javascript:loginForm();';
    $contents = nv_info_theme($lang_module['ae_note'], $lang_module['ae_note2'], $link, 'error');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

$id = 0;
$custom_fields = [];
if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

if (isset($array_op[1])) {
    if (preg_match("/^edit\-([0-9]+)$/", $array_op[1], $m) and defined('NV_IS_MANAGER_ADMIN')) {
        $id = intval($m[1]);

        $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id = ' . $id;
        $result = $db->query($sql);
        $array = $result->fetch();

        if (empty($array)) {
            header('Location: ' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true));
            die();
        }

        $array['event_textday'] = nv_date('d/m/Y', $array['e_time']);

        foreach ($array_field_config as $field) {
            $custom_fields[$field['field']] = $array[$field['field']];
        }

        $page_title = $lang_module['mana_edit'];
        $form_action = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '/edit-' . $id;
    } else {
        header('Location: ' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true));
        die();
    }
} else {
    $array = [
        'id' => 0,
        'e_day' => date('j'),
        'e_month' => date('n'),
        'e_week' => date('W'),
        'e_year' => date('Y'),
        'e_time' => 0,
        'e_shour' => -1,
        'e_smin' => -1,
        'e_ehour' => -1,
        'e_emin' => -1,
        'event_textday' => '',
        'status' => defined('NV_IS_MANAGER_ADMIN') ? 1 : 2,
        'highlights' => 0
    ];

    $form_action = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
}

if ($nv_Request->isset_request('event_textday', 'post')) {
    $custom_fields = $nv_Request->get_array('custom_fields', 'post');

    file_put_contents(NV_ROOTDIR . '/log.txt', print_r($custom_fields, true), FILE_APPEND);

    $array['event_textday'] = $nv_Request->get_string('event_textday', 'post', 0);

    $array['e_shour'] = $nv_Request->get_int('e_shour', 'post', -1);
    $array['e_smin'] = $nv_Request->get_int('e_smin', 'post', -1);
    $array['e_ehour'] = $nv_Request->get_int('e_ehour', 'post', -1);
    $array['e_emin'] = $nv_Request->get_int('e_emin', 'post', -1);

    $array['status'] = $nv_Request->get_int('status', 'post', 0);
    $array['highlights'] = $nv_Request->get_int('highlights', 'post', 0);

    if (!defined('NV_IS_MANAGER_ADMIN')) {
        $array['status'] = 2;
        $array['highlights'] = 0;
    }

    // Bắt đầu kiểm tra
    unset($m);
    if (!preg_match("/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/", $array['event_textday'], $m)) {
        die(add_result([
            'status' => 'error',
            'input' => 'event_textday',
            'mess' => $lang_module['ae_error_day1']
        ]));
    }

    if ($array['e_shour'] == -1) {
        die(add_result([
            'status' => 'error',
            'input' => 'e_shour',
            'mess' => $lang_module['ae_error_hour']
        ]));
    }

    if ($array['e_shour'] < 0 or $array['e_shour'] > 23) {
        die(add_result([
            'status' => 'error',
            'input' => 'e_shour',
            'mess' => $lang_module['ae_error_hour1']
        ]));
    }

    if ($array['e_smin'] == -1) {
        die(add_result([
            'status' => 'error',
            'input' => 'e_smin',
            'mess' => $lang_module['ae_error_min']
        ]));
    }

    if ($array['e_smin'] < 0 or $array['e_smin'] > 59) {
        die(add_result([
            'status' => 'error',
            'input' => 'e_smin',
            'mess' => $lang_module['ae_error_min1']
        ]));
    }

    if ($array['e_ehour'] < -1 or $array['e_ehour'] > 23) {
        die(add_result([
            'status' => 'error',
            'input' => 'e_ehour',
            'mess' => $lang_module['ae_error_hour1']
        ]));
    }

    if ($array['e_emin'] < -1 or $array['e_emin'] > 59) {
        die(add_result([
            'status' => 'error',
            'input' => 'e_emin',
            'mess' => $lang_module['ae_error_min1']
        ]));
    }

    $array['e_time'] = mktime($array['e_shour'], $array['e_smin'], 0, $m[2], $m[1], $m[3]);
    $mintime_allow = mktime(0, 0, 0, date('n'), date('j'), date('Y')) + 86400;

    if ($array['e_time'] < $mintime_allow and !defined('NV_IS_MANAGER_ADMIN')) {
        die(add_result([
            'status' => 'error',
            'input' => 'event_textday',
            'mess' => $lang_module['ae_error_day2']
        ]));
    }

    // Kiểm tra các trường dữ liệu
    $query_field = [];
    if (!empty($array_field_config)) {
        require NV_ROOTDIR . '/modules/' . $module_file . '/fields.check.php';
    }

    if ($array['status'] < 0 or $array['status'] > 2) {
        die(add_result([
            'status' => 'error',
            'input' => 'status',
            'mess' => $lang_module['ae_error_status']
        ]));
    }

    // Mã bảo mật
    if (!defined('NV_IS_MANAGER_ADMIN')) {
        if ($global_config['captcha_type'] == 2) {
            $nv_seccode = $nv_Request->get_title('g-recaptcha-response', 'post', '');
        } else {
            $nv_seccode = $nv_Request->get_title('nv_seccode', 'post', '');
        }
        $check_seccode = nv_capcha_txt($nv_seccode) ? true : false;

        if (!$check_seccode) {
            die(add_result([
                'status' => 'error',
                'input' => 'nv_seccode',
                'mess' => ($global_config['captcha_type'] == 2 ? $lang_global['securitycodeincorrect1'] : $lang_global['securitycodeincorrect'])
            ]));
        }
    }

    // Dữ liệu ok, xử lý một chút
    $array['e_day'] = date('j', $array['e_time']);
    $array['e_month'] = date('n', $array['e_time']);
    $array['e_week'] = date('W', $array['e_time']);
    $array['e_year'] = date('Y', $array['e_time']);

    if ($id) {
        $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET
            edittime = ' . NV_CURRENTTIME . ', e_day = :e_day, e_month = :e_month, e_week = :e_week, e_year = :e_year, e_time = :e_time, e_shour = :e_shour,
            e_smin = :e_smin, e_ehour = :e_ehour, e_emin = :e_emin, status = :status, highlights = :highlights';
        foreach ($array_field_config as $field) {
            $sql .= ', ' . $field['field'] . ' = :' . $field['field'];
        }
        $sql .= ' WHERE id = ' . $id;
    } else {
        $sql_key = $sql_value = '';
        foreach ($array_field_config as $field) {
            $sql_key .= ', ' . $field['field'];
            $sql_value .= ', :' . $field['field'];
        }
        $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_rows (
             post_id, addtime, edittime, e_day, e_month, e_week, e_year, e_time, e_shour, e_smin, e_ehour, e_emin, status, highlights' . $sql_key . '
        ) VALUES (
            ' . $user_info['userid'] . ', ' . NV_CURRENTTIME . ', ' . NV_CURRENTTIME . ', :e_day, :e_month, :e_week, :e_year, :e_time, :e_shour, :e_smin, :e_ehour, :e_emin,
            :status, :highlights' . $sql_value . '
        )';
    }

    try {
        $sth = $db->prepare($sql);
        $sth->bindParam(':e_day', $array['e_day'], PDO::PARAM_INT);
        $sth->bindParam(':e_month', $array['e_month'], PDO::PARAM_INT);
        $sth->bindParam(':e_week', $array['e_week'], PDO::PARAM_INT);
        $sth->bindParam(':e_year', $array['e_year'], PDO::PARAM_INT);
        $sth->bindParam(':e_time', $array['e_time'], PDO::PARAM_INT);
        $sth->bindParam(':e_shour', $array['e_shour'], PDO::PARAM_INT);
        $sth->bindParam(':e_smin', $array['e_smin'], PDO::PARAM_INT);
        $sth->bindParam(':e_ehour', $array['e_ehour'], PDO::PARAM_INT);
        $sth->bindParam(':e_emin', $array['e_emin'], PDO::PARAM_INT);

        foreach ($array_field_config as $field) {
            $sth->bindParam(':' . $field['field'], $query_field_values[$field['field']], PDO::PARAM_STR, strlen($query_field_values[$field['field']]));
        }

        $sth->bindParam(':status', $array['status'], PDO::PARAM_INT);
        $sth->bindParam(':highlights', $array['highlights'], PDO::PARAM_INT);
        $sth->execute();

        if ($sth->rowCount()) {
            $nv_Cache->delMod($module_name);

            if ($id) {
                nv_insert_logs(NV_LANG_DATA, $module_name, 'Edit Work: ', $id, $user_info['userid']);
                $redirect = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=manager', true);
                $message = $lang_module['actionsuccess'];
            } elseif (defined('NV_IS_MANAGER_ADMIN')) {
                nv_insert_logs(NV_LANG_DATA, $module_name, 'Add Work: ', $array['e_time'], $user_info['userid']);
                $redirect = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=manager', true);
                $message = $lang_module['actionsuccess'];
            } else {
                nv_insert_logs(NV_LANG_DATA, $module_name, 'Reg Work: ', $array['e_time'], $user_info['userid']);
                $redirect = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true);
                $message = $lang_module['ae_success'];
            }

            die(add_result([
                'status' => 'ok',
                'input' => '',
                'redirect' => $redirect,
                'mess' => $message
            ]));
        }
    } catch (PDOException $e) {
        trigger_error($e->getMessage());
    }

    die(add_result([
        'status' => 'error',
        'input' => '',
        'mess' => $lang_module['errorsave']
    ]));
}

$contents = nv_add_theme($array, $form_action, $module_config[$module_name], $array_field_config, $custom_fields);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
