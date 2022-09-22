<h5>Binary Matching Report</h5>
<hr />
<div class="box box-p">
    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Left PV</th>
                <th>Right PV</th>
                <th>Matching</th>
                <th>Carry Left</th>
                <th>Carry Right</th>
                <th>Matching Laps</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($reports as $ob) {
            ?>
                <tr>
                    <td><?= date('d M Y h:i A', strtotime($ob->report_created)); ?></td>
                    <td><?= $ob->left_count; ?></td>
                    <td><?= $ob->right_count; ?></td>
                    <td><?= $ob->matching; ?></td>
                    <td><?= $ob->left_carry; ?></td>
                    <td><?= $ob->right_carry; ?></td>
                    <td><?= $ob->laps; ?></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>