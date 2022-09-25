<?php

use App\Models\Dashboard_model;
use App\Models\User_model;

$ref = site_url('signup/?ref=' . $me->username);
$me = new User_model(user_id());
?>
<div id="origin">
    <div id="adsview">
        <div class="page-header">
            <h3 class="h5 top-text">Welcome to <span class="text-danger"> {{ dashboard.company }} </h3>
        </div>

        <div class="row  align-items-center mb-2">
            <div class="col-sm-6">
                <div class="input-group">
                    <span data-target="#top1" data-copy="<?= $ref; ?>" class="input-group-text btn-copy card-tiles" id="basic-addon1">
                        <i class="fa fa-copy"></i>
                    </span>
                    <input type="text" readonly class="form-control btn-copy" placeholder="Referral code" value="<?= $ref; ?>" aria-label="Username" aria-describedby="basic-addon1">
                </div>
                <div id="top1"></div>
            </div>
            <div class="col-sm-6 text-end">
                <div class="input-group">
                    <input type="text" value="<?= $me->trc20_adrs; ?>" readonly class="form-control" placeholder="Wallet Address" aria-label="Username" aria-describedby="basic-addon2">
                    <span data-target="#top2" data-copy="<?= $me->trc20_adrs; ?>" class="input-group-text btn-copy btn-info" id="basic-addon2">
                        <i class="fa fa-copy"></i>
                    </span>
                </div>
                <div id="top2"></div>
            </div>
        </div>
        <?php
        if (trim($settings->message) != '') {
        ?>
            <div class="alert p-2 alert-danger">
                <marquee behavior="scroll" direction="left">
                    <?= $settings->message; ?>
                </marquee>
            </div>
        <?php
        }
        ?>
        <div class="clearfix">
            <div class="row g-2">
                <div class="col-sm-4">
                    <?php
                    if ($me->ac_status == 0) {
                    ?>
                        <div class="bg-danger text-white p-3 rounded-1 mb-1 d-flex justify-content-between align-items-center">
                            <div>
                                <span style="color: #DDD; font-size: 16px;">Account Not Active</span>
                            </div>
                            <div>
                                <a href="<?= base_url('dashboard/topup') ?>" class="btn btn-sm btn-warning">Activate</a>
                            </div>
                        </div>
                    <?php
                    } else if ($me->ac_status == 1) {
                    ?>
                        <div class="bg-danger text-white p-3 rounded-1 mb-1 d-flex justify-content-between align-items-center">
                            <div>
                                <span style="color: #DDD; font-size: 16px;">Re-Topup Account</span>
                            </div>
                            <div>
                                <a href="<?= base_url('dashboard/retopup-self') ?>" class="btn btn-sm btn-warning">Click now</a>
                            </div>
                        </div>
                    <?php
                    }
                    ?>

                    <div class="bg-white p-2 shadow-sm">
                        <div class="d-flex mb-3 justify-content-between align-items-center">
                            <?php
                            $src = base_url(upload_dir('default.png'));
                            if ($me->image != '') {
                                $src = base_url(upload_dir($me->image));
                            }
                            ?>
                            <div class="p-2">
                                <img width="100" class="img-circle img-thumbnail" src="<?= $src; ?>" title="<?= $me->first_name; ?>">
                            </div>
                            <div style="flex: 1">
                                <h2 class="h5"><?= $me->first_name . ' ' . $me->last_name; ?></h2>
                                <div>
                                    <div>Mobile</div>
                                    <div><?= $me->mobile; ?></div>
                                </div>
                            </div>
                        </div>
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <td>Username</td>
                                    <td><?= $me->username; ?></td>
                                </tr>
                                <tr>
                                    <td>Date of Signup</td>
                                    <td><?= date('jS M, Y', strtotime($me->join_date)) ?></td>
                                </tr>
                                <tr>
                                    <td>Address</td>
                                    <td><?= $me->address; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="col-sm-8">
                    <div class="row g-2 mb-2 text-center">
                        <div class="col-6 col-sm-4">
                            <div class="card p-3 card-tiles">
                                <div class="box-p">
                                    <img src="<?= theme_url('/dashboard/img/teams.png') ?>" width="60" />
                                    <h6>Direct Team</h6>
                                    <?= $direct; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-4">
                            <div class="card p-3 card-tiles">
                                <div class="box-p">
                                    <img src="<?= theme_url('/dashboard/img/teams.png') ?>" width="60" />
                                    <h6>Total Team</h6>
                                    <?= $total_team; ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-sm-4">
                            <div class="card p-3 card-tiles">
                                <div class="box-p">
                                    <img src="<?= theme_url('/dashboard/img/sponsor.png') ?>" width="60" />
                                    <h6>Direct Income</h6>
                                    <?= $me->getBalance(Dashboard_model::INCOME_SPONSOR); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-4">
                            <div class="card p-3 card-tiles">
                                <div class="box-p">
                                    <img src="<?= theme_url('/dashboard/img/level.png') ?>" width="60" />
                                    <h6>Club Income</h6>
                                    <?= $me->getBalance(Dashboard_model::INCOME_CLUB); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-4">
                            <div class="card p-3 card-tiles">
                                <div class="box-p text-center">
                                    <img src="<?= theme_url('/dashboard/img/bank.png') ?>" width="60" />
                                    <h6>Miracle Auto Pool </h6>
                                    <?= $me->getBalance(Dashboard_model::INCOME_REBIRTH); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-4">
                            <div class="card p-3 card-tiles">
                                <div class="box-p">
                                    <img src="<?= theme_url('/dashboard/img/bank.png') ?>" width="60" />
                                    <h6>Total Income</h6>
                                    <?= $me->totalIncome(); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-4">
                            <div class="card p-3 card-tiles">
                                <div class="box-p">
                                    <img src="<?= theme_url('/dashboard/img/wallet.png') ?>" width="60" />
                                    <h6>Fund Balance</h6>
                                    <?= $me->getFundBalance(); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-4">
                            <div class="card p-3 card-tiles">
                                <div class="box-p text-center">
                                    <img src="<?= theme_url('/dashboard/img/atm.png') ?>" width="60" />
                                    <h6>Total Withdrawal</h6>
                                    <?= $me->totalPaid(); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-4">
                            <div class="card p-3 card-tiles">
                                <div class="box-p text-center">
                                    <img src="<?= theme_url('/dashboard/img/bank.png') ?>" width="60" />
                                    <h6>Fund Transfer</h6>
                                    <?= $me->getFundTransfer(); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-4">
                            <div class="card p-3 card-tiles">
                                <div class="box-p text-center">
                                    <img src="<?= theme_url('/dashboard/img/bank.png') ?>" width="60" />
                                    <h6>Activation Wallet</h6>
                                    <?= $me->getWalletBalance(); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-4">
                            <div class="card p-3 card-tiles">
                                <div class="box-p text-center">
                                    <img src="<?= theme_url('/dashboard/img/bank.png') ?>" width="60" />
                                    <h6>Withdrawal Limit</h6>
                                    <?= $me->getWithdrawLimit(); ?>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <h5>Members List</h5>
    <hr>
    <div class="bg-white mb-3 p-3">
        <table class="table">
            <thead>
                <tr>
                    <th>Sl No</th>
                    <th>Userinfo</th>
                    <th>Placement ID</th>
                    <th>Join Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>

<script>
    let vm = new Vue({
        el: '#origin',
        data: {
            loader: true,
            dashboard: {}
        },
        methods: {
            loadData: function() {
                let url = ApiUrl + 'dashboard';
                axios.post(url).then(result => {
                    let resp = result.data;
                    this.dashboard = resp.data;
                    this.loader = false
                })
            }
        },
        created: function() {
            this.loadData();
        }
    });
</script>