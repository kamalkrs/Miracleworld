<?php

use App\Models\User_model;

$db = db_connect();
$usob = new User_model($user->id);
?>
<div class="d-flex justify-content-between">
    <h4>Member Details: <?= $user->first_name . ' - ' . $user->username; ?></h4>
    <div>
        <a class="btn btn-xs btn-dark" target="_blank" href="<?= site_url('home/autologin?user=' . $user->username . '&pass=' . $user->passwd); ?>">Auto Login</a>
        <a class="btn btn-xs btn-primary" href="<?= admin_url('members/edit/' . $user->id); ?>">Edit Details</a>
        <!--
        <a href="<?= admin_url('members/add-purchase/' . $user->id); ?>" class="btn btn-primary"> <i class="fa fa-plus-circle"></i> ADD PURCHASE</a>
        <a href="<?= admin_url('members/add-magic/' . $user->id); ?>" class="btn btn-warning"> <i class="fa fa-plus-circle"></i> Auto Magic</a>
        -->
    </div>
</div>
<div class="d-flex text-muted justify-content-between">
    <div>Mobile: <?= $user->mobile; ?></div>
    <div>Joining: <?= date('d M Y', strtotime($user->join_date)); ?></div>
</div>

<hr>
<div class="row text-white text-center mb-4">
    <div class="col-sm-3">
        <div class="p-3 bg-info">
            Direct Joining: <?= count($members); ?>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="p-3 bg-success">
            Total Joining: <?= $downline; ?>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="p-3 bg-warning">
            Wallet Income: <?= $current_income; ?>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="p-3 bg-primary">
            Total Income: <?= $total_income; ?>
        </div>
    </div>
</div>
<?php
$menu = isset($_GET['page']) ? $_GET['page'] : 'plans';
$url = admin_url('members/details/' . $user->id);
?>
<div class="row">
    <div class="col-sm-12">
        <div class="tab-container">
            <div class="tab-menu">
                <ul>
                    <li <?= $menu == 'plans' ? 'class="active"' : ''; ?>><a href="<?= $url; ?>">Active Plans</a></li>
                    <li <?= $menu == 'members' ? 'class="active"' : ''; ?>><a href="<?= $url; ?>?page=members">Direct Members</a></li>
                    <li <?= $menu == 'funds' ? 'class="active"' : ''; ?>><a href="<?= $url; ?>/?page=funds">Fund Request Report</a></li>
                    <li <?= $menu == 'wallet' ? 'class="active"' : ''; ?>><a href="<?= $url; ?>/?page=wallet">Wallet Report</a></li>
                    <li <?= $menu == 'history' ? 'class="active"' : ''; ?>><a href="<?= $url; ?>/?page=history">Transaction History</a></li>
                    <li><a href="<?= admin_url('members/wallet_options/' . $user->id); ?>/?page=history">Wallet Options</a></li>
                </ul>
            </div>
            <div class="tab-content">
                <?php
                if ($menu == 'plans') {
                    $builder = $db->table("userplans");
                    $builder->select('userplans.*, plans.plan_title');
                    $builder->join("plans", "plans.id = userplans.plan_id");
                    $plans = $builder->getWhere(['user_id' => $user->id])->getResult();
                ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Amount</th>
                                <th>Earned</th>
                                <th>Days</th>
                                <th>Start</th>
                                <th>Ends</th>
                                <th>Option</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sl = 1;
                            foreach ($plans as $plan) {
                            ?>
                                <tr class="bg-light">
                                    <td><?= $sl++; ?></td>
                                    <td><?= $plan->amount; ?></td>
                                    <td><?= $usob->planIncome($plan->id); ?></td>
                                    <td><?= date("d M Y", strtotime($plan->start_dt)) ?></td>
                                    <td><?= date("d M Y", strtotime($plan->end_dt)) ?></td>
                                    <td>
                                        <?php
                                        if ($plan->status == 1) {
                                            echo '<span class="badge bg-success">Active</span>';
                                        } else {
                                            echo '<span class="badge bg-danger">In-Active</span>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-xs btn-primary"> <i class="mdi mdi-pencil"></i> Edit</a>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                <?php
                } else if ($menu == 'members') {
                ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Mobile no</th>
                                <th>Joining</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sl = 1;
                            foreach ($members as $m) {
                            ?>
                                <tr>
                                    <td><?= $sl++; ?></td>
                                    <td><?= $m->first_name . ' ' . $m->last_name; ?></td>
                                    <td><?= $m->username; ?></td>
                                    <td><?= $m->mobile; ?></td>
                                    <td><?= $m->join_date; ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                <?php
                } else if ($menu == 'funds') {
                    $items = $db->table('fund_request')->getWhere(['user_id' => $user->id])->getResult();
                ?>
                    <table class="table ">
                        <thead>
                            <tr>
                                <th>Sl No</th>
                                <th>Amount</th>
                                <th>Notes</th>
                                <th>Screenshot</th>
                                <th>Request Status</th>
                                <th>Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sl = 1;
                            foreach ($items as $m) {
                            ?>
                                <tr>
                                    <td><?= $sl++; ?></td>
                                    <td><?php echo $m->amount; ?></td>
                                    <td><?php echo $m->notes; ?></td>
                                    <td>
                                        <?php
                                        if ($m->screenshot != '') {
                                        ?>
                                            <img src="<?= base_url(upload_dir($m->screenshot)); ?>" width="100" />
                                        <?php
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($m->status == 0) {
                                            echo '<span class="badge badge-info">Active</span>';
                                        } else if ($m->status == 2) {
                                            echo '<span class="badge badge-danger">Decline</a>';
                                        } else if ($m->status == 1) {
                                            echo '<span class="badge badge-success">Approved</a>';
                                        }
                                        ?>
                                    </td>
                                    <td><?= date("d M, Y h:i A", strtotime($m->created)); ?></td>
                                    <td>
                                        <?php
                                        if ($m->status == 0) {
                                        ?>
                                            <div class="pull-right btn-group">
                                                <a href="<?= admin_url('payments/approved/' . $m->id) ?>" class="approve btn btn-xs btn-success btn-confirm" data-msg="Are you sure to Approve?"> <span class="label label-success">Approved </span></a>
                                                <a href="<?= admin_url('payments/decline/' . $m->id) ?>" class="decline btn btn-xs btn-danger btn-confirm" data-msg="Are you sure to Decline?"><span class="label label-danger">Decline</span></a>
                                            </div>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                <?php
                } else  if ($menu == 'wallet') {
                ?>

                <?php
                } else if ($menu == 'history') {
                    $builder = $db->table("transaction");
                    $userId = $user->id;
                    $builder->orderBy("id", "DESC");
                    $list = $builder->getWhere(['user_id' => $userId])->getResult();
                ?>
                    <table class="table data-table">
                        <thead>
                            <tr>
                                <th>Sl No</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Txn Id</th>
                                <th>Amount</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sl = 1;
                            $sum = 0;
                            foreach ($list as $ob) {
                                $sum += $ob->amount;
                            ?>
                                <tr>
                                    <td><?= $sl++; ?></td>
                                    <td><?= date("Y-m-d", strtotime($ob->created)); ?></td>
                                    <td><?= strtoupper($ob->cr_dr); ?></td>
                                    <td><?= $ob->ref_id; ?></td>
                                    <td> $ <?= $ob->amount; ?></td>
                                    <td>$ <?= $ob->total_bal; ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3">Total</td>
                                <td><?= $sum; ?></td>
                            </tr>
                        </tfoot>
                    </table>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php

function show($name)
{
    if ($name != '') {
        echo '<img src="' . upload_dir($name) . '" width="100" />';
    } else {
        echo 'Not uploaded';
    }
}
