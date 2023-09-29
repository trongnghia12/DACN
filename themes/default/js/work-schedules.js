/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1 - 31 - 2010 5 : 12
 */

function ws_validErrorShow(a) {
    $(a).parent().parent().addClass("has-error");
    $("[data-mess]", $(a).parent().parent().parent()).not(".tooltip-current").tooltip("destroy");
    $(a).tooltip({
       container: "body",
        placement: "bottom",
        title: function() {
            return "" != $(a).attr("data-current-mess") ? $(a).attr("data-current-mess") : nv_required
        }
    });
    $(a).focus().tooltip("show");
    "DIV" == $(a).prop("tagName") && $("input", a)[0].focus()
}

function ws_validCheck(a) {
    var c = $(a).attr("data-pattern"),
        d = $(a).val(),
        b = $(a).prop("tagName"),
        e = $(a).prop("type");
    if ("INPUT" == b && "email" == e) {
        if (!nv_mailfilter.test(d)) return !1;
    } else if ("SELECT" == b) {
        if (typeof $(a).data('empty') != 'undefined' && d == $(a).data('empty')) return !1;
        if (!$("option:selected", a).length) return !1;
    } else if ("DIV" == b && $(a).is(".radio-box")) {
        if (!$("[type=radio]:checked", a).length) return !1;
    } else if ("DIV" == b && $(a).is(".check-box")) {
        if (!$("[type=checkbox]:checked", a).length) return !1;
    } else if ("INPUT" == b || "TEXTAREA" == b) if ("undefined" == typeof c || "" == c) {
        if ("" == d) return !1;
    } else if (a = c.match(/^\/(.*?)\/([gim]*)$/), !(a ? new RegExp(a[1], a[2]) : new RegExp(c)).test(d)) return !1;
    return !0;
}

function ws_validErrorHidden(a, b) {
    if (!b) b = 2;
    b = parseInt(b);
    var c = $(a),
        d = $(a);
    for (var i = 0; i < b; i++) {
        c = c.parent();
        if (i >= 2) d = d.parent()
    }
    d.tooltip("destroy");
    c.removeClass("has-error")
}

function ws_formErrorHidden(a) {
    $(".has-error", a).removeClass("has-error");
    $("[data-mess]", a).tooltip("destroy")
}

function ws_add_validForm(form) {
    // Xử lý các trình soạn thảo
    if (typeof CKEDITOR != "undefined") {
        for (var instanceName in CKEDITOR.instances) {
            $('#' + instanceName).val(CKEDITOR.instances[instanceName].getData());
        }
    }
    $(".has-error", form).removeClass("has-error");
    $(".tooltip-current", form).removeClass("tooltip-current");
    var isError = false,
        formData = [];
    $(form).find(".required").each(function() {
        if ($(this).prop("type") == 'password') {
            $(this).val(trim(strip_tags($(this).val())));
        }
        if (!ws_validCheck(this)) {
            isError = true;
            $(this).addClass("tooltip-current").attr("data-current-mess", $(this).attr("data-mess"));
            ws_validErrorShow(this);
            return false;
        }
    });
    if (!isError) {
        formData.type = $(form).prop("method");
        formData.url = $(form).prop("action");
        formData.data = $(form).serialize();
        ws_formErrorHidden(form);
        $(form).find("input,button,select,textarea").prop("disabled", true);
        $.ajax({
            type: formData.type,
            cache: false,
            url: formData.url,
            data: formData.data,
            dataType: "json",
            success: function(res) {
                var btn = $("[onclick*='change_captcha']", form);
                if (btn.length) {
                    btn.click();
                }
                if (res.status == 'error') {
                    $("input,button,select,textarea", form).not("[type=submit]").prop("disabled", false);
                    $(".tooltip-current", form).removeClass("tooltip-current");
                    if (res.input != '') {
                        $(form).find("[name=\"" + res.input + "\"]").each(function() {
                            $(this).addClass("tooltip-current").attr("data-current-mess", res.mess);
                            ws_validErrorShow(this);
                        });
                    } else {
                        $(".nv-info", form).html(res.mess).addClass("error").show();
                    }
                    setTimeout(function() {
                        $("[type=submit]", form).prop("disabled", false);
                    }, 1000);
                } else {
                    $(".nv-info", form).html(res.mess + '<span class="load-bar"></span>').removeClass("error").addClass("success").show();
                    $(".form-detail", form).hide();
                    setTimeout(function() {
                        window.location.href = "undefined" != typeof res.redirect && "" != res.redirect ? res.redirect : window.location.href;
                    }, 3000);
                }
            }
        });
    }
    return !1
}

