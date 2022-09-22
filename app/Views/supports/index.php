<div class="page-header">
    <h5>Supports Enquiry</h5>
</div>

<div class="card card-info p-3">
    <table class="table data-table">
        <thead>
            <tr>
                <th>#Id</th>
                <th>Subject</th>
                <th>User Information</th>
                <th>Last updated</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $session = session();
            $db = db_connect();
            $builder = $db->table("admin");
            $userid = $session->get('userid');
            $admin = $builder->getWhere(array('id' => $userid))->getRow();
            if (is_array($datalist) && count($datalist) > 0) {
                $builder = $db->table("users");
                foreach ($datalist as $d) {
                    $us = $builder->getWhere(['id' => $d->user_id])->getRow();
            ?>
                    <tr>
                        <td><?= $d->id; ?></td>
                        <td><?= $d->subject; ?></td>
                        <td><a href="<?= admin_url('members/details/' . $d->user_id); ?>">
                                <?= $us->first_name . ' ' . $us->last_name . '<br />Username: ' . $us->username; ?>
                            </a></td>
                        <td><?= date('d-m-y h:i A', strtotime($d->updated)); ?></td>
                        <td><?php
                            if ($d->status == 1) echo '<span class="p-1 small text-white bg-success">OPEN</span>';
                            if ($d->status == 0) echo '<span class="p-1 small text-white bg-dark">CLOSED</span>';
                            ?></td>
                        <td>
                            <div class="btn-group pull-right">
                                <a href="<?= admin_url("supports/views/" . $d->id); ?>" title="Edit" class="btn btn-xs btn-info"><i class="fa fa-reply"></i> </a>
                                <a data-id="<?= $d->id; ?>" data-table="supports" href="#" title="Delete" class="btn btn-xs btn-danger ajax-delete"><i class="fa fa-trash"></i> </a>
                            </div>
                        </td>
                    </tr>
            <?php

                }
            }
            ?>


        </tbody>
    </table>
</div>