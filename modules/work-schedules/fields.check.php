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

if (!isset($query_field_values) or !is_array($query_field_values)) {
    $query_field_values = [];
}

foreach ($array_field_config as $row_f) {
    $value = (isset($custom_fields[$row_f['field']])) ? $custom_fields[$row_f['field']] : '';

    if ($value != '') {
        if ($row_f['field_type'] == 'number') {
            $number_type = $row_f['field_choices']['number_type'];
            $pattern = ($number_type == 1) ? '/^[0-9]+$/' : '/^[0-9\.]+$/';

            if (!preg_match($pattern, $value)) {
                die(json_encode([
                    'status' => 'error',
                    'input' => 'custom_fields[' . $row_f['field'] . ']',
                    'mess' => sprintf($lang_module['field_match_type_error'], $row_f['title'])
                ]));
            } else {
                $value = ($number_type == 1) ? intval($value) : floatval($value);

                if ($value < $row_f['min_length'] or $value > $row_f['max_length']) {
                    die(json_encode([
                        'status' => 'error',
                        'input' => 'custom_fields[' . $row_f['field'] . ']',
                        'mess' => sprintf($lang_module['field_min_max_value'], $row_f['title'], $row_f['min_length'], $row_f['max_length'])
                    ]));
                }
            }
        } elseif ($row_f['field_type'] == 'date') {
            if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $value, $m)) {
                $value = mktime(0, 0, 0, $m[2], $m[1], $m[3]);

                if ($row_f['min_length'] > 0 and ($value < $row_f['min_length'] or $value > $row_f['max_length'])) {
                    die(json_encode([
                        'status' => 'error',
                        'input' => 'custom_fields[' . $row_f['field'] . ']',
                        'mess' => sprintf($lang_module['field_min_max_value'], $row_f['title'], date('d/m/Y', $row_f['min_length']), date('d/m/Y', $row_f['max_length']))
                    ]));
                }
            } else {
                die(json_encode([
                    'status' => 'error',
                    'input' => 'custom_fields[' . $row_f['field'] . ']',
                    'mess' => sprintf($lang_module['field_match_type_error'], $row_f['title'])
                ]));
            }
        } elseif ($row_f['field_type'] == 'textbox') {
            if ($row_f['match_type'] == 'alphanumeric') {
                if (!preg_match('/^[a-zA-Z0-9\_]+$/', $value)) {
                    die(json_encode([
                        'status' => 'error',
                        'input' => 'custom_fields[' . $row_f['field'] . ']',
                        'mess' => sprintf($lang_module['field_match_type_error'], $row_f['title'])
                    ]));
                }
            } elseif ($row_f['match_type'] == 'email') {
                if (($error = nv_check_valid_email($value)) != '') {
                    die(json_encode([
                        'status' => 'error',
                        'input' => 'custom_fields[' . $row_f['field'] . ']',
                        'mess' => $error
                    ]));
                }
            } elseif ($row_f['match_type'] == 'url') {
                if (!nv_is_url($value)) {
                    die(json_encode([
                        'status' => 'error',
                        'input' => 'custom_fields[' . $row_f['field'] . ']',
                        'mess' => sprintf($lang_module['field_match_type_error'], $row_f['title'])
                    ]));
                }
            } elseif ($row_f['match_type'] == 'regex') {
                if (!preg_match('/' . $row_f['match_regex'] . '/', $value)) {
                    die(json_encode([
                        'status' => 'error',
                        'input' => 'custom_fields[' . $row_f['field'] . ']',
                        'mess' => sprintf($lang_module['field_match_type_error'], $row_f['title'])
                    ]));
                }
            } elseif ($row_f['match_type'] == 'callback') {
                if (function_exists($row_f['func_callback'])) {
                    if (!call_user_func($row_f['func_callback'], $value)) {
                        die(json_encode([
                            'status' => 'error',
                            'input' => 'custom_fields[' . $row_f['field'] . ']',
                            'mess' => sprintf($lang_module['field_match_type_error'], $row_f['title'])
                        ]));
                    }
                } else {
                    die(json_encode([
                        'status' => 'error',
                        'input' => 'custom_fields[' . $row_f['field'] . ']',
                        'mess' => 'error function not exists ' . $row_f['func_callback']
                    ]));
                }
            } else {
                $value = nv_htmlspecialchars($value);
            }

            $strlen = nv_strlen($value);

            if ($strlen < $row_f['min_length'] or $strlen > $row_f['max_length']) {
                die(json_encode([
                    'status' => 'error',
                    'input' => 'custom_fields[' . $row_f['field'] . ']',
                    'mess' => sprintf($lang_module['field_min_max_error'], $row_f['title'], $row_f['min_length'], $row_f['max_length'])
                ]));
            }
        } elseif ($row_f['field_type'] == 'textarea' or $row_f['field_type'] == 'editor') {
            $allowed_html_tags = array_map('trim', explode(',', NV_ALLOWED_HTML_TAGS));
            $allowed_html_tags = '<' . implode('><', $allowed_html_tags) . '>';
            $value = strip_tags($value, $allowed_html_tags);
            if ($row_f['match_type'] == 'regex') {
                if (!preg_match('/' . $row_f['match_regex'] . '/', $value)) {
                    die(json_encode([
                        'status' => 'error',
                        'input' => $row_f['field_type'] == 'editor' ? '' : 'custom_fields[' . $row_f['field'] . ']',
                        'mess' => sprintf($lang_module['field_match_type_error'], $row_f['title'])
                    ]));
                }
            } elseif ($row_f['match_type'] == 'callback') {
                if (function_exists($row_f['func_callback'])) {
                    if (!call_user_func($row_f['func_callback'], $value)) {
                        die(json_encode([
                            'status' => 'error',
                            'input' => $row_f['field_type'] == 'editor' ? '' : 'custom_fields[' . $row_f['field'] . ']',
                            'mess' => sprintf($lang_module['field_match_type_error'], $row_f['title'])
                        ]));
                    }
                } else {
                    die(json_encode([
                        'status' => 'error',
                        'input' => $row_f['field_type'] == 'editor' ? '' : 'custom_fields[' . $row_f['field'] . ']',
                        'mess' => 'error function not exists ' . $row_f['func_callback']
                    ]));
                }
            }

            $value = ($row_f['field_type'] == 'textarea') ? nv_nl2br($value, '<br />') : $value;
            $strlen = nv_strlen($value);

            if ($strlen < $row_f['min_length'] or $strlen > $row_f['max_length']) {
                die(json_encode([
                    'status' => 'error',
                    'input' => $row_f['field_type'] == 'editor' ? '' : 'custom_fields[' . $row_f['field'] . ']',
                    'mess' => sprintf($lang_module['field_min_max_error'], $row_f['title'], $row_f['min_length'], $row_f['max_length'])
                ]));
            }
        } elseif ($row_f['field_type'] == 'checkbox' or $row_f['field_type'] == 'multiselect') {
            $temp_value = [];
            foreach ($value as $value_i) {
                if (isset($row_f['field_choices'][$value_i])) {
                    $temp_value[] = $value_i;
                }
            }

            $value = implode(',', $temp_value);
        } elseif ($row_f['field_type'] == 'select' or $row_f['field_type'] == 'radio') {
            if (!isset($row_f['field_choices'][$value])) {
                die(json_encode([
                    'status' => 'error',
                    'input' => 'custom_fields[' . $row_f['field'] . ']',
                    'mess' => sprintf($lang_module['field_match_type_error'], $row_f['title'])
                ]));
            }
        }

        $custom_fields[$row_f['field']] = $value;
    }

    if (empty($value) and $row_f['required']) {
        die(json_encode([
            'status' => 'error',
            'input' => $row_f['field_type'] == 'editor' ? '' : 'custom_fields[' . $row_f['field'] . ']',
            'mess' => sprintf($lang_module['field_match_type_required'], $row_f['title'])
        ]));
    }

    $query_field_values[$row_f['field']] = $value;
}