function ws_datepickerShow(a) {
    if ("object" == typeof $.datepicker) {
        $(a).datepicker({
            dateFormat: "dd/mm/yy",
            changeMonth: !0,
            changeYear: !0,
            showOtherMonths: !0,
            showOn: "focus",
            yearRange: "-90:+0"
        });
        $(a).css("z-index", "1000").datepicker('show');
    }
}

function ws_button_datepickerShow(a) {
    var b = $(a).parent();
    datepickerShow($(".datepicker", b))
}

function nv_main_action(oForm, msgnocheck) {
    var fa = oForm['idcheck[]'];
    var listid = '';
    if (fa.length) {
        for (var i = 0; i < fa.length; i++) {
            if (fa[i].checked) {
                listid = listid + fa[i].value + ',';
            }
        }
    } else {
        if (fa.checked) {
            listid = listid + fa.value + ',';
        }
    }

    if (listid != '') {
        var action = document.getElementById('action').value;
        if (action == 'delete') {
            if (confirm(nv_is_del_confirm[0])) {
                $.post(
                    nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + nv_func_name + '&nocache=' + new Date().getTime(),
                    'delete=1&listid=' + listid,
                    function(res) {
                        var r_split = res.split("_");
                        if (r_split[0] == 'OK') {
                            window.location.href = window.location.href;
                        } else {
                            alert(nv_is_del_confirm[2]);
                        }
                    }
                );
            }
        } else {
            $.post(
                nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + nv_func_name + '&nocache=' + new Date().getTime(),
                'changestatus=1&action=' + action + '&listid=' + listid,
                function(res) {
                    alert(res);
                    window.location.href = window.location.href;
                }
            );
        }
    } else {
        alert(msgnocheck);
    }
}

function nv_del_row( id ){
    if (confirm(nv_is_del_confirm[0])) {
        $.post(
            nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + nv_func_name + '&nocache=' + new Date().getTime(),
            'delete=1&id=' + id,
            function(res) {
                var r_split = res.split("_");
                if (r_split[0] == 'OK') {
                    window.location.href = window.location.href;
                } else {
                    alert(nv_is_del_confirm[2]);
                }
            }
        );
    }
}

$(document).ready(function(){
    $('#employ-fearch-form').submit(function(e){
        if( $(this).find('[name="q"]').val() == '' ){
            e.preventDefault();
            alert($(this).data('notice'));
        }else{
            return 1;
        }
    });
    // Tooltip
    $('[data-toggle="tooltip-week"]').tooltip({container: 'body'});
    $('[data-toggle="tooltip-week"]').click(function() {
        var value = $(this).data('value');
        var mode = $(this).data('mode');
        $('.schedules-week').each(function(k, v) {
            if ($(this).text() == value) {
                window.location = $($('.schedules-week')[(mode == 'next') ? k + 1 : k - 1]).find('a').prop('href');
                return false;
            }
        });

    });
    // scrollTop dropdown menu
    $('#dropdown-selectweek').on('shown.bs.dropdown', function() {
        var $this = $(this);
        var menu = $('.dropdown-menu', $this);
        var offsetTop = 0;
        $('li.schedules-week', menu).each(function() {
            if ($(this).hasClass('bg-warning')) {
                return false;
            }
            offsetTop += $(this).height();
        });
        menu.scrollTop(offsetTop);
    });
});
