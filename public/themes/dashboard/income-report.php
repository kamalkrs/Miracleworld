<?php

use App\Models\Dashboard_model;

$db = db_connect();
$user_id = user_id();
?>
<div class="header-back">
    <a href="<?= site_url('dashboard/payment-report') ?>">
        <i class="fa fa-chevron-left"></i> <?= $title; ?>
    </a>
</div>
<div class="px-3">
    <?php
    if ($tab == 'campaign') {
        $list = $db->table("transaction")->select('sum(amount) as sum, created')->where(['user_id' => $user_id, 'notes' => Dashboard_model::INCOME_ADS])->groupBy('DATE(created)')->orderBy('created', 'DESC')->get()->getResult();
    ?>
        <div id="campaign" class="bg-3 p-3 bg-white rounded mybox">
            <h3>Campaign Income</h3>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Sl No</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Show History</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sl = 1;
                        foreach ($list as $item) {
                        ?>
                            <tr>
                                <td><?= $sl++; ?></td>
                                <td><?= usd_rs($item->sum) ?></td>
                                <td><?= date("M d, Y", strtotime($item->created)) ?></td>
                                <td>
                                    <a href="<?= site_url('dashboard/income-report-view/?tab=campaign&date=' . date("Y-m-d", strtotime($item->created))) ?>" class="btn btn-xs btn-primary">View Details</a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php
    } else if ($tab == 'sponsor') {
        $list = $db->table("transaction")->orderBy('id', 'DESC')->getWhere(['user_id' => $user_id, 'notes' => Dashboard_model::INCOME_SPONSOR])->getResult();
    ?>
        <div id="sponsor" class="bg-3 p-3 bg-white rounded mybox">
            <h3>Direct Sponsor</h3>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Sl No</th>
                            <th>Sponsor ID</th>
                            <th>Amount</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sl = 1;
                        foreach ($list as $item) {
                        ?>
                            <tr>
                                <td><?= $sl++; ?></td>
                                <td><?= id2userid($item->comments) ?></td>
                                <td><?= usd_rs($item->amount) ?></td>
                                <td><?= date("M d, Y", strtotime($item->created)) ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php
    } else if ($tab == 'compounding') {
        $list = $db->table("transaction")->orderBy('id', 'DESC')->getWhere(['user_id' => $user_id, 'notes' => Dashboard_model::INCOME_COMPOUNDING])->getResult();
    ?>
        <div id="compounding" class="bg-3 p-3 bg-white rounded mybox">
            <h3>Compounding Income</h3>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Sl No</th>
                            <th>Investment</th>
                            <th>Profit</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sl = 1;
                        foreach ($list as $item) {
                            $amt = $item->amount;
                            $base = $item->amount * 100 / 101;
                            $profit = $amt - $base;
                        ?>
                            <tr>
                                <td><?= $sl++; ?></td>
                                <td><?= usd_rs($base) ?></td>
                                <td><?= usd_rs($profit) ?></td>
                                <td><?= date("M d, Y", strtotime($item->created)) ?></td>
                                <td></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php
    } else if ($tab == 'level') {
        $list = $db->table("transaction")->select('sum(amount) as sum, DATE(created) as created')->where(['user_id' => $user_id, 'notes' => Dashboard_model::INCOME_LEVEL])->groupBy('CAST(created AS DATE)')->orderBy('created', 'DESC')->get()->getResult();
    ?>
        <div id="level" class="bg-3 p-3 bg-white rounded mybox footer-margin">
            <h3>Level Income</h3>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Sl No</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Show Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sl = 1;
                        foreach ($list as $item) {
                        ?>
                            <tr>
                                <td><?= $sl++; ?></td>
                                <td><?= usd_rs($item->sum) ?></td>
                                <td><?= date("M d, Y", strtotime($item->created)) ?></td>
                                <td>
                                    <a href="<?= site_url('dashboard/income-report-view/?tab=level&date=' . date("Y-m-d", strtotime($item->created))) ?>&level=" class="btn btn-xs btn-primary">View Details</a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php
    }
    ?>
</div>