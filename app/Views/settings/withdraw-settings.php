<div class="page-header">
    <h5>Withdrawal Settings</h5>
</div>
<?php
$arr_default['withdraw_min'] = '';
$arr_default['withdraw_fee'] = '';
$arr_default['withdraw_methods'] = '';

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
<div class="row">
    <div class="col-sm-6">
        <form action="" method="post">
            <div class="card card-info">
                <div class="box-p p-4">
                    <div class="form-group row">
                        <label class="col-sm-4 control-label">Min Withdrawal</label>
                        <div class="col-sm-6">
                            <input type="text" name="withdraw_min" value="<?= get_option('withdraw_min'); ?>" class="form-control input-sm" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 control-label">Withdrawal Fee(%)</label>
                        <div class="col-sm-6">
                            <input type="text" name="withdraw_fee" value="<?= get_option('withdraw_fee'); ?>" class="form-control input-sm" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4">&nbsp;</label>
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
    </div>
</div>