<?php

/**
 * @Project WORK SCHEDULES 4.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @Language Tiếng Việt
 * @License CC BY-SA (http://creativecommons.org/licenses/by-sa/4.0/)
 * @Createdate Sat, 11 Jun 2016 23:45:51 GMT
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$lang_translator['author'] = 'PHAN TAN DUNG <writeblabla@gmail.com>';
$lang_translator['createdate'] = '04/03/2010, 15:22';
$lang_translator['copyright'] = '@Copyright (C) 2016 PHAN TAN DUNG. All rights reserved';
$lang_translator['info'] = '';
$lang_translator['langtype'] = 'lang_module';

$lang_module['add'] = 'Thêm việc làm';
$lang_module['list'] = 'Danh sách việc làm';
$lang_module['edit'] = 'Sửa việc làm';
$lang_module['title'] = 'Tiêu đề';
$lang_module['alias'] = 'Liên kết tĩnh';
$lang_module['errorsave'] = 'Vì một lý do nào đó mà dữ liệu không thể lưu lại được';
$lang_module['function'] = 'Chức năng';
$lang_module['order'] = 'Thứ tự';
$lang_module['status'] = 'Hoạt động';
$lang_module['save'] = 'Lưu lại';
$lang_module['main_note'] = 'Để quản lý lịch, mời bạn nhấp vào đây';

$lang_module['e_element'] = 'Thành phần';
$lang_module['e_location'] = 'Địa điểm';
$lang_module['e_host'] = 'Chủ trì';
$lang_module['e_note'] = 'Ghi chú';

$lang_module['config'] = 'Quyền hạn người dùng';
$lang_module['config_add'] = 'Ai được phép đăng ký lịch';
$lang_module['config_edit'] = 'Ai được phép hiệu chỉnh lịch';

$lang_module['field'] = 'Cấu hình trường dữ liệu';
$lang_module['field_addcaption'] = 'Thêm trường dữ liệu';
$lang_module['field_editcaption'] = 'Sửa trường dữ liệu';
$lang_module['field_edit'] = 'Sửa';
$lang_module['field_choices_empty'] = 'Empty Choice Fields';
$lang_module['field_id'] = 'Trường dữ liệu';
$lang_module['field_id_note'] = 'Trường dữ liệu này cần duy nhất và không thay đổi được khi đã lưu. Chỉ dùng ký tự thường từ a-z và dấu gạch dưới';
$lang_module['field_title'] = 'Tên gọi';
$lang_module['field_description'] = 'Mô tả thêm';
$lang_module['field_required'] = 'Trường dữ liệu bắt buộc';
$lang_module['field_required_note'] = 'Nếu là trường bắt buộc sẽ được hiển thị trong quá trình thêm, sửa và hiệu chỉnh lịch';
$lang_module['field_show_addedit'] = 'Hiển thị khi thêm/sửa lịch';
$lang_module['field_show_website'] = 'Hiển thị ngoài site';
$lang_module['field_show_manager'] = 'Hiển thị ở trang quản lý lịch';
$lang_module['field_type'] = 'Loại dữ liệu';
$lang_module['field_type_number'] = 'Số';
$lang_module['field_type_date'] = 'Ngày';
$lang_module['field_type_textbox'] = 'Một dòng (textbox)';
$lang_module['field_type_textarea'] = 'Nhiều dòng (textarea)';
$lang_module['field_type_editor'] = 'Trình soạn thảo';
$lang_module['field_type_select'] = 'Lựa chọn thả xuống (selectbox)';
$lang_module['field_type_radio'] = 'Một lựa chọn (radio)';
$lang_module['field_type_checkbox'] = 'Nhiều lựa chọn (checkbox)';
$lang_module['field_type_multiselect'] = 'Nhiều lựa chọn thả xuống (multi selectbox)';
$lang_module['field_type_note'] = 'Giá trị sẽ không thay đổi được khi đã lưu';
$lang_module['field_class'] = 'Thuộc tính class html';
$lang_module['field_size'] = 'Kích thước ô nhập liệu';
$lang_module['field_options_text'] = 'Các tùy chọn cho khác';
$lang_module['field_match_type'] = 'Yêu cầu kiểm tra với giá trị:';
$lang_module['field_match_type_none'] = 'Không kiểm tra';
$lang_module['field_match_type_alphanumeric'] = 'Chỉ được dùng các ký tự A-Z, 0-9 và gạch dưới';
$lang_module['field_match_type_date'] = 'Ngày tháng cần nhập theo định dạng dd/mm/yyyy';
$lang_module['field_match_type_url'] = 'Url';
$lang_module['field_match_type_regex'] = 'Biểu thức quy tắc';
$lang_module['field_match_type_callback'] = 'Sử dụng hàm';
$lang_module['field_default_value'] = 'Giá trị mặc định';
$lang_module['field_min_length'] = 'Chiều dài ký tự ít nhất';
$lang_module['field_max_length'] = 'Chiều dài ký tự nhiều nhất';
$lang_module['field_min_value'] = 'Giá trị nhỏ nhất';
$lang_module['field_max_value'] = 'Giá trị lớn nhất';
$lang_module['field_options_number'] = 'Các tùy chọn cho dữ liệu';
$lang_module['field_number_type'] = 'Kiểu số';
$lang_module['field_integer'] = 'Số nguyên';
$lang_module['field_real'] = 'Số thực';
$lang_module['field_options_date'] = 'Các tùy chọn dữ liệu ngày tháng';
$lang_module['field_current_date'] = 'Sử dụng ngày hiện tại';
$lang_module['field_default_date'] = 'Sử dụng ngày';
$lang_module['field_min_date'] = 'Từ ngày';
$lang_module['field_max_date'] = 'Tới ngày';
$lang_module['field_options_choice'] = 'Các tùy chọn';
$lang_module['field_number'] = 'STT';
$lang_module['field_value'] = 'Khóa';
$lang_module['field_text'] = 'Giá trị';
$lang_module['field_add_choice'] = 'Thêm lựa chọn';
$lang_module['field_date_error'] = 'Giá trị của Min Date cần nhỏ hơn Max Date';
$lang_module['field_number_error'] = 'Giá trị của Min Value cần nhỏ hơn Max Value';
$lang_module['field_error_empty'] = 'Trường dữ liệu không được rỗng';
$lang_module['field_error_not_allow'] = 'Trường dữ liệu không được phép sử dụng';
$lang_module['field_error'] = 'Trường dữ liệu đã có';
$lang_module['field_match_type_error'] = '%s không đúng quy tắc';
$lang_module['field_match_type_required'] = '%s bắt buộc nhập';
$lang_module['field_min_max_error'] = '%1$s cần nhập từ %2$s đến %3$s ký tự';
$lang_module['field_min_max_value'] = '%1$s cần nhập từ %2$s đến %3$s';
$lang_module['field_choicetypes_title'] = 'Lựu chọn dữ liệu';
$lang_module['field_choicetypes_sql'] = 'Lấy dữ liệu từ CSDL';
$lang_module['field_choicetypes_text'] = 'Lấy dữ liệu từ nhập liệu';
$lang_module['field_options_choicesql'] = 'Lựa chọn module, bảng dữ liệu và trường dữ liệu';
$lang_module['field_options_choicesql_module'] = 'Chọn module';
$lang_module['field_options_choicesql_table'] = 'Chọn bảng dữ liệu';
$lang_module['field_options_choicesql_column'] = 'Chọn cột dữ liệu';
$lang_module['field_options_choicesql_key'] = 'Chọn cột làm key';
$lang_module['field_options_choicesql_val'] = 'Chọn cột làm value';
$lang_module['field_sql_choices_empty'] = 'Lỗi : Lựa chọn lấy dữ liệu từ CSDL không đầy đủ';

$lang_module['cfgSYS'] = 'Thiết lập hệ thống';
$lang_module['cfgSYS_cron'] = 'Tiến trình tự động';
$lang_module['cfgSYS_theme'] = 'Thiết lập giao diện';
$lang_module['cfgSYS_show_type'] = 'Kiểu hiển thị';
$lang_module['cfgSYS_show_type_week'] = 'Theo tuần';
$lang_module['cfgSYS_show_type_all'] = 'Tất cả';
$lang_module['cfgSYS_show_type_all_note'] = 'Chú ý: Để đảm bảo an toàn cho CSDL cũng như người dùng, nếu chọn kiểu hiển thị lịch là <strong>Tất cả</strong> hãy bật chức năng <strong>Tự động xóa lịch</strong>. Hệ thống sẽ tự động giới hạn hiển thị số mục lịch trong phạm vi 200 mục';
$lang_module['cfgSYS_auto_delete'] = 'Tự động xóa lịch cũ';
$lang_module['cfgSYS_auto_delete_time'] = 'Xóa lịch sau';
$lang_module['cfgSYS_cron_interval'] = 'Tần số kiểm tra xóa';
$lang_module['cfgSYS_cron_interval_per'] = 'một lần';
$lang_module['cfgSYS_cron_interval_note'] = 'Chú ý: Thời gian lặp lại tính từ khi có người truy cập vào khu vực lịch công tác, không phải là thời gian thực của máy chủ';
$lang_module['cfgSYS_show_navweek'] = 'Hiển thị nút chọn tuần ở kiểu lịch tuần';
$lang_module['cfgSYS_show_textweek'] = 'Hiển thị dòng text tuần bên dưới phần HTML đầu';
$lang_module['cfgSYS_show_btntool'] = 'Hiển thị nút công cụ';
$lang_module['cfgSYS_html_infotop'] = 'Nội dung HTML hiển thị ở đầu';
