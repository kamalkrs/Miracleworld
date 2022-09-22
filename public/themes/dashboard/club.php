<h5><?= $type; ?> Matching </h5>
<hr />
<div class="box box-p">
    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Left</th>
                <th>Right</th>
                <th>Carry Left</th>
                <th>Carry Right</th>
                <th>Matching</th>
                <th>Matching Laps</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($reports as $ob) {
            ?>
                <tr>
                    <td><?= date('d M Y', strtotime($ob->created)); ?></td>
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
    </table>
</div>