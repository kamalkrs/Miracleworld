<div class="page-header">
    <h5>Payout Settings</h5>
</div>
<?php
$arr_default['payout_days'] = '';
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
$days = get_option('payout_days');
if ($days != '') {
    $days = json_decode($days);
}
?>
<div class="row">
    <div class="col-sm-8">
        <form action="" method="post">
            <div class="card card-info">
                <div class="box-p p-4">
                    <div class="form-group row">
                        <label class="col-sm-3 control-label">Payout Days</label>
                        <div class="col-sm-8">
                            <div class="form-check form-check-inline">
                                <label> <input type="checkbox" name="days[]" value="0" class="form-check-input" <?= in_array("0", $days) ? 'checked' : ''; ?> /> SUN</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <label> <input type="checkbox" name="days[]" value="1" class="form-check-input" <?= in_array("1", $days) ? 'checked' : ''; ?> /> MON</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <label> <input type="checkbox" name="days[]" value="2" class="form-check-input" <?= in_array("2", $days) ? 'checked' : ''; ?> /> TUE</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <label> <input type="checkbox" name="days[]" value="3" class="form-check-input" <?= in_array("3", $days) ? 'checked' : ''; ?> /> WED</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <label> <input type="checkbox" name="days[]" value="4" class="form-check-input" <?= in_array("4", $days) ? 'checked' : ''; ?> /> THUR</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <label> <input type="checkbox" name="days[]" value="5" class="form-check-input" <?= in_array("5", $days) ? 'checked' : ''; ?> /> FRI</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <label> <input type="checkbox" name="days[]" value="6" class="form-check-input" <?= in_array("6", $days) ? 'checked' : ''; ?> /> SAT</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3">&nbsp;</label>
                        <div class="col-sm-5">
                            <button type="submit" name="submit" value="Save Settings" class="btn btn-primary"><i class="fa fa-save"></i> Save </button>
                            <a href="<?= admin_url('dashboard'); ?>" class="btn btn-secondary"><i class="fa fa-close"></i> Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>