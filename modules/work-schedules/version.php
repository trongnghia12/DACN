<?php

/**
 * @Project WORK SCHEDULES 4.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sat, 11 Jun 2016 23:45:51 GMT
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$module_version = [
    'name' => 'Work Schedules',
    'modfuncs' => 'main,manager,add',
    'is_sysmod' => 0,
    'virtual' => 1,
    'version' => '4.5.00',
    'date' => 'Saturday, July 2, 2022 2:34:46 PM GMT+07:00',
    'author' => 'PHAN TAN DUNG <writeblabla@gmail.com>',
    'note' => '',
    'uploads_dir' => [
        $module_upload
    ]
];
