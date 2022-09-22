<style>
    .alert ul {
        list-style: none;
        margin: 0;
        padding: 0;
    }
</style>
<div id="signup" class="container">
    <div class="row">
        <div class="col-sm-6 m-auto">
            <form action="<?= site_url('signup-verification'); ?>" method="post">
                <div class="login-inner p-4">
                    <?= front_view("alert.php"); ?>
                    <legend>Verification OTP</legend>
                    <input type="text" name="otp_code" required minlength="4" maxlength="4" class="form-control text-center" />
                    <div class="p-4 text-center">
                        <button name="save" value="Save" class="btn btn-lg btn-primary w-50 btn-gradiant">Finish</button>
                    </div>
                    <div class="flogin">
                        OTP Not received ? <a class="d-inline-block ps-2" href="<?= site_url('otp-resent') ?>">Resend</a>
                    </div>
                    <div class="d-flex">
                        <a class="d-inline-block ps-2" href="<?= site_url('signup') ?>"> <i class="fa fa-chevron-left"></i> Go Back</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>