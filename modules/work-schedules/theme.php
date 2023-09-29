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

/**
 * nv_main_theme()
 *
 * @param mixed $arrays
 * @param mixed $year
 * @param mixed $week
 * @param mixed $links
 * @param mixed $numqueues
 * @param mixed $cfg
 * @param mixed $fields
 * @return
 */
function nv_main_theme($arrays, $year, $week, $links, $numqueues, $cfg, $fields)
{
    global $lang_module, $module_info, $module_name, $module_config;

    $xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);

    $xtpl->assign('LINK_ADD', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=add');
    $xtpl->assign('LINK_EDIT', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit');
    $xtpl->assign('LINK_MANAGER', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=manager');

    //$xtpl->assign( 'LINK_PRINT', empty( $arrays ) ? 'javascript:void(0)' : $links['print'] );
    //$xtpl->assign( 'LINK_DOWNLOAD', empty( $arrays ) ? 'javascript:void(0)' : $links['download'] );

    $xtpl->assign('LINK_PRINT', "javascript:alert('" . $lang_module['nofeature'] . "');");
    $xtpl->assign('LINK_DOWNLOAD', "javascript:alert('" . $lang_module['nofeature'] . "');");

    if (empty($arrays)) {
        $xtpl->parse('main.empty');
    } else {
        $num_custom_fields = 0;
        foreach ($fields as $field) {
            if (!empty($field['show_website'])) {
                $num_custom_fields++;
                $xtpl->assign('FIELD_TITLE', $field['title']);
                $xtpl->assign('FIELD_DESCRIPTION', $field['description']);

                if (!empty($field['description'])) {
                    $xtpl->parse('main.data.header_field.description');
                }
                $xtpl->parse('main.data.header_field');
            }
        }

        $xtpl->assign('COLSPAN', $num_custom_fields + 2);

        foreach ($arrays as $thisWeek => $array) {
            $firstDay = 0;
            $array_dow = array();
            foreach ($array as $row) {
                $d = date('N', $row['e_time']);
                if (!isset($array_dow[$d])) {
                    $array_dow[$d] = 1;
                } else {
                    $array_dow[$d]++;
                }
            }

            $array_tmp = array();
            foreach ($array as $row) {
                $d = date('N', $row['e_time']);

                $row['etime'] = str_pad($row['e_shour'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($row['e_smin'], 2, '0', STR_PAD_LEFT);

                if ($row['e_ehour'] > -1) {
                    $row['etime'] .= ' - ' . str_pad($row['e_ehour'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($row['e_emin'], 2, '0', STR_PAD_LEFT);
                }
                $row['highlights'] = empty($row['highlights']) ? '' : ' highlights';
                $row['panel_type'] = empty($row['highlights']) ? 'default' : 'success';

                $xtpl->assign('ROW', $row);

                if (!isset($array_tmp[$d])) {
                    $xtpl->assign('DAYOFWEEK', nv_date("l", $row['e_time']));
                    $xtpl->assign('DAYTEXT', nv_date("d/m/Y", $row['e_time']));

                    if ($array_dow[$d] > 1) {
                        $xtpl->assign('NUMROWS', $array_dow[$d]);
                        $xtpl->parse('main.data.loop.first_col.rowspan');
                    }

                    $xtpl->parse('main.data.loop.first_col');
                    $xtpl->parse('main.data.loop_mobile.title');

                    $array_tmp[$d] = true;
                }

                if (defined('NV_IS_MANAGER_ADMIN')) {
                    $xtpl->parse('main.data.loop.edit');
                }

                foreach ($fields as $field) {
                    if (!empty($field['show_website'])) {
                        $xtpl->assign('FIELD_VALUE', nv_get_display_field_value($field, $row[$field['field']]));
                        $xtpl->assign('FIELD_TITLE', $field['title']);
                        $xtpl->parse('main.data.loop.field');

                        if (!empty($field['description'])) {
                            $xtpl->assign('FIELD_DESCRIPTION', $field['description']);
                            $xtpl->parse('main.data.loop_mobile.field.description');
                        }
                        $xtpl->parse('main.data.loop_mobile.field');
                    }
                }

                if ($module_config[$module_name]['show_type'] == 'all' and $firstDay ++ == 0) {
                    $xtpl->assign('THISWEEK', $thisWeek);
                    $xtpl->parse('main.data.loop.week');
                    $xtpl->parse('main.data.loop_mobile.week');
                }

                $xtpl->parse('main.data.loop');
                $xtpl->parse('main.data.loop_mobile');
            }
        }

        $xtpl->parse('main.data');
    }

    // Xuất tuần (Lấy ngày đầu của năm trừ ra)
    $real_week = nv_get_week_from_time(NV_CURRENTTIME);
    $this_year = $real_week[1];
    $time_per_week = 86400 * 7;
    $time_start_year = mktime(0, 0, 0, 1, 1, $year);
    $time_first_week = $time_start_year - (86400 * (date('N', $time_start_year) - 1));

    $have_tool_child = 0;
    if ($module_config[$module_name]['show_type'] == 'week') {
        if (!empty($module_config[$module_name]['show_navweek'])) {
            $have_tool_child++;

            // Thêm tuần cuối năm trước
            $num_week_before = nv_get_max_week_of_year($year - 1);
            $row = array(
                'stt' => $num_week_before,
                'from' => nv_date('d/m/Y', $time_first_week - $time_per_week),
                'to' => nv_date('d/m/Y', $time_first_week - 1),
                'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . change_alias($lang_module['week']) . '-' . $num_week_before . '-' . ($year - 1)
            );

            $xtpl->assign('WEEK', $row);
            $xtpl->parse('main.show_tool.showweek.week');

            // Các tuần trong năm
            $num_week_loop = nv_get_max_week_of_year($year) - 1;
            for ($i = 0; $i <= $num_week_loop; $i++) {
                $row = array(
                    'stt' => $i + 1,
                    'from' => nv_date('d/m/Y', $time_first_week + $i * $time_per_week),
                    'to' => nv_date('d/m/Y', $time_first_week + $i * $time_per_week + $time_per_week - 1),
                    'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . change_alias($lang_module['week']) . '-' . ($i + 1) . ($this_year == $year ? '' : '-' . $year)
                );

                $xtpl->assign('WEEK', $row);

                if ($week == ($i + 1)) {
                    $xtpl->parse('main.show_tool.showweek.week.current');
                }

                $xtpl->parse('main.show_tool.showweek.week');
            }

            // Thêm tuần đầu năm sau
            $num_week = nv_get_max_week_of_year($year);
            $row = array(
                'stt' => 1,
                'from' => nv_date('d/m/Y', $time_first_week + $num_week * $time_per_week),
                'to' => nv_date('d/m/Y', $time_first_week + $num_week * $time_per_week + $time_per_week - 1),
                'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . change_alias($lang_module['week']) . '-1-' . ($year + 1)
            );

            $xtpl->assign('WEEK', $row);
            $xtpl->parse('main.show_tool.showweek.week');

            $current_week = array(
                'stt' => $week,
                'from' => nv_date('d/m/Y', $time_first_week + ($week - 1) * $time_per_week),
                'to' => nv_date('d/m/Y', $time_first_week + ($week - 1) * $time_per_week + $time_per_week - 1),
            );
            $xtpl->assign('CURRENT_WEEK', $current_week);
            $xtpl->parse('main.show_tool.showweek');
        }

        $xtpl->parse('main.showweek_note');
    }

    if (defined('NV_IS_MANAGER_ADMIN')) {
        $xtpl->assign('LANG_ADD', $lang_module['mana_add1']);
    } else {
        $xtpl->assign('LANG_ADD', $lang_module['add']);
    }

    // Xuất nút công cụ
    if (!empty($module_config[$module_name]['show_btntool'])) {
        $have_tool_child++;
        if ($numqueues > 0) {
            $xtpl->assign('NUMQUEUES', $numqueues);
            $xtpl->parse('main.show_tool.showbtn.numqueues');
        }
        $xtpl->parse('main.show_tool.showbtn');
    }

    if ($have_tool_child > 0) {
        $xtpl->parse('main.show_tool');
    }

    // Text tuần từ ngày đến ngày
    $have_text_week = false;
    if (!empty($module_config[$module_name]['show_textweek']) and $module_config[$module_name]['show_type'] == 'week') {
        $xtpl->assign('TEXT_WEEK', sprintf($lang_module['week_from_to'], nv_date('d/m/Y', $time_first_week + ($week - 1) * $time_per_week), nv_date('d/m/Y', $time_first_week + ($week - 1) * $time_per_week + $time_per_week - 1)));
        $xtpl->parse('main.show_textweek');
        $have_text_week = true;
    }
    // Nội dung HTML đầu
    if (!empty($module_config[$module_name]['html_infotop'])) {
        $xtpl->assign('HTML_INFOTOP', $module_config[$module_name]['html_infotop']);
        if (!$have_text_week) {
            $xtpl->parse('main.show_html_infotop.margin_bottom');
        }
        $xtpl->parse('main.show_html_infotop');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_info_theme()
 *
 * @param mixed $title
 * @param mixed $message
 * @param mixed $link
 * @param string $type
 * @return
 */
function nv_info_theme($title, $message, $link, $type = 'info')
{
    global $lang_module, $module_info;

    $xtpl = new XTemplate('info.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('TITLE', $title);
    $xtpl->assign('MESSAGE', $message);
    $xtpl->assign('LINK', $link);

    if ($type == 'error') {
        $xtpl->parse('main.error');
    } else {
        $xtpl->parse('main.info');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_add_theme()
 *
 * @param mixed $array
 * @param mixed $form_action
 * @param mixed $cfg
 * @return
 */
function nv_add_theme($array, $form_action, $cfg, $fields, $custom_fields)
{
    global $lang_module, $module_info, $lang_global, $module_name, $op, $global_array_cat, $global_array_career, $global_array_location, $global_array_salary_type, $global_config;

    $xtpl = new XTemplate('add.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('FORM_ACTION', $form_action);
    $xtpl->assign('DATA', $array);

    if (defined('NV_IS_MANAGER_ADMIN')) {
        $xtpl->assign('INFO', empty($array['id']) ? $lang_module['mana_add_info'] : $lang_module['mana_edit_info']);

        for ($i = 0; $i <= 2; $i++) {
            $status = array(
                'key' => $i,
                'title' => $lang_module['mana_status_' . $i],
                'selected' => $i == $array['status'] ? ' selected="selected"' : '',
                );

            $xtpl->assign('STATUS', $status);
            $xtpl->parse('main.status.loop');
        }

        $xtpl->parse('main.status');

        for ($i = 0; $i <= 1; $i++) {
            $highlights = array(
                'key' => $i,
                'title' => $lang_module['mana_highlights_' . $i],
                'selected' => $i == $array['highlights'] ? ' selected="selected"' : '',
                );

            $xtpl->assign('HIGHLIGHTS', $highlights);
            $xtpl->parse('main.highlights.loop');
        }

        $xtpl->parse('main.highlights');
    } else {
        $xtpl->assign('INFO', $lang_module['ae_info']);

        // Mã bảo mật nếu không là người quản lý
        if ($global_config['captcha_type'] == 2) {
            $xtpl->assign('RECAPTCHA_ELEMENT', 'recaptcha' . nv_genpass(8));
            $xtpl->assign('N_CAPTCHA', $lang_global['securitycode1']);
            $xtpl->parse('main.recaptcha');
        } else {
            $xtpl->assign('N_CAPTCHA', $lang_global['securitycode']);
            $xtpl->assign('CAPTCHA_REFRESH', $lang_global['captcharefresh']);
            $xtpl->assign('GFX_WIDTH', NV_GFX_WIDTH);
            $xtpl->assign('GFX_HEIGHT', NV_GFX_HEIGHT);
            $xtpl->assign('CAPTCHA_REFR_SRC', NV_BASE_SITEURL . NV_FILES_DIR . '/images/refresh.png');
            $xtpl->assign('SRC_CAPTCHA', NV_BASE_SITEURL . 'index.php?scaptcha=captcha&t=' . NV_CURRENTTIME);
            $xtpl->assign('GFX_MAXLENGTH', NV_GFX_NUM);

            $xtpl->parse('main.captcha');
        }

        $xtpl->parse('main.disable_oldday');
    }

    // Xuất giờ
    for ($i = -1; $i <= 23; $i++) {
        $hour = array(
            'key' => $i,
            'title' => $i == -1 ? '--' : str_pad($i, 2, '0', STR_PAD_LEFT),
            'selected_s' => $i == $array['e_shour'] ? ' selected="selected"' : '',
            'selected_e' => $i == $array['e_ehour'] ? ' selected="selected"' : '',
        );

        $xtpl->assign('HOUR', $hour);

        $xtpl->parse('main.shour');
        $xtpl->parse('main.ehour');
    }

    // Xuất phút
    for ($i = -1; $i <= 59; $i++) {
        $min = array(
            'key' => $i,
            'title' => $i == -1 ? '--' : str_pad($i, 2, '0', STR_PAD_LEFT),
            'selected_s' => $i == $array['e_smin'] ? ' selected="selected"' : '',
            'selected_e' => $i == $array['e_emin'] ? ' selected="selected"' : '',
        );

        $xtpl->assign('MIN', $min);

        $xtpl->parse('main.smin');
        $xtpl->parse('main.emin');
    }

    $have_custom_fields = false;

    if (!empty($fields)) {
        foreach ($fields as $_k => $row) {
            $row['customID'] = $_k;
            if ($row['show_addedit']) {
                if (empty($array['id'])) {
                    if (!empty($row['field_choices'])) {
                        if ($row['field_type'] == 'date') {
                            $row['value'] = ($row['field_choices']['current_date']) ? NV_CURRENTTIME : $row['default_value'];
                        } elseif ($row['field_type'] == 'number') {
                            $row['value'] = $row['default_value'];
                        } else {
                            $temp = array_keys($row['field_choices']);
                            $tempkey = intval($row['default_value']) - 1;
                            $row['value'] = (isset($temp[$tempkey])) ? $temp[$tempkey] : '';
                        }
                    } else {
                        $row['value'] = $row['default_value'];
                    }
                } else {
                    $row['value'] = (isset($custom_fields[$row['field']])) ? $custom_fields[$row['field']] : $row['default_value'];
                }
                $row['required'] = ($row['required']) ? 'required' : '';

                $xtpl->assign('FIELD', $row);

                $loop_key = '';
                $is_group_rowf = true;

                if ($row['field_type'] == 'textbox' or $row['field_type'] == 'number') {
                    $loop_key = 'textbox';
                } elseif ($row['field_type'] == 'date') {
                    $row['value'] = (empty($row['value'])) ? '' : date('d/m/Y', $row['value']);
                    $xtpl->assign('FIELD', $row);
                    $loop_key = 'date';
                } elseif ($row['field_type'] == 'textarea') {
                    $row['value'] = nv_htmlspecialchars(nv_br2nl($row['value']));
                    $xtpl->assign('FIELD', $row);
                    $loop_key = 'textarea';
                } elseif ($row['field_type'] == 'editor') {
                    $row['value'] = htmlspecialchars(nv_editor_br2nl($row['value']));
                    if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
                        $array_tmp = explode('@', $row['class']);
                        $edits = nv_aleditor('custom_fields[' . $row['field'] . ']', $array_tmp[0], $array_tmp[1], $row['value']);
                        $xtpl->assign('EDITOR', $edits);
                        $loop_key = 'editor';
                    } else {
                        $row['class'] = '';
                        $xtpl->assign('FIELD', $row);
                        $loop_key = 'textarea';
                    }
                } elseif ($row['field_type'] == 'select') {
                    foreach ($row['field_choices'] as $key => $value) {
                        $xtpl->assign('FIELD_CHOICES', array(
                            'key' => $key,
                            'selected' => ($key == $row['value']) ? ' selected="selected"' : '',
                            'value' => $value
                        ));
                        $xtpl->parse('main.field.loop.select.loop');
                    }
                    $loop_key = 'select';
                } elseif ($row['field_type'] == 'radio') {
                    $number = 0;
                    foreach ($row['field_choices'] as $key => $value) {
                        $xtpl->assign('FIELD_CHOICES', array(
                            'id' => $row['fid'] . '_' . $number++,
                            'key' => $key,
                            'checked' => ($key == $row['value']) ? ' checked="checked"' : '',
                            'value' => $value
                        ));
                        $xtpl->parse('main.field.loop.radio.loop');
                    }
                    $is_group_rowf = false;
                    $loop_key = 'radio';
                } elseif ($row['field_type'] == 'checkbox') {
                    $number = 0;
                    $valuecheckbox = (!empty($row['value'])) ? explode(',', $row['value']) : array();
                    foreach ($row['field_choices'] as $key => $value) {
                        $xtpl->assign('FIELD_CHOICES', array(
                            'id' => $row['fid'] . '_' . $number++,
                            'key' => $key,
                            'checked' => (in_array($key, $valuecheckbox)) ? ' checked="checked"' : '',
                            'value' => $value
                        ));
                        $xtpl->parse('main.field.loop.checkbox.loop');
                    }
                    $is_group_rowf = false;
                    $loop_key = 'checkbox';
                } elseif ($row['field_type'] == 'multiselect') {
                    $valueselect = (!empty($row['value'])) ? explode(',', $row['value']) : array();
                    foreach ($row['field_choices'] as $key => $value) {
                        $xtpl->assign('FIELD_CHOICES', array(
                            'key' => $key,
                            'selected' => (in_array($key, $valueselect)) ? ' selected="selected"' : '',
                            'value' => $value
                        ));
                        $xtpl->parse('main.field.loop.multiselect.loop');
                    }
                    $loop_key = 'multiselect';
                }

                if ($row['required']) {
                    $xtpl->parse('main.field.loop.required');
                }
                if (!empty($row['description'])) {
                    $xtpl->parse('main.field.loop.' . $loop_key . '.description');
                }

                $xtpl->assign('FIELD_GROUPROW_CLASS', $is_group_rowf ? ' class="form-group"' : '');

                $xtpl->parse('main.field.loop.' . $loop_key);
                $xtpl->parse('main.field.loop');

                $have_custom_fields = true;
            }
        }
    }

    if ($have_custom_fields) {
        $xtpl->parse('main.field');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_manager_list_theme()
 *
 * @param mixed $array
 * @param mixed $array_users
 * @param mixed $generate_page
 * @param mixed $array_list_action
 * @param mixed $fields
 * @return
 */
function nv_manager_list_theme($array, $array_users, $generate_page, $array_list_action, $fields)
{
    global $lang_module, $module_info, $lang_global;

    $xtpl = new XTemplate('manager-list.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);

    $num_custom_fields = 0;
    foreach ($fields as $field) {
        if (!empty($field['show_manager'])) {
            $num_custom_fields++;
            $xtpl->assign('FIELD_TITLE', $field['title']);
            $xtpl->assign('FIELD_DESCRIPTION', $field['description']);

            if (!empty($field['description'])) {
                $xtpl->parse('main.header_field.description');
            }
            $xtpl->parse('main.header_field');
        }
    }

    $xtpl->assign('COLSPAN', $num_custom_fields + 4);

    foreach ($array as $row) {
        $row['etime'] = str_pad($row['e_shour'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($row['e_smin'], 2, '0', STR_PAD_LEFT);

        if ($row['e_ehour'] > -1) {
            $row['etime'] .= ' - ' . str_pad($row['e_ehour'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($row['e_emin'], 2, '0', STR_PAD_LEFT);
        }

        $row['e_time'] = nv_date('d/m/Y', $row['e_time']);
        $row['status_text'] = $lang_module['mana_status_' . $row['status']];

        if ($row['status'] == 2) {
            $xtpl->assign('USER', isset($array_users[$row['post_id']]) ? $array_users[$row['post_id']] : $lang_module['mana_unknow_who']);
            $xtpl->parse('main.loop.userreg');
        }

        $xtpl->assign('ROW', $row);

        foreach ($fields as $field) {
            if (!empty($field['show_manager'])) {
                $xtpl->assign('FIELD_VALUE', nv_get_display_field_value($field, $row[$field['field']]));
                $xtpl->parse('main.loop.field');
            }
        }

        $xtpl->parse('main.loop');
    }

    if (!empty($generate_page)) {
        $xtpl->assign('GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.generate_page');
    }

    foreach ($array_list_action as $action_i => $title_i) {
        $action_assign = array('value' => $action_i, 'title' => $title_i);
        $xtpl->assign('ACTION', $action_assign);
        $xtpl->parse('main.action');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}
