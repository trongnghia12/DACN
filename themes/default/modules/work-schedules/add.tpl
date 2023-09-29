<!-- BEGIN: main -->
<link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css"/>
<p class="text-right">
    <em>
        <span class="text-danger">(<i class="fa fa-asterisk"></i>)</span> 
        {LANG.ae_id_requires}
    </em>
</p>
<!-- BEGIN: error -->
<div class="message-box-wrapper red">
    <div class="message-box-title">{ERROR}</div>
</div>
<!-- END: error -->
<form method="post" action="{FORM_ACTION}" role="form" onsubmit="return ws_add_validForm(this);" >
    <div class="nv-info margin-bottom" data-default="{INFO}">{INFO}</div>
    <div class="form-detail">
        <div class="form-group">
            <div>
                <label>{LANG.ae_day}<span class="fa-required text-danger">(<i class="fa fa-asterisk"></i>)</span></label>
                <input type="text" name="event_textday" value="{DATA.event_textday}" class="form-control required" placeholder="{LANG.ae_day_note}" maxlength="255" data-pattern="/^([0-9]{2,2})\/([0-9]{2,2})\/([0-9]{4,4})$/" onkeypress="ws_validErrorHidden(this);" data-mess="{LANG.ae_error_day}">
            </div>
        </div>
        <div class="form-group">
            <div>
                <label>{LANG.ae_start}<span class="fa-required text-danger">(<i class="fa fa-asterisk"></i>)</span></label>
                <div class="row clearfix">
                    <div class="col-md-12">
                        <select name="e_shour" class="form-control required" data-empty="-1" data-mess="{LANG.ae_error_hour}" onclick="ws_validErrorHidden(this);">
                            <!-- BEGIN: shour --><option value="{HOUR.key}"{HOUR.selected_s}>{HOUR.title}</option><!-- END: shour -->
                        </select>
                    </div>
                    <div class="col-md-12">
                        <select name="e_smin" class="form-control required" data-empty="-1" data-mess="{LANG.ae_error_min}" onclick="ws_validErrorHidden(this);">
                            <!-- BEGIN: smin --><option value="{MIN.key}"{MIN.selected_s}>{MIN.title}</option><!-- END: smin -->
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div>
                <label>{LANG.ae_end}</label>
                <div class="row clearfix">
                    <div class="col-md-12">
                        <select name="e_ehour" class="form-control">
                            <!-- BEGIN: ehour --><option value="{HOUR.key}"{HOUR.selected_e}>{HOUR.title}</option><!-- END: ehour -->
                        </select>
                    </div>
                    <div class="col-md-12">
                        <select name="e_emin" class="form-control">
                            <!-- BEGIN: emin --><option value="{MIN.key}"{MIN.selected_e}>{MIN.title}</option><!-- END: emin -->
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- BEGIN: field -->
        <!-- BEGIN: loop -->
        <div{FIELD_GROUPROW_CLASS}>
            <div>
                <label>{FIELD.title}<!-- BEGIN: required --><span class="fa-required text-danger">(<i class="fa fa-asterisk"></i>)</span><!-- END: required --></label>
                <!-- BEGIN: textbox -->
                <input type="text" class="form-control {FIELD.required} {FIELD.class}" placeholder="{FIELD.title}" value="{FIELD.value}" name="custom_fields[{FIELD.field}]" onkeypress="ws_validErrorHidden(this);" data-mess=""/>
                <!-- END: textbox -->
                
                <!-- BEGIN: date -->
                <div class="input-group">
                    <input type="text" class="form-control datepicker {FIELD.required} {FIELD.class}" data-provide="datepicker" placeholder="{FIELD.title}" value="{FIELD.value}" name="custom_fields[{FIELD.field}]" readonly="readonly" onchange="ws_validErrorHidden(this);" onfocus="ws_datepickerShow(this);" data-mess=""/>
                    <span class="input-group-addon pointer" onclick="ws_button_datepickerShow(this);">
                        <em class="fa fa-calendar"></em>
                    </span>
                </div>
                <!-- END: date -->
            
                <!-- BEGIN: textarea -->
                <textarea class="form-control {FIELD.required} {FIELD.class}" placeholder="{FIELD.title}" name="custom_fields[{FIELD.field}]" onkeypress="ws_validErrorHidden(this);" data-mess="">{FIELD.value}</textarea>
                <!-- END: textarea -->
            
                <!-- BEGIN: editor -->
                {EDITOR}
                <!-- END: editor -->
            
                <!-- BEGIN: select -->
                <select name="custom_fields[{FIELD.field}]" class="form-control {FIELD.required} {FIELD.class}" onchange="ws_validErrorHidden(this);" data-mess="">
                    <!-- BEGIN: loop -->
                    <option value="{FIELD_CHOICES.key}" {FIELD_CHOICES.selected}>
                        {FIELD_CHOICES.value}
                    </option>
                    <!-- END: loop -->
                </select>
                <!-- END: select -->
            
                <!-- BEGIN: radio -->
                <div class="form-group clearfix radio-box {FIELD.required}" data-mess="">
                    <label for="custom_fields[{FIELD.field}]" class="col-sm-8 control-label {FIELD.required}" title="{FIELD.description}">
                        {FIELD.title}
                    </label>
                    <div class="btn-group col-sm-16">
                        <!-- BEGIN: loop -->
                         <label for="lb_{FIELD_CHOICES.id}" class="radio-box">
                            <input type="radio" name="custom_fields[{FIELD.field}]" value="{FIELD_CHOICES.key}" class="{FIELD.class}" onclick="ws_validErrorHidden(this,5);" {FIELD_CHOICES.checked}>
                            {FIELD_CHOICES.value}
                        </label>
                        <!-- END: loop -->
                    </div>
                </div>
                <!-- END: radio -->
            
                <!-- BEGIN: checkbox -->
                <div class="form-group clearfix check-box {FIELD.required}" data-mess="">
                    <label for="custom_fields[{FIELD.field}]" class="col-sm-8 control-label {FIELD.required}" title="{FIELD.description}">
                        {FIELD.title}
                    </label>
                    <div class="btn-group col-sm-16">
                        <!-- BEGIN: loop -->
                        <label for="lb_{FIELD_CHOICES.id}" class="check-box">
                            <input type="checkbox" name="custom_fields[{FIELD.field}][]" value="{FIELD_CHOICES.key}" class="{FIELD.class}" onclick="ws_validErrorHidden(this,5);" {FIELD_CHOICES.checked}>
                            {FIELD_CHOICES.value}
                        </label>
                        <!-- END: loop -->
                    </div>
                </div>
                <!-- END: checkbox -->
            
                <!-- BEGIN: multiselect -->
                <select name="custom_fields[{FIELD.field}][]" multiple="multiple" class="{FIELD.class} {FIELD.required} form-control" onchange="ws_validErrorHidden(this);" data-mess="">
                    <!-- BEGIN: loop -->
                    <option value="{FIELD_CHOICES.key}" {FIELD_CHOICES.selected}>{FIELD_CHOICES.value}</option>
                    <!-- END: loop -->
                </select>
                <!-- END: multiselect -->
            </div>
        </div>
        <!-- END: loop -->
        <!-- END: field -->

        <!-- BEGIN: status -->
        <div class="form-group">
            <div>
                <label>{LANG.mana_status1}</label>
                <select name="status" class="form-control">
                    <!-- BEGIN: loop --><option value="{STATUS.key}"{STATUS.selected}>{STATUS.title}</option><!-- END: loop -->
                </select>
            </div>
        </div>
        <!-- END: status -->
        <!-- BEGIN: highlights -->
        <div class="form-group">
            <div>
                <label>{LANG.mana_highlights}</label>
                <select name="highlights" class="form-control">
                    <!-- BEGIN: loop --><option value="{HIGHLIGHTS.key}"{HIGHLIGHTS.selected}>{HIGHLIGHTS.title}</option><!-- END: loop -->
                </select>
            </div>
        </div>
        <!-- END: highlights -->
        <!-- BEGIN: captcha -->
        <div class="form-group">
            <div class="middle text-center clearfix">
                <img class="captchaImg display-inline-block" src="{SRC_CAPTCHA}" width="{GFX_WIDTH}" height="{GFX_HEIGHT}" alt="{N_CAPTCHA}" title="{N_CAPTCHA}" />
                <em class="icon-pointer icon-refresh margin-left margin-right" title="{CAPTCHA_REFRESH}" onclick="change_captcha('.rsec');"></em>
                <input type="text" style="width:100px;" class="rsec required form-control display-inline-block" name="nv_seccode" value="" maxlength="{GFX_MAXLENGTH}" placeholder="{GLANG.securitycode}" data-pattern="/^(.){{GFX_MAXLENGTH},{GFX_MAXLENGTH}}$/" onkeypress="ws_validErrorHidden(this);" data-mess="{GLANG.securitycodeincorrect}" />
            </div>
        </div>
        <!-- END: captcha -->
        <!-- BEGIN: recaptcha -->
        <div class="form-group">
            <div class="middle text-center clearfix">
                <label class="control-label">{N_CAPTCHA}</label>
                <div class="nv-recaptcha-default"><div id="{RECAPTCHA_ELEMENT}"></div></div>
                <script type="text/javascript">
                nv_recaptcha_elements.push({
                    id: "{RECAPTCHA_ELEMENT}",
                    btn: $('[type="submit"]', $('#{RECAPTCHA_ELEMENT}').parent().parent().parent().parent())
                })
                </script>
            </div>
        </div>
        <!-- END: recaptcha -->
        <div class="form-group">
            <div class="text-center">
                <input type="submit" name="submit" value="{GLANG.submit}" class="btn btn-warning">
            </div>
        </div>
    </div>
</form>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    $("[name='event_textday']").datepicker({
        dateFormat : "dd/mm/yy",
        showOtherMonths : true,
        selectOtherMonths : true,
        numberOfMonths : 2,
        <!-- BEGIN: disable_oldday -->minDate : 1<!-- END: disable_oldday -->
    });
});
</script>
<!-- END: main -->