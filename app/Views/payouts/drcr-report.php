<div class="mb-3" style="display: flex; justify-content: space-between;">
    <h5>Debit/Credit Report</h5>
    <a href="<?= admin_url('payout/manage'); ?>" class="btn btn-sum btn-primary">New Payment</a>
</div>
<div class="box p-3">
    <table class="table data-table">
        <thead>
            <tr>
                <th>Sl no</th>
                <th>Name</th>
                <th>Username</th>
                <th>Mobile no</th>
                <th>Amount</th>
                <th>DR/CR</th>
                <th>Created</th>
                <th>Comments</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sl = 1;
            $db = db_connect();
            foreach ($list as $ob) {
                $user = $db->table('users')->getWhere(['id' => $ob->user_id])->getRow();
            ?>
                <tr>
                    <td><?= $sl++; ?></td>
                    <td><?= $user->first_name . ' ' . $user->last_name; ?></td>
                    <td><?= $user->username; ?></td>
                    <td><?= $user->mobile; ?></td>
                    <td>Rs <?= number_format($ob->amount, 2); ?></td>
                    <td><?= strtoupper($ob->cr_dr); ?></td>
                    <td><?= date("Y-m-d h:i:s A", strtotime($ob->created)); ?></td>
                    <td><?= $ob->comments; ?></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>