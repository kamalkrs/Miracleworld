<?php

use App\Models\Dashboard_model;

$db = db_connect();
$user_id = user_id();
$date = $_GET['date'];

?>
<div class="header-back">
    <a href="<?= site_url('dashboard/income-report/?tab=' . $tab) ?>">
        <i class="fa fa-chevron-left"></i> <?= $title; ?>
    </a>
</div>
<div class="px-3">
    <?php
    if ($tab == 'campaign') {

        $builder = $db->table("transaction");
        $builder->where("DATE(created) = '$date'");
        $list = $builder->orderBy('id', 'DESC')->getWhere(['user_id' => $user_id, 'notes' => Dashboard_model::INCOME_ADS])->getResult();
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
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sl = 1;
                        foreach ($list as $item) {
                        ?>
                            <tr>
                                <td><?= $sl++; ?></td>
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
                        ?>
                            <tr>
                                <td><?= $sl++; ?></td>
                                <td><?= usd_rs($item->amount) ?></td>
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
        $level = isset($_GET['level']) ? $_GET['level'] : false;
        $date = isset($_GET['date']) ? $_GET['date'] : false;


        $builder = $db->table("transaction");
        if ($level) {
            $builder->where('paylevel', $level);
        }
        if ($date) {
            $builder->where("DATE(created) as $date");
        }
        // $list = $builder->orderBy('id', 'DESC')->getWhere(['user_id' => $user_id, 'notes' => Dashboard_model::INCOME_LEVEL])->getResult();

        if ($level) {
            $list = $db->table("transaction")->where("DATE(created) = '$date'")->where(['user_id' => $user_id, 'notes' => Dashboard_model::INCOME_LEVEL, 'paylevel' => $level])->get()->getResult();
        } else {
            $list = $db->table("transaction")->select('sum(amount) as amount, paylevel, DATE(created) as created')->where("DATE(created) = '$date'")->where(['user_id' => $user_id, 'notes' => Dashboard_model::INCOME_LEVEL])->groupBy('paylevel')->get()->getResult();
        }
    ?>
        <div id="level" class="bg-3 p-3 bg-white rounded mybox footer-margin">
            <h3>Level Income</h3>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Sl No</th>
                            <th>Amount</th>
                            <th>Level</th>
                            <th>Date</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sl = 1;
                        foreach ($list as $item) {
                        ?>
                            <tr>
                                <td><?= $sl++; ?></td>
                                <td><?= usd_rs($item->amount) ?></td>
                                <td><?= $item->paylevel; ?></td>
                                <td><?= date("M d, Y", strtotime($item->created)) ?></td>
                                <td>
                                    <?php
                                    if ($date && $level == false) {
                                    ?>
                                        <a href="<?= site_url('dashboard/income-report-view/?tab=level&date=' . date("Y-m-d", strtotime($item->created))) ?>&level=<?= $item->paylevel; ?>" class="btn btn-xs btn-primary">View Details</a>
                                    <?php
                                    }
                                    ?>
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