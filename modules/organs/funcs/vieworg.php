<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 3, 2010  11:32:04 AM
 */

if (!defined('NV_IS_MOD_ORGAN')) die('Stop!!!');

$page_title = $module_info['site_title'];
$key_words = $module_info['keywords'];

$per_page = $arr_config['per_page'];
$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name;

//get pages
$page = 1;
if (isset($array_op[2])) {
    if (preg_match('/^page\-([0-9]{1,10})$/', $array_op[2], $m)) {
        $page = intval($m[1]);
    }
}

//get id
$id = 0;
if (!empty($array_op[1])) {
    $temp = explode('-', $array_op[1]);
    if (!empty($temp)) {
        $id = end($temp);
        $id = intval($id);
    }
}
$person_data = $organs_data = array();

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE organid=' . $id;
$result = $db->query($sql);
$organs_data = $result->fetch();

// thanh dieu huong
$parentid = $organs_data['parentid'];
while ($parentid > 0) {
    $array_cat_i = $global_organ_rows[$parentid];
    $array_mod_title[] = array(
        'catid' => $parentid,
        'title' => $array_cat_i['title'],
        'link' => $array_cat_i['link']
    );
    $parentid = $array_cat_i['parentid'];
}
krsort($array_mod_title, SORT_NUMERIC);

if (empty($organs_data)) {
    $redirect = "<meta http-equiv=\"Refresh\" content=\"3;URL=" . nv_url_rewrite($base_url, true) . "\" />";
    nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] . $redirect);
}

$contents = '';
$base_url .=  "&" . NV_OP_VARIABLE . "=" . $op . "/" . $organs_data['alias'] . "-" . $organs_data['organid'];

$sql = 'SELECT SQL_CALC_FOUND_ROWS * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_person WHERE organid=' . $id . ' AND active=1 ORDER BY weight LIMIT ' . $per_page . ' OFFSET ' . ($page - 1) * $per_page;
$result = $db->query($sql);
$result_all = $db->query("SELECT FOUND_ROWS()");
$numf = $result_all->fetchColumn();
betweenURLs($page, ceil($numf/$per_page), $base_url, '/page-', $prevPage, $nextPage);
$all_page = ($numf) ? $numf : 1;
while ($row = $result->fetch()) {
    if (!empty($row['photo']) and file_exists(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_upload . '/' . $row['photo'])) {
        $row['photo'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $row['photo'];
    } elseif (!empty($row['photo']) and file_exists(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['photo'])) {
        $row['photo'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['photo'];
    } else {
        $row['photo'] = NV_STATIC_URL . 'themes/' . $module_info['template'] . '/images/' . $module_info['module_theme'] . '/no-avatar.jpg';
    }

    $row['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=person/" . $global_organ_rows[$id]['alias'] . "-" . $id . "/" . change_alias($row['name']) . "-" . $row['personid'];
    $person_data[] = $row;
}
$page_url = $base_url;
if ($page > 1) {
    $page_url = $base_url . '/page-' . $page;
}
$canonicalUrl = getCanonicalUrl($page_url);

$html_pages = nv_alias_page($page_title, $base_url, $all_page, $per_page, $page);
if ($arr_config['organ_view_type']) {
    $contents .= vieworg_list($organs_data, $person_data, $html_pages);
} else {
    $contents .= vieworg_gird($organs_data, $person_data, $html_pages);
}

if ($organs_data['numsub'] > 0) {
    $array_content = array();
    $suborg = array();
    $i = 0;
    foreach ($global_organ_rows as $organid => $organinfo) {
        if ($organinfo['parentid'] == $id) {
            $person_data = array();

            // Hien thi phong ban truc thuoc
            $i++;
            $suborg[$i]['link'] = $organinfo['link'];
            $suborg[$i]['title'] = ucwords(mb_strtolower($organinfo['title']));

            //Số nhân sự hiển thị ở tổ chức
            $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_person WHERE organid=' . intval($organinfo['organid']) . ' AND active=1 ORDER BY weight';
            if ($arr_config['per_page_parent'] > 0) {
                $sql .= ' LIMIT ' . $arr_config['per_page_parent'];
            }
            //Hien thi danh sach nhan su
            $result = $db->query($sql);
            while ($row = $result->fetch()) {
                if (!empty($row['photo']) and file_exists(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_upload . '/' . $row['photo'])) {
                    $row['photo'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $row['photo'];
                } elseif (!empty($row['photo']) and file_exists(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['photo'])) {
                    $row['photo'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['photo'];
                } else {
                    $row['photo'] = NV_STATIC_URL . 'themes/' . $module_info['template'] . '/images/' . $module_info['module_theme'] . '/no-avatar.jpg';
                }

                $row['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=person/' . $global_organ_rows[$id]['alias'] . '-' . $id . '/' . change_alias($row['name']) . '-' . $row['personid'];
                $person_data[] = $row;
            }
            $array_content[] = array(
                'id' => $organinfo['organid'],
                'data' => $person_data
            );
            unset($person_data);
        }
    }

    $contents .= vieworg_catelist($array_content, $suborg);
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
