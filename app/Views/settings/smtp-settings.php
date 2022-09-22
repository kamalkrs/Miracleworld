<div class="page-header">
    <h5>SMTP Settings</h5>
</div>
<?php
$arr_default['smtp_host'] = '';
$arr_default['smtp_port'] = '';
$arr_default['smtp_user'] = '';
$arr_default['smtp_pass'] = '';

$_GET['options'] = $options;
$_GET['default'] = $arr_default;

function get_option($fname)
{
    $arr_options = $_GET['options'];
    $arr_default = $_GET['default'];
    if (isset($arr_options[$fname])) {
        return $arr_options[$fname];
    } else {
        if (isset($arr_default[$fname])) {
            return $arr_default[$fname];
        } else {
            return NULL;
        }
    }
}
?>
<form action="" class="px-3" method="post">
    <div class="card card-info">
        <div class="box-p p-4">
            <div class="form-group row">
                <label class="col-sm-2 control-label">SMTP Host</label>
                <div class="col-sm-5">
                    <input type="text" name="smtp_host" value="<?= get_option('smtp_host'); ?>" class="form-control input-sm" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 control-label">SMTP Port</label>
                <div class="col-sm-5">
                    <input type="text" name="smtp_port" value="<?= get_option('smtp_port'); ?>" class="form-control input-sm" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 control-label">SMTP Username</label>
                <div class="col-sm-5">
                    <input type="text" name="smtp_user" value="<?= get_option('smtp_user'); ?>" class="form-control input-sm" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 control-label">SMTP Password</label>
                <div class="col-sm-5">
                    <input type="text" name="smtp_pass" value="<?= get_option('smtp_pass'); ?>" class="form-control input-sm" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2">&nbsp;</label>
                <div class="col-sm-5">
                    <button type="submit" name="submit" value="Save Settings" class="btn btn-primary"><i class="fa fa-save"></i> Save </button>
                    <a href="<?= admin_url('dashboard'); ?>" class="btn btn-secondary"><i class="fa fa-close"></i> Cancel</a>
                </div>
            </div>
        </div>
        <?php
        $str = '';
        if (is_array($arr_default) && count($arr_default) > 0) {
            foreach ($arr_default as $key => $val) {
                $str .= $key . ',';
            }
        }
        $str = rtrim($str, ',');
        ?>
        <input type="hidden" name="fields" value="<?= $str; ?>" />
    </div>
</form>