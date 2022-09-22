<div class="page-header">
    <h5>Plans</h5>
    <a href="<?= admin_url('plans/add'); ?>" class="btn btn-sm btn-primary "><i class="fa fa-plus-circle"></i> Add New Plan</a>
</div>
<div class="card card-info p-3">
    <table class="table table-sm data-table">
        <thead>
            <tr>
                <th>Sl No</th>
                <th>Plan name</th>
                <th>Plan Amount</th>
                <th>Validity</th>
                <th>ROI</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sl = 1;
            foreach ($pack as $m) {
            ?>
                <tr>
                    <td><?= $sl++; ?></td>
                    <td><?= $m->plan_title; ?></td>

                    <td> $ <?= $m->amount; ?></td>
                    <td><?= $m->validity; ?> Days</td>
                    <td><?= $m->roi; ?> %</td>
                    <td><?php if ($m->status == 1) { ?>

                            <a href="<?= admin_url('plans/deactivate/' . $m->id, TRUE); ?>" class="btn btn-xs btn-success">Active</a>

                        <?php } else { ?>

                            <a href="<?= admin_url('plans/activate/' . $m->id, TRUE); ?>" class="btn btn-xs btn-danger">Deactive</a>

                        <?php } ?>
                    </td>
                    <td>
                        <div class="pull-right btn-group">
                            <a title="Edit" href="<?php echo admin_url('plans/add/' . $m->id); ?>" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i> </a>
                            <a title="Delete" href="<?php echo admin_url('plans/delete/' . $m->id); ?>" class="btn btn-danger btn-xs delete"><i class="fa fa-trash"></i> </a>
                        </div>

                    </td>

                </tr>

            <?php

            }

            ?>

        </tbody>

    </table>
</div>