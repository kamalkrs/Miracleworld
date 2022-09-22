<div class="page-header">
    <h5>Edit Profile</h5>
</div>
<div class="hgradiant p-3 position-relative logo-box footer-margin">
    <div class="bg-3 p-3 bg-white rounded mybox">
        <form id="fr-register" enctype="multipart/form-data" class="footer-margin" onsubmit="return validate()" method="POST" action="<?= site_url('dashboard/edit-profile/'); ?>">
            <input type="hidden" name="edit">
            <h5>Personal Details</h5>
            <hr>
            <div class="form-group row">
                <label class="col-sm-2 control-label col-form-label">Full name <span class="required">*</span></label>
                <div class="col-sm-3">
                    <input required class="form-control" type="text" name="form[first_name]" value="<?= set_value('form[first_name]', $profile->first_name); ?>">
                </div>
                <label class="col-sm-2 control-label col-form-label">Mobile <span class="required">*</span></label>
                <div class="col-sm-3">
                    <input required class="form-control" type="text" name="form[mobile]" value="<?= set_value('form[mobile]', $profile->mobile); ?>">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 control-label col-form-label">Email Id <span class="required">*</span></label>
                <div class="col-sm-3">
                    <input required class="form-control" type="text" name="form[email_id]" value="<?= set_value('form[email_id]', $profile->email_id); ?>">
                </div>
                <label class="col-sm-2 control-label col-form-label">Wallet Wallet</label>
                <div class="col-sm-4">
                    <input name="form[trc20_adrs]" class="form-control" value="<?= set_value('form[trc20_adrs]', $profile->trc20_adrs); ?>">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2"></label>
                <div class="col-sm-8">
                    <button class="btn btn-success" type="submit" name="submit" value="submit">
                        SAVE
                    </button>
                    <a class="btn btn-dark" href="<?= site_url('dashboard'); ?>">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>