<div class="page-header">
    <h5>Fund Request </h5>
</div>
<div class="box p-2">
    <table class="table ">
        <thead>
            <tr>
                <th>Sl No</th>
                <th>Username</th>
                <th>Request Amount</th>
                <th>Date & Time</th>
                <th>Notes</th>
                <th>Screenshot</th>
                <th>Request Status</th>
                <th>Created</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>

            <?php
            $sl = 1;
            $db = db_connect();
            $builder = $db->table('users');
            foreach ($request as $m) {
                $u = $builder->getWhere(['id' => $m->user_id])->getRow();
            ?>
                <tr>
                    <td><?= $sl++; ?></td>
                    <td><?php echo  id2userid($m->user_id); ?></td>
                    <td><?php echo $m->amount; ?></td>
                    <td><?php echo $m->fdate . ' ' . $m->ftime; ?></td>
                    <td><?php echo $m->notes; ?></td>
                    <td>
                        <?php
                        if ($m->screenshot != '') {
                        ?>
                            <img src="<?= base_url(upload_dir($m->screenshot)); ?>" width="100" />
                        <?php
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        if ($m->status == 0) {
                            echo '<span class="badge badge-info">Active</span>';
                        } else if ($m->status == 2) {
                            echo '<span class="badge badge-danger">Decline</a>';
                        } else if ($m->status == 1) {
                            echo '<span class="badge badge-success">Approved</a>';
                        }
                        ?>
                    </td>
                    <td><?= date("d M, Y h:i A", strtotime($m->created)); ?></td>
                    <td>
                        <?php
                        if ($m->status == 0) {
                        ?>
                            <div class="pull-right btn-group">
                                <a href="<?= admin_url('payments/approved/' . $m->id) ?>" class="approve btn btn-xs btn-success btn-confirm" data-msg="Are you sure to Approve?"> <span class="label label-success">Approved </span></a>
                                <a href="<?= admin_url('payments/decline/' . $m->id) ?>" class="decline btn btn-xs btn-danger btn-confirm" data-msg="Are you sure to Decline?"><span class="label label-danger">Decline</span></a>
                            </div>
                        <?php } ?>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>