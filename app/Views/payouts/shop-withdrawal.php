<div class="mb-3" style="display: flex; justify-content: space-between;">
    <h5>Shop Withdrawal Request</h5>
</div>
<div class="box p-2">
    <table class="table">
        <thead>
            <tr>
                <th>Req no</th>
                <th>Name & Details</th>
                <th>Bank, Branc & IFSC</th>
                <th>Ac Name</th>
                <th>Ac Number</th>
                <th>Net Payment</th>
                <th>Last update</th>
                <th>Pay Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sl = 1;
            foreach ($paylist as $ob) {
                $user = $this->db->get_where("shops", array("id" => $ob->user_id))->row();
            ?>
                <tr>
                    <td>#<?= $ob->id; ?></td>
                    <td><?= $user->first_name . ' ' . $user->last_name; ?>(<?= $user->shop_title; ?>)<br />Mobile: <?= $user->mobile_no; ?></td>
                    <td>Bank: <?= $user->bank_name; ?>, <br />
                        Branch: <?= $user->bank_branch; ?><br>
                        IFSC: <?= $user->bank_ifsc; ?> <br />
                    </td>
                    <td><?= $user->ac_name; ?></td>
                    <td><?= $user->ac_number; ?></td>
                    <td>Rs <?= number_format($ob->amount, 2); ?></td>
                    <td><?= date("jS M, Y H:i", strtotime($ob->updated)); ?></td>
                    <td>
                        <?php
                        if ($ob->status == 0) {
                        ?>
                            <a href="<?= admin_url('payout/shop-withdrawal-update/' . $ob->id) ?>/?status=1" class="btn btn-xs btn-success btn-confirm" data-msg="Are you sure to Approve withdrawal?">Approve</a>
                            <a href="<?= admin_url('payout/shop-withdrawal-update/' . $ob->id) ?>/?status=2" class="btn btn-xs btn-danger btn-confirm" data-msg="Are you sure to Reject withdrawal?">Reject</a>
                        <?php
                        } else if ($ob->status == 1) {
                        ?>
                            <span class="btn btn-xs btn-warning">Approved</span>
                        <?php
                        } else if ($ob->status == 2) {
                        ?>
                            <span class="btn btn-xs btn-danger">Rejected</span>
                        <?php
                        } else if ($ob->status == 3) {
                        ?>
                            <span class="btn btn-xs btn-secondary">Cancelled</span>
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