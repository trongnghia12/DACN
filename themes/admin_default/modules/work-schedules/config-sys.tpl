<!-- BEGIN: main -->
<form method="post" action="{FORM_ACTION}">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>{LANG.cfgSYS_cron}</strong>
        </div>
        <div class="table-responsive">
            <table class="table">
                <col class="w150"/>
                <tbody>
                    <tr>
                        <td class="form-label"><strong>{LANG.cfgSYS_auto_delete}</strong></td>
                        <td><input type="checkbox" value="1" name="auto_delete"{DATA.auto_delete}/></td>
                    </tr>
                    <tr>
                        <td class="form-label form-label-input-text"><strong>{LANG.cfgSYS_auto_delete_time}</strong></td>
                        <td>
                            <select class="form-control w350" name="auto_delete_time">
                                <!-- BEGIN: auto_delete_time --><option value="{AUTO_DELETE_TIME.key}"{AUTO_DELETE_TIME.selected}>{AUTO_DELETE_TIME.title}</option><!-- END: auto_delete_time -->
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="form-label form-label-input-text"><strong>{LANG.cfgSYS_cron_interval}</strong></td>
                        <td>
                            <select class="form-control w350" name="cron_interval">
                                <!-- BEGIN: cron_interval --><option value="{CRON_INTERVAL.key}"{CRON_INTERVAL.selected}>{CRON_INTERVAL.title}</option><!-- END: cron_interval -->
                            </select>
                            <span class="help-block help-block-no">{LANG.cfgSYS_cron_interval_note}</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>{LANG.cfgSYS_theme}</strong>
        </div>
        <div class="table-responsive">
            <table class="table">
                <col class="w150"/>
                <tbody>
                    <tr>
                        <td class="form-label form-label-input-text"><strong>{LANG.cfgSYS_show_type}</strong></td>
                        <td>
                            <select class="form-control w350" name="show_type">
                                <!-- BEGIN: show_type --><option value="{SHOW_TYPE.key}"{SHOW_TYPE.selected}>{SHOW_TYPE.title}</option><!-- END: show_type -->
                            </select>
                            <span class="help-block help-block-no">{LANG.cfgSYS_show_type_all_note}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="form-label"><strong>{LANG.cfgSYS_show_navweek}</strong></td>
                        <td>
                            <input type="checkbox" value="1" name="show_navweek"{DATA.show_navweek}/>
                        </td>
                    </tr>
                    <tr>
                        <td class="form-label"><strong>{LANG.cfgSYS_show_textweek}</strong></td>
                        <td>
                            <input type="checkbox" value="1" name="show_textweek"{DATA.show_textweek}/>
                        </td>
                    </tr>
                    <tr>
                        <td class="form-label"><strong>{LANG.cfgSYS_show_btntool}</strong></td>
                        <td>
                            <input type="checkbox" value="1" name="show_btntool"{DATA.show_btntool}/>
                        </td>
                    </tr>
                    <tr>
                        <td class="form-label"><strong>{LANG.cfgSYS_html_infotop}</strong></td>
                        <td>
                            {DATA.html_infotop}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-24">
            <div class="text-center">
                <input type="submit" name="submit" value="{LANG.save}" class="btn btn-block btn-success btn-lg"/>
            </div>
        </div>
    </div>
</form>
<!-- END: main -->