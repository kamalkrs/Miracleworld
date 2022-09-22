<div class="page-header">
    <h5>PIN List </h5>
    <a href="<?= admin_url('pin-generate'); ?>" class="btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> GENERATE PIN</a>
</div>
<div class="box p-3">
    <table class="table table-bordered table-striped data-table">
        <thead>
            <tr>
                <th>Sl No</th>
                <th>Username</th>
                <th>PIN</th>
                <th>Pin Type</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sl = 1;
            $db = db_connect();
            foreach ($mem_list as $m) {
                $u = $db->table("users")->getWhere(['id' => $m->user_id])->getRow();
            ?>
                <tr>
                    <td><?= $sl++; ?></td>
                    <td><?= $u->first_name . '(' . id2userid($m->user_id) . ')'; ?>
                        <br /><small class="text-primary">Phone: <?= $u->mobile; ?></small>
                        <br /><small class="text-muted">Date: <?= date('d M, Y h:i:s A', strtotime($m->created)); ?></small>
                    </td>
                    <td><?= $m->pin; ?></td>
                    <td><?= $m->pintype; ?></td>
                    <td>
                        <?php
                        if ($m->status == 1) {
                        ?>
                            <span class="badge badge-success">Not Used</span>
                        <?php
                        } else {
                        ?>
                            <span class="badge badge-danger">Used</a>
                            <?php
                        }
                            ?>
                    </td>
                    <td>
                        <?php
                        if ($m->status == 1) {
                        ?>
                            <div class="pull-right btn-group">
                                <a href="<?php echo admin_url('epin/delete/' . $m->id); ?>" class="btn btn-danger btn-sm delete"><i class="fa fa-trash"></i> </a>
                            </div>
                        <?php
                        } else {
                            $m = $db->table("users")->getWhere(['epin' => $m->pin])->getRow();
                            $name =  $m->first_name . '(' . $m->username . ')<br />' . $m->mobile;
                        ?>
                            <a href="<?= admin_url('members/details/' . $m->id); ?>"><?= $name; ?>
                                <br />
                                <small><?= date('Y-m-d h:i A', strtotime($m->ac_active_date)); ?></small>
                            </a>
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