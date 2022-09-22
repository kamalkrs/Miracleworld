<h5>Matching Report</h5>
<hr>
<div class="box box-p">
    <table class="table data-table text-center">
        <thead>
            <tr>
                <th>Date</th>
                <th>Matching</th>
                <th>Laps</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($plan_a as $ob) {
                $dt = date('Y-m-d', strtotime($ob->created));
            ?>
                <tr>
                    <td><?= $dt; ?></td>
                    <td><?= $ob->mat; ?></td>
                    <td><?= $ob->laps; ?></td>
                    <td>
                        <a href="<?= admin_url('dashboard/day_report/' . $dt); ?>" class="btn btn-xs btn-primary">View List</a>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>