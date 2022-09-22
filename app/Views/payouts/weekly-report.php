<h5>Weekly Payout Report</h5>
<hr>

<div class="box box-p">
    <table class="table">
        <thead>
            <tr>
                <th>Payout</th>
                <th>Date Between</th>
                <th>View List</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $start = '2021-02-27';
            for ($i = 1; $i <= $weeks; $i++) {
                $to = date('Y-m-d', strtotime($start . " +1 weeks"));
                $tos = date('Y-m-d', strtotime($start . " +6 days"));
            ?>
                <tr>
                    <td>Payout <?= $i; ?></td>
                    <td><?= $start; ?> - <?= $tos; ?></td>
                    <td>
                        <a href="<?= admin_url("payout/payout-report/?from=$start&to=$tos&btn=Search"); ?>" class="btn btn-sm btn-primary">View List</a>
                    </td>
                </tr>
            <?php
                $start = $to;
            }
            ?>
        </tbody>
    </table>
</div>