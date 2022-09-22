<?php

use App\Models\Dashboard_model;

$db = db_connect();
$total_users = $active_users = $inactive_users = $blocked_users = 0;
$today_dep = $today_wdraw = $total_dep = $total_wdraw = 0;

$total_users = (int) $db->table('users')->countAll();
$active_users = (int) $db->table('users')->where(['payout' => 1])->countAllResults();
$inactive_users = (int) $db->table('users')->where(['payout' => 0])->countAllResults();
$blocked_users = (int) $db->table('users')->where(['status' => 2])->countAllResults();

$today_dep = (float) $db->table("payorder")->select("sum(amount) as sum")->where('DATE(created) = CURDATE()')->getWhere(['order_status' => 1])->getRow()->sum;
$total_dep = (float) $db->table("payorder")->select("sum(amount) as sum")->getWhere(['order_status' => 1])->getRow()->sum;

$today_wdraw = (float) $db->table("withdraw")->select("sum(amount) as sum")->where('DATE(created) = CURDATE()')->getWhere(['status' => 1])->getRow()->sum;
$total_wdraw = (float) $db->table("withdraw")->select("sum(amount) as sum")->getWhere(['status' => 1])->getRow()->sum;

?>
<div class="page-header">
    <h5>Dashboard</h5>
</div>
<div class="dashboard">
    <div class="row g-2">
        <div class="col-sm-3">
            <div class="box text-white box-grad1 border-0">
                <div class="p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Total Users</h6>
                        <h2 class="m-0"><?= $total_users; ?></h2>
                    </div>
                    <div>
                        <i class="fa fa-users fa-3x"></i>
                    </div>
                </div>
                <div class="box-footer p-2 box-footer-dark">
                    <a href="<?= admin_url('members') ?>" class="btn btn-sm btn-outline-light">View More</a>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="box  box-grad2 border-0">
                <div class="p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Payment Active</h6>
                        <h2 class="m-0"><?= $active_users; ?></h2>
                    </div>
                    <div>
                        <i class="fa fa-users fa-3x"></i>
                    </div>
                </div>
                <div class="box-footer p-2 box-footer-dark">
                    <a href="<?= admin_url('members/?payout=1') ?>" class="btn btn-sm btn-outline-light">View More</a>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="box text-white box-grad1 border-0">
                <div class="p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Payment In-Active</h6>
                        <h2 class="m-0"><?= $inactive_users; ?></h2>
                    </div>
                    <div>
                        <i class="fa fa-bank fa-3x"></i>
                    </div>
                </div>
                <div class="box-footer p-2 box-footer-dark">
                    <a href="<?= admin_url('members/?payout=0') ?>" class="btn btn-sm btn-outline-light">View More</a>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="box  box-grad2 border-0">
                <div class="p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Blocked User</h6>
                        <h2 class="m-0"><?= $blocked_users; ?></h2>
                    </div>
                    <div>
                        <i class="mdi mdi-wallet fa-3x"></i>
                    </div>
                </div>
                <div class="box-footer p-2 box-footer-dark">
                    <a href="<?= admin_url('members/?status=2') ?>" class="btn btn-sm btn-outline-light">View More</a>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="box text-white box-grad1 border-0">
                <div class="p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Today Deposit</h6>
                        <h2 class="m-0"><?= $today_dep; ?></h2>
                    </div>
                    <div>
                        <i class="mdi mdi-transfer fa-3x"></i>
                    </div>
                </div>
                <div class="box-footer p-2 box-footer-dark">
                    <a href="<?= admin_url('payments/payment-history/?today=1') ?>" class="btn btn-sm btn-outline-light">View More</a>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="box box-grad2 border-0">
                <div class="p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Today Withdrawal</h6>
                        <h2 class="m-0"><?= $today_wdraw; ?></h2>
                    </div>
                    <div>
                        <i class="mdi mdi-wallet fa-3x"></i>
                    </div>
                </div>
                <div class="box-footer p-2 box-footer-dark">
                    <a href="<?= admin_url('payout/withdrawal/?today=1') ?>" class="btn btn-sm btn-outline-light">View More</a>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="box text-white box-grad1 border-0">
                <div class="p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Total Deposit</h6>
                        <h2 class="m-0"><?= $total_dep; ?></h2>
                    </div>
                    <div>
                        <i class="mdi mdi-wallet fa-3x"></i>
                    </div>
                </div>
                <div class="box-footer p-2 box-footer-dark">
                    <a href="<?= admin_url('payments/payment-history') ?>" class="btn btn-sm btn-outline-light">View More</a>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="box box-grad2 border-0">
                <div class="p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Total Withdrawal</h6>
                        <h2 class="m-0"><?= $total_wdraw; ?></h2>
                    </div>
                    <div>
                        <i class="mdi mdi-transfer fa-3x"></i>
                    </div>
                </div>
                <div class="box-footer p-2 box-footer-dark">
                    <a href="<?= admin_url('payout/withdrawal') ?>" class="btn btn-sm btn-outline-light">View More</a>
                </div>
            </div>
        </div>
    </div>
</div>