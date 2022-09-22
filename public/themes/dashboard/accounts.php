<?php

use App\Models\Setting_model;

$config = new \Config\AppConfig;
?>

<div class="hgradiant p-3 position-relative logo-box" style="margin-bottom: 110px;">
    <div class="home-slider1 bg-white p-2 mb-3 pt-5 rounded position-relative">
        <a href="<?= site_url('logout') ?>" class="hrgradiant-reverse log-out pe-1">
            <img src="<?= theme_url('dashboard/icons/logout.png'); ?>" width="28" />
            <span class="d-inline-block ms-1">Log Out</span>
        </a>
        <div class="d-flex mb-3 align-items-center">
            <img src="<?= theme_url('dashboard/img/user.png'); ?>" width="60" />
            <div class="user0 ps-2">
                <h5 class="m-0"><?= $me->first_name . ' ' . $me->last_name; ?></h5>
                <div class="text-muted sponsor-text">Sponsor Code: <?= $me->username; ?></div>
            </div>
        </div>
        <h6 class="mb-1">Main Wallet</h6>
        <div class="wallet-balance mb-3">
            $ <?= $main_balance; ?>
        </div>
        <div class="row gx-2 d-flex">
            <div class="col-6">
                <div class="d-grid">
                    <a href="<?= site_url('dashboard/add-funds'); ?>" class="btn hrgradiant-reverse btn-deposit">Deposit</a>
                </div>
            </div>
            <div class="col-6">
                <div class="d-grid">
                    <a href="<?= site_url('dashboard/withdraw'); ?>" class="btn btn-withdraw">Withdraw</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="px-3 footer-margin">
    <div class="bg-3 p-3 bg-white rounded mybox">
        <h3>My Account</h3>
        <ul class="myaccounts">
            <li><a href="<?= site_url('dashboard/view-profile'); ?>">
                    <img src="<?= theme_url('dashboard/icons/personal.png'); ?>" width="28" />
                    Personal Information</a></li>
            <!-- <li><a href="<?= site_url('dashboard/view-contact'); ?>">
                    <img src="<?= theme_url('dashboard/icons/comm.png'); ?>" width="28" />
                    Communication Details</a></li> -->
            <li><a href="<?= site_url('dashboard/my-ads'); ?>">
                    <img src="<?= theme_url('dashboard/icons/ads-records.png'); ?>" width="28" />
                    Ads Records</a></li>
            <li><a href="<?= site_url('dashboard/withdraw-history'); ?>">
                    <img src="<?= theme_url('dashboard/icons/withdraw.png'); ?>" width="28" />
                    Withdraw Records</a></li>
            <li><a href="<?= site_url('dashboard/deposite-history'); ?>">
                    <img src="<?= theme_url('dashboard/icons/deposite.png'); ?>" width="28" />
                    Deposit Records</a></li>
        </ul>
    </div>
    <div class="bg-3 p-3 bg-white rounded mybox">
        <h3>Invite Link</h3>
        <div class="bg-white">
            <div class="says mb-0">
                <div class="hgradiant sic">
                    <img src="<?= theme_url('dashboard/icons/ui.png'); ?>" width="30" />
                </div>
                <div class="px-0" style="flex: 1;">
                    <input type="text" value="https://www.cryptoads.uk/signup/?ref=<?= $me->username; ?>" class="form-control input-invite">
                </div>
                <div class="hgradiant sic">
                    <img class="btn-copy" data-target="#target" data-copy="https://www.cryptoads.uk/signup/?ref=<?= $me->username; ?>" src="<?= theme_url('dashboard/icons/cc.png'); ?>" width="30" />
                </div>
            </div>
            <div id="target" class="d-flex justify-content-end pt-1"></div>
        </div>
    </div>
</div>