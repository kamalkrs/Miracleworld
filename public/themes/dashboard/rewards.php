<h5>Lifetime Rewards</h5>
<hr>
<?php

use App\Models\User_model;

$db = db_connect();
$builder = $db->table('reward_master');
$items = $builder->orderBy('reward_order', 'ASC')->get()->getResult();
$user = new User_model();
$m = 0;
?>
<div class="box box-info box-p">
    <table class="table">
        <thead>
            <tr>
                <th>Sl No</th>
                <th>Rank</th>
                <th>Rewards</th>
                <th>Achived </th>
                <th>Required</th>
                <th>Status</th>
                <th>Reward</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $a = $b = 0;
            $matched = 0;
            $builder = $db->table("rewards");
            $sl = 1;
            foreach ($items as $indx => $ar) {
                $pvs = $ar->left_count;
                $ob = $builder->getWhere(array("user_id" => user_id(), 'rank_id' => $indx + 1))->getRow();
                if ($matched <= $pvs) {
                    $a = $matched;
                    $b = $matched - $pvs;
                    $matched = 0;
                } else {
                    $a = $ar->left_count;
                    $b = $matched - $pvs;
                    $matched = $b;
                }
                if ($b > 0) {
                    $b = 0;
                } else {
                    $b = -$b;
                }
            ?>
                <tr>
                    <td><?= $sl++; ?></td>
                    <td><?= $ar->reward_title; ?></td>
                    <td><?= $ar->gift_item; ?></td>
                    <td><?= $a; ?></td>
                    <td><?= $b; ?></td>
                    <td><?= is_object($ob) ? '<span class="badge bg-success">Achieved</span>' : '<span class="badge bg-warning">Pending</span>' ?></td>
                    <td><?= is_object($ob) ? $ob->status : '-'; ?></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>