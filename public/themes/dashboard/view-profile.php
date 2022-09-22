<div class="header-back">
    <a href="<?= site_url('dashboard/accounts') ?>">
        <i class="fa fa-chevron-left"></i> Personal Information
    </a>
    <a href="<?= site_url('dashboard/edit-profile') ?>">EDIT</a>
</div>
<div class="hgradiant p-3 position-relative logo-box footer-margin">
    <div class="bg-3 p-3 bg-white rounded mybox">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <span>Ref. Id</span>
            <span><?= $me->username; ?></span>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <span>Name</span>
            <span><?= $me->first_name; ?></span>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <span>Mobile No</span>
            <span><?= $me->country_code . ' ' . $me->mobile; ?></span>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <span>Email Id</span>
            <span><?= $me->email_id; ?></span>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <span>Joining Date</span>
            <span><?= date('d M Y', strtotime($me->join_date)); ?></span>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <span>USDT Address</span>
            <span class="btn-copy" data-copy="<?= $me->trc20_adrs; ?>"><?= $me->trc20_adrs; ?></span>
        </div>
        <div class="d-flex justify-content-between align-items-center ">
            <span>Password</span>
            <a href="<?= site_url('dashboard/change-password'); ?>">Change Password</a>
        </div>
    </div>
</div>