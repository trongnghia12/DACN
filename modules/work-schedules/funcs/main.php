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

$page_title = $module_info['site_title'];
$key_words = $module_info['keywords'];

if (isset($array_op[2])) {
    header('Location: ' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $array_op[0], true));
    die();
}

$is_print = false;
$is_download = false;
$real_week = nv_get_week_from_time(NV_CURRENTTIME);
$week = $real_week[0];
$year = $real_week[1];

$link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;

if (isset($array_op[0])) {
    if ($array_op[0] == 'print') {
        $is_print = true;
    } elseif ($array_op[0] == 'download') {
        $is_download = true;
    } elseif ($module_config[$module_name]['show_type'] == 'week') {
        $valid = false;
        $link .= '&amp;' . NV_OP_VARIABLE . '=';

        if (preg_match("/^" . preg_quote(change_alias($lang_module['week'])) . "\-([0-9]{1,2})$/", $array_op[0], $m)) {
            $m[1] = intval($m[1]);
            $num_week = nv_get_max_week_of_year($year);

            if ($m[1] > 0 and $m[1] < $num_week) {
                $valid = true;
                $week = $m[1];
                $link .= change_alias($lang_module['week']) . '-' . $week;

                $page_title = $lang_module['pagetitle'] . ' ' . $week;
            }
        } elseif (preg_match("/^" . preg_quote(change_alias($lang_module['week'])) . "\-([0-9]{1,2})\-([0-9]{4})$/", $array_op[0], $m)) {
            $m[1] = intval($m[1]);
            $m[2] = intval($m[2]);
            $num_week = nv_get_max_week_of_year($m[2]);

            if ($m[1] > 0 and $m[1] <= $num_week and $m[2] > 1699 and $m[2] < 2101) {
                $valid = true;
                $week = $m[1];
                $year = $m[2];
                $link .= change_alias($lang_module['week']) . '-' . $week . '-' . $year;

                $page_title = $lang_module['pagetitle'] . ' ' . $week . ' ' . $lang_module['year'] . ' ' . $year;
            }
        }

        if ($valid !== true) {
            header('Location: ' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true));
            die();
        }
    } else {
        header('Location: ' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true));
        die();
    }
}

if (isset($array_op[1])) {
    if ($array_op[1] == 'print') {
        $is_print = true;
    } elseif ($array_op[1] == 'download') {
        $is_download = true;
    } else {
        header('Location: ' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $array_op[0], true));
        die();
    }
}

$links = array(
    'print' => $link . '/print',
    'download' => $link . '/download',
);

$array = array();
$time_per_week = 86400 * 7;
$time_start_year = mktime(0, 0, 0, 1, 1, $year);
$time_first_week = $time_start_year - (86400 * (date('N', $time_start_year) - 1));

$week_begin = $time_first_week + ($week - 1) * $time_per_week;
$week_next = $week_begin + $time_per_week;

if ($module_config[$module_name]['show_type'] == 'week') {
    // Lịch công tác theo tuần
    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE e_time >= ' . $week_begin . ' AND e_time < ' . $week_next . ' AND status = 1 ORDER BY e_time ASC';
    $result = $db->query($sql);
} else {
    // Tất cả lịch công tác
    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE status = 1 ORDER BY e_time DESC LIMIT 200';
    $result = $db->query($sql);
}

while ($row = $result->fetch()) {
    $row['url_edit'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=add/edit-' . $row['id'];

    $real_week = nv_get_week_from_time($row['e_time']);
    $array[$real_week[0]][] = $row;
}

if ($module_config[$module_name]['show_type'] != 'week') {
    foreach ($array as $_week => $_weekData) {
        krsort($array[$_week]);
    }
}

if ($is_download) {
    if (!empty($array)) {
        // Include the main TCPDF library (search for installation path).
        require_once (NV_ROOTDIR . '/modules/' . $module_file . '/tcpdf/tcpdf.php');

        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Nicola Asuni');
        $pdf->SetTitle('TCPDF Example 001');
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 001', PDF_HEADER_STRING, array(
            0,
            64,
            255), array(
            0,
            64,
            128));
        $pdf->setFooterData(array(
            0,
            64,
            0), array(
            0,
            64,
            128));

        // set header and footer fonts
        $pdf->setHeaderFont(array(
            PDF_FONT_NAME_MAIN,
            '',
            PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(
            PDF_FONT_NAME_DATA,
            '',
            PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__file__) . '/lang/eng.php')) {
            require_once (dirname(__file__) . '/lang/eng.php');
            $pdf->setLanguageArray($l);
        }

        // ---------------------------------------------------------

        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        // Set font
        // dejavusans is a UTF-8 Unicode font, if you only need to
        // print standard ASCII chars, you can use core fonts like
        // helvetica or times to reduce file size.
        $pdf->SetFont('dejavusans', '', 14, '', true);

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage();

        // set text shadow effect
        $pdf->setTextShadow(array(
            'enabled' => true,
            'depth_w' => 0.2,
            'depth_h' => 0.2,
            'color' => array(
                196,
                196,
                196),
            'opacity' => 1,
            'blend_mode' => 'Normal'));

        // Set some content to print
        $html = '
        <h1>Welcome to <a href="http://www.tcpdf.org" style="text-decoration:none;background-color:#CC0000;color:black;">&nbsp;<span style="color:black;">TC</span><span style="color:white;">PDF</span>&nbsp;</a>!</h1>
        <i>This is the first example of TCPDF library.</i>
        <p>This text is printed using the <i>writeHTMLCell()</i> method but you can also use: <i>Multicell(), writeHTML(), Write(), Cell() and Text()</i>.</p>
        <p>Please check the source code documentation and other examples for further information.</p>
        <p style="color:#CC0000;">TO IMPROVE AND EXPAND TCPDF I NEED YOUR SUPPORT, PLEASE <a href="http://sourceforge.net/donate/index.php?group_id=128076">MAKE A DONATION!</a></p>';

        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

        // ---------------------------------------------------------

        // Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        $pdf->Output('example_001.pdf', 'D');
    } else {
        header('Location: ' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true));
        die();
    }
}

if ($is_print) {
    if (!empty($array)) {
        die('PRINT');
    } else {
        header('Location: ' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true));
        die();
    }
}

$numqueues = 0;
if (defined('NV_IS_MANAGER_ADMIN')) {
    $sql = 'SELECT COUNT(*) numqueue FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE status = 2 OR status = 3';
    $numqueues = $db->query($sql)->fetchColumn();
}

$contents = nv_main_theme($array, $year, $week, $links, $numqueues, $module_config[$module_name], $array_field_config);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
