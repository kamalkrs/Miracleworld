<div class="header-back">
    <a href="<?= site_url('dashboard') ?>">
        <i class="fa fa-chevron-left"></i> Verify OTP
    </a>
</div>
<div class="hgradiant p-3 position-relative logo-box footer-margin">
    <div class="bg-3 p-3 bg-white rounded mybox">
        <h3>OTP Verify</h3>
        <div class="p-2">
            <form action="<?= site_url('dashboard/withdraw-otp'); ?>" method="post">
                <div class="box box-info">
                    <div class="box-p">
                        <div class="mb-2">
                            <input type="number" name="otp" placeholder="4 digit OTP Code" class="form-control">
                        </div>
                        <input type="submit" name="submit" value="VERIFY" class="btn btn-primary">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>