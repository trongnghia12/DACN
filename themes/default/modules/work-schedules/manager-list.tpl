<!-- BEGIN: main -->
<div class="h2 mb20">{LANG.mana_pagetitle}</div>
<form class="mb0">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th class="text-center"><input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" /></th>
                    <th class="text-center w100">{LANG.event_time}</th>
                    <!-- BEGIN: header_field -->
                    <th class="text-center">
                        {FIELD_TITLE}
                        <!-- BEGIN: description -->
                        <br />{FIELD_DESCRIPTION}
                        <!-- END: description -->
                    </th>
                    <!-- END: header_field -->
                    <th class="text-center w100">{LANG.mana_status}</th>
                    <th class="text-center w150">{LANG.mana_tools}</th>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: loop -->
                <tr>
                    <td class="text-center"><input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.id}" name="idcheck[]" /></td>
                    <td class="text-center middle">
                        <strong>{ROW.etime}</strong><br />
                        <strong>{ROW.e_time}</strong>
                        <!-- BEGIN: userreg -->
                        <div class="margin-top"><strong>{LANG.mana_reg_user}: {USER}</strong></div>
                        <!-- END: userreg -->
                    </td>
                    <!-- BEGIN: field -->
                    <td>
                        {FIELD_VALUE}
                    </td>
                    <!-- END: field -->
                    <td class="middle text-center">{ROW.status_text}</td>
                    <td class="middle text-center">
                        <a href="{ROW.url_edit}" class="display-inline-block margin-right"><i class="fa fa-edit"></i> {GLANG.edit}</a> 
                        <a href="javascript:void(0);" onclick="nv_del_row('{ROW.id}');" class="display-inline-block"><i class="fa fa-trash"></i> {GLANG.delete}</a>
                    </td>
                </tr>
                <!-- END: loop -->
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="{COLSPAN}" class="form-inline">
                        <select class="form-control mt0" name="action" id="action">
                            <!-- BEGIN: action -->
                            <option value="{ACTION.value}">{ACTION.title}</option>
                            <!-- END: action -->
                        </select>
                        <input type="button" class="btn btn-danger" onclick="nv_main_action(this.form, '{LANG.mgscheck}')" value="{LANG.do}" />
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    <!-- BEGIN: generate_page -->
    <div class="text-center">
        {GENERATE_PAGE}
    </div>
    <!-- END: generate_page -->
</form>
<!-- END: main -->