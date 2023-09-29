<!-- BEGIN: main -->
<form method="post" action="{FORM_ACTION}">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading"><strong>{LANG.config_add}</strong></div>
                <div class="panel-body">
                    <ul class="list-none">
                        <!-- BEGIN: group_add --><li><label><input type="checkbox" name="group_add[]" value="{GROUP_ADD.key}"{GROUP_ADD.checked}/> {GROUP_ADD.title}</label></li><!-- END: group_add -->
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading"><strong>{LANG.config_edit}</strong></div>
                <div class="panel-body">
                    <ul class="list-none">
                        <!-- BEGIN: group_edit --><li><label><input type="checkbox" name="group_edit[]" value="{GROUP_EDIT.key}"{GROUP_EDIT.checked}/> {GROUP_EDIT.title}</label></li><!-- END: group_edit -->
                    </ul>
                </div>
            </div>
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