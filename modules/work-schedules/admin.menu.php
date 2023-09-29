<?php

/**
 * @Project WORK SCHEDULES 4.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sat, 11 Jun 2016 23:45:51 GMT
 */

if (!defined('NV_ADMIN'))
    die('Stop!!!');

$submenu['config'] = $lang_module['config'];
$submenu['fields'] = $lang_module['field'];
$submenu['config-sys'] = $lang_module['cfgSYS'];

$allow_func = array('main', 'config', 'fields', 'config-sys');
