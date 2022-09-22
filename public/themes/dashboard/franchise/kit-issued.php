<div class="mb-3">
    <h5>All Kit Issued</h5>
</div>
<div class="box box-p">
    <table class="table table-sm data-table">
        <thead>
            <tr>
                <th>Sl No</th>
                <th>Date</th>
                <th>Customer Info</th>
                <th>Receiver Info</th>
                <th>Joining Kit</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sl = 1;
            foreach ($list as $ob) {
                $sb = $this->User_model->get_user($ob->kit_issue_details);
            ?>
                <tr>
                    <td><?= $sl++; ?></td>
                    <td><?= date("d M Y", strtotime($ob->kit_issue_date)); ?></td>
                    <td><?= $ob->first_name . ' ' . $ob->last_name . '<br />Username: ' . $ob->username; ?></td>
                    <td><?= $sb->first_name . ' ' . $sb->last_name . '<br />Username: ' . $sb->username; ?></td>
                    <td><?= $ob->plan_total; ?></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>