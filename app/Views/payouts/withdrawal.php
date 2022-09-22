<div class="mb-3" style="display: flex; justify-content: space-between;">
    <h5>Withdrawal Request</h5>
</div>
<div class="card card-info p-3">
    <table class="table data-table">
        <thead>
            <tr>
                <th>Sl</th>
                <th>Amount</th>
                <th>User Id</th>
                <th>Name & Details</th>
                <th>Address</th>
                <th>Mobile</th>
                <th>Email Id</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sl = 1;
            $db = db_connect();
            $builder = $db->table("users");
            $sl = 1;
            foreach ($paylist as $ob) {
                $user = $builder->getWhere(['id' => $ob->user_id])->getRow();
            ?>
                <tr>
                    <td><?= $sl++; ?></td>
                    <td><?= number_format($ob->amount, 2); ?></td>
                    <td><?= $user->username; ?></td>
                    <td><?= $user->first_name . ' ' . $user->last_name; ?><br />Mobile: <?= $user->mobile; ?></td>
                    <td><?= $ob->wallet_adrs; ?></td>
                    <td><?= $user->mobile; ?></td>
                    <td><?= $user->email_id; ?></td>
                    <td>
                        <?php
                        if ($ob->status == 0) {
                        ?>
                            <span class="badge bg-warning">Pending</span>
                            <!-- <a href="<?= admin_url('payout/withdrawal-update/' . $ob->id) ?>/?status=1" class="btn btn-xs btn-success btn-confirm" data-msg="Are you sure to Approve withdrawal?">Approve</a>
                            <a href="<?= admin_url('payout/withdrawal-update/' . $ob->id) ?>/?status=2" class="btn btn-xs btn-danger btn-confirm" data-msg="Are you sure to Reject withdrawal?">Reject</a> -->
                        <?php
                        } else if ($ob->status == 1) {
                        ?>
                            <span class="badge bg-success">Approved</span>
                        <?php
                        } else if ($ob->status == 2) {
                        ?>
                            <span class="badge bg-danger">Rejected</span>
                        <?php
                        } else if ($ob->status == 3) {
                        ?>
                            <span class="badge bg-dark">Cancelled</span>
                        <?php
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        if ($ob->status == 0) {
                        ?>
                            <!-- <a href="<?= admin_url('payout/withdrawal_rejected/' . $ob->id) ?>/?status=2" class="btn btn-xs btn-danger btn-confirm" data-msg="Are you sure to Reject withdrawal?">Reject</a> -->
                            <a href="<?= admin_url('payout/withdrawal-update/' . $ob->id) ?>/?status=1" class="btn btn-xs btn-success btn-confirm" data-msg="Are you sure to Approve withdrawal?">Approve</a>
                            <a href="<?= admin_url('payout/withdrawal-update/' . $ob->id) ?>/?status=2" class="btn btn-xs btn-danger btn-confirm" data-msg="Are you sure to Reject withdrawal?">Reject</a>
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