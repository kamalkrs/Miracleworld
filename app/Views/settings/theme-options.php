<div class="page-header">
    <h5>Global Settings</h5>
</div>
<?php
$arr_default['logo'] = theme_url('img/logo.png');
$arr_default['title'] = 'Welcome to our website';
$arr_default['message'] = '';
$arr_default['header_block'] = '';
$arr_default['body_start'] = '';
$arr_default['body_end'] = '';
$arr_default['pdf_link'] = '';

$arr_default['address'] = '';
$arr_default['mobile'] = '';
$arr_default['email_id'] = '';

$arr_default['fb_link'] = "";
$arr_default['tw_link'] = "";
$arr_default['rs_link'] = "";
$arr_default['gp_link'] = "";
$arr_default['support_no'] = '1234';
$arr_default['support_email'] = 'info@domain.com';

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

<div class="card card-info">
    <?php echo form_open(admin_url('settings'), array('class' => 'form-horizontal')); ?>
    <div class="box-p">
        <div class="form-group row">
            <label class="col-sm-2 control-label">Logo</label>
            <div class="col-sm-8">
                <input type="text" name="logo" value="<?= get_option('logo'); ?>" class="form-control input-sm" />
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 control-label">Title</label>
            <div class="col-sm-8">
                <input type="text" name="title" value="<?= get_option('title'); ?>" placeholder="Website name" class="form-control input-sm" />
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 control-label">Address</label>
            <div class="col-sm-8">
                <textarea rows="2" name="address" class="form-control input-sm"><?= get_option('address'); ?></textarea>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 control-label">Email Id</label>
            <div class="col-sm-8">
                <input type="text" name="email_id" value="<?= get_option('email_id'); ?>" class="form-control input-sm" />
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 control-label">Mobile</label>
            <div class="col-sm-8">
                <input type="text" name="mobile" value="<?= get_option('mobile'); ?>" class="form-control input-sm" />
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 control-label">Dashboard Message </label>
            <div class="col-sm-8">
                <textarea cols="20" class="form-control" rows="5" name="message"><?= get_option('message'); ?></textarea>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 control-label">PDF Download</label>
            <div class="col-sm-8">
                <input type="text" name="pdf_link" value="<?= get_option('pdf_link'); ?>" placeholder="PDF Plan link" class="form-control input-sm" />
            </div>
        </div>

        <fieldset>
            <legend>Social Sharing</legend>
            <hr>
            <div class="form-group row">
                <label class="col-sm-2 control-label">Facebook </label>
                <div class="col-sm-3">

                    <input type="url" name="fb_link" value="<?= get_option('fb_link'); ?>" placeholder="Facebook Page Url" class="form-control input-sm" />
                </div>
                <label class="col-sm-2 control-label">Twitter </label>
                <div class="col-sm-3">

                    <input type="url" name="tw_link" value="<?= get_option('tw_link'); ?>" placeholder="Twitter Url" class="form-control input-sm" />
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 control-label">LinkedIn </label>
                <div class="col-sm-3">

                    <input type="url" name="rs_link" value="<?= get_option('rs_link'); ?>" placeholder="Linkedin Url" class="form-control input-sm" />
                </div>
                <label class="col-sm-2 control-label">Pinrest </label>
                <div class="col-sm-3">
                    <input type="url" name="gp_link" value="<?= get_option('gp_link'); ?>" placeholder="Pinrest Url" class="form-control input-sm" />
                </div>
            </div>

        </fieldset>

        <fieldset>
            <legend>Meta Settings</legend>
            <hr>
            <div class="form-group row">
                <label class="col-sm-2 control-label">Head Script </label>
                <div class="col-sm-8">
                    <textarea cols="20" class="form-control" rows="2" name="header_block"><?= get_option('header_block'); ?></textarea>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 control-label">After Body Start</label>
                <div class="col-sm-8">
                    <textarea cols="20" class="form-control" rows="2" name="body_start"><?= get_option('body_start'); ?></textarea>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 control-label">Before Body End </label>
                <div class="col-sm-8">
                    <textarea cols="20" class="form-control" rows="2" name="body_end"><?= get_option('body_end'); ?></textarea>
                </div>
            </div>
        </fieldset>

        <div class="form-group row">
            <label class="col-sm-2">&nbsp;</label>
            <div class="col-sm-5">
                <button type="submit" name="submit" value="Save Settings" class="btn btn-primary"><i class="fa fa-save"></i> Save </button>
                <a href="<?= admin_url('settings/restore'); ?>" class="btn btn-secondary reset"><i class="fa fa-close"></i> Restore Default</a>
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
    <?= form_close(); ?>
</div>
<script>
    $(document).ready(function() {
        $('.reset').click(function() {
            if (!confirm('It will RESET all values. Are you sure to proceed?'))
                return false;
        });
    });
</script>