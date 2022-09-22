<div class="mb-3" style="display: flex; justify-content: space-between;">
    <h5>Payout Reports</h5>
    <a href="<?= admin_url('payout/generate') ?>" class="btn btn-primary">Generate Payout</a>
</div>
<div class="box p-2">
    <table class="table">
        <thead>
            <tr>
                <th>Sl no</th>
                <th>Date</th>
                <th>Total Payout</th>
                <th>Users</th>
                <th>View List</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sl = 1;
            foreach ($list as $ob) {
                $items = json_decode($ob->details);
                $sum = 0;
                $count = count($items);
                foreach ($items as $r) {
                    $sum += $r->paid;
                }
            ?>
                <tr>
                    <td><?= $sl++; ?></td>
                    <td><?= date('d M Y', strtotime($ob->created)); ?></td>
                    <td><?= $sum; ?></td>
                    <td><?= $count; ?></td>

                    <td>
                        <a href="<?= admin_url("payout/listview/" . $ob->id); ?>" class="btn btn-sm btn-primary">View List</a>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {
        $('.btn-pay').click(function() {
            if (!confirm("Are you sure you paid?"))
                return false;
        });
    });
</script>