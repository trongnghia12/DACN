<?php

/**
 * @Project WORK SCHEDULES 4.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sat, 11 Jun 2016 23:45:51 GMT
 */

if (!defined('NV_IS_FILE_MODULES')) {
    die('Stop!!!');
}

$sql_drop_module = [];
$array_table = [
    'rows',
    'field'
];
$table = $db_config['prefix'] . '_' . $lang . '_' . $module_data;
$result = $db->query('SHOW TABLE STATUS LIKE ' . $db->quote($table . '_%'));
while ($item = $result->fetch()) {
    $name = substr($item['name'], strlen($table) + 1);
    if (preg_match('/^' . $db_config['prefix'] . '\_' . $lang . '\_' . $module_data . '\_/', $item['name']) and (preg_match('/^([0-9]+)$/', $name) or in_array($name, $array_table) or preg_match('/^bodyhtml\_([0-9]+)$/', $name))) {
        $sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $item['name'];
    }
}

$sql_create_module = $sql_drop_module;

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_rows (
 id int(11) unsigned NOT NULL AUTO_INCREMENT,
 correct_id int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Hiệu chỉnh cho',
 correct_userid int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Người hiệu chỉnh',
 post_id mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Người đăng lịch',
 addtime int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Tạo lúc',
 edittime int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Cập nhật lần cuối lúc',
 e_day smallint(2) unsigned NOT NULL DEFAULT '0' COMMENT 'Ngày',
 e_month smallint(2) unsigned NOT NULL DEFAULT '0' COMMENT 'Tháng',
 e_week smallint(2) unsigned NOT NULL DEFAULT '0' COMMENT 'Tuần',
 e_year smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT 'Năm',
 e_time int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'TG bắt đầu kiểu số',
 e_shour smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT 'Giờ bắt đầu',
 e_smin smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT 'Phút bắt đầu',
 e_ehour smallint(4) NOT NULL DEFAULT '-1' COMMENT 'Giờ kết thúc',
 e_emin smallint(4) NOT NULL DEFAULT '-1' COMMENT 'Phút kết thúc',
 status tinyint(4) NOT NULL DEFAULT '1' COMMENT '0: Ẩn, 1: Hiện',
 highlights tinyint(4) NOT NULL DEFAULT '1' COMMENT '0: Bình thường, 1: Nhấn mạnh',
 e_content mediumtext NOT NULL COMMENT 'Nội dung sự kiện',
 e_element mediumtext NOT NULL COMMENT 'Thành phần',
 e_location varchar(255) NOT NULL DEFAULT 'Địa điểm',
 e_host varchar(255) NOT NULL DEFAULT 'Chủ trì',
 e_note mediumtext NOT NULL COMMENT 'Ghi chú',
 PRIMARY KEY (id),
 KEY e_day (e_day),
 KEY e_month (e_month),
 KEY e_week (e_week),
 KEY e_year (e_year),
 KEY e_time (e_time),
 KEY post_id (post_id),
 KEY status (status)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_field (
 fid mediumint(8) NOT NULL AUTO_INCREMENT,
 field varchar(25) NOT NULL,
 weight int(10) unsigned NOT NULL DEFAULT '1',
 field_type enum('number','date','textbox','textarea','editor','select','radio','checkbox','multiselect') NOT NULL DEFAULT 'textbox',
 field_choices text NOT NULL,
 sql_choices text NOT NULL,
 match_type enum('none','alphanumeric','email','url','regex','callback') NOT NULL DEFAULT 'none',
 match_regex varchar(250) NOT NULL DEFAULT '',
 func_callback varchar(75) NOT NULL DEFAULT '',
 min_length int(11) NOT NULL DEFAULT '0',
 max_length bigint(20) unsigned NOT NULL DEFAULT '0',
 required tinyint(3) unsigned NOT NULL DEFAULT '0',
 show_addedit tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Hiển thị tại trang thêm sửa',
 show_website tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Hiển thị cho người xem website',
 show_manager tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Hiển thị ở trang quản lý',
 class varchar(50) NOT NULL DEFAULT '',
 language text NOT NULL,
 default_value varchar(255) NOT NULL DEFAULT '',
 PRIMARY KEY (fid),
 UNIQUE KEY field (field)
) ENGINE=MyISAM";

$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'group_add', '1')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'group_edit', '1')";

$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'show_type', 'week')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'auto_delete', '0')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'auto_delete_time', '604800')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'cron_next_check', '0')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'cron_interval', '300')";

$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'show_navweek', '1')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'show_textweek', '0')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'show_btntool', '1')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'html_infotop', '')";

// Thêm vào bảng field
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_field (fid, field, weight, field_type, field_choices, sql_choices, match_type, match_regex, func_callback, min_length, max_length, required, show_addedit, show_website, show_manager, class, language, default_value) VALUES (1, 'e_content', 1, 'textarea', '', '', 'none', '', '', 0, 65536, 1, 1, 1, 1, '', 'a:1:{s:2:\"vi\";a:2:{i:0;s:10:\"Nội dung\";i:1;s:0:\"\";}}', '');";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_field (fid, field, weight, field_type, field_choices, sql_choices, match_type, match_regex, func_callback, min_length, max_length, required, show_addedit, show_website, show_manager, class, language, default_value) VALUES (2, 'e_element', 2, 'textarea', '', '', 'none', '', '', 0, 65536, 1, 1, 1, 0, '', 'a:1:{s:2:\"vi\";a:2:{i:0;s:13:\"Thành phần\";i:1;s:0:\"\";}}', '');";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_field (fid, field, weight, field_type, field_choices, sql_choices, match_type, match_regex, func_callback, min_length, max_length, required, show_addedit, show_website, show_manager, class, language, default_value) VALUES (3, 'e_location', 3, 'textbox', '', '', 'none', '', '', 0, 255, 1, 1, 1, 0, '', 'a:1:{s:2:\"vi\";a:2:{i:0;s:14:\"Địa điểm\";i:1;s:0:\"\";}}', '');";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_field (fid, field, weight, field_type, field_choices, sql_choices, match_type, match_regex, func_callback, min_length, max_length, required, show_addedit, show_website, show_manager, class, language, default_value) VALUES (4, 'e_host', 4, 'textbox', '', '', 'none', '', '', 0, 255, 1, 1, 1, 0, '', 'a:1:{s:2:\"vi\";a:2:{i:0;s:10:\"Chủ trì\";i:1;s:0:\"\";}}', '');";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_field (fid, field, weight, field_type, field_choices, sql_choices, match_type, match_regex, func_callback, min_length, max_length, required, show_addedit, show_website, show_manager, class, language, default_value) VALUES (5, 'e_note', 5, 'textarea', '', '', 'none', '', '', 0, 65536, 0, 0, 0, 0, '', 'a:1:{s:2:\"vi\";a:2:{i:0;s:8:\"Ghi chú\";i:1;s:0:\"\";}}', '');";
