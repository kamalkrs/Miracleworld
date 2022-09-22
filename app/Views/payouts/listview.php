<div class="mb-3" style="display: flex; justify-content: space-between;">
    <h5>Payout List</h5>
    <a href="<?= admin_url('payout'); ?>" class="btn btn-sum btn-primary">Go Back</a>
</div>
<div class="box p-2">
    <table class="table">
        <thead>
            <tr>
                <th>Sl no</th>
                <th>Name</th>
                <th>Username</th>
                <th>Mobile no</th>
                <!-- <th>Total Amount</th>
                <th>TDS @5%</th>
                <th>Admin @10%</th> -->
                <th>Amout</th>
                <th>Net Payment</th>
                <th>Pay Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sl = 1;
            $db = db_connect();

            foreach ($payout as $ob) {

                $builder  = $db->table("users");
                $user = $builder->getWhere(array("id" => $ob->id))->getRow();
            ?>
                <tr>
                    <td><?= $sl++; ?></td>
                    <td><?= $user->first_name . ' ' . $user->last_name; ?></td>
                    <td><?= $user->username; ?></td>
                    <td><?= $user->mobile; ?></td>
                    <td>$ <?= number_format($ob->total, 2); ?></td>
                    <td>$ <?= number_format($ob->total * .90, 2); ?></td>
                    <td><?php

                        echo '<span class="btn btn-xs btn-success">PAID</span>';
                        ?></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>