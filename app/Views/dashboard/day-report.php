<div class="d-flex justify-content-between align-items-center">
    <h5>Detailed Report: <?= $date; ?></h5>
    <a class="btn btn-primary" href="<?= admin_url('dashboard/report') ?>">Go Back</a>
</div>
<hr>
<div class="box box-p">
    <table class="table data-table">
        <thead>
            <tr>
                <th>Sl</th>
                <th style="width: 200px;">Userinfo</th>
                <th>Left</th>
                <th>Right</th>
                <th>Carry Left</th>
                <th>Carry Right</th>
                <th>Matching</th>
                <th>Laps</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sl = 1;
            $a1 = $a2 = $a3 = $a4 = 0;
            $laps = 0;
            foreach ($report as $ind => $ob) {
                $a1 += $ob->matching;
                $laps += $ob->laps;
            ?>
                <tr>
                    <td><?= $sl++; ?></td>
                    <td>
                        <a href="<?= admin_url('members/details/' . $ob->user_id); ?>">
                            <?= $ob->username; ?>
                            <br>
                            <small><?= $ob->first_name; ?></small>
                        </a>
                    </td>
                    <td><?= $ob->left_count; ?></td>
                    <td><?= $ob->right_count; ?></td>
                    <td><?= $ob->left_carry; ?></td>
                    <td><?= $ob->right_carry; ?></td>
                    <td><?= $ob->matching; ?></td>
                    <td><?= $ob->laps; ?></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><b>Matching:</b> <?= $a1; ?></td>
                <td>Laps: <?= $laps; ?></td>
            </tr>
        </tfoot>
    </table>
</div>