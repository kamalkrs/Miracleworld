<div class="page-header">
    <h3 class="h5"><?= $title; ?></h3>
</div>
<div class="card card-info">
    <div class="card-body">
        <table class="table table-responsive table-sm table-bordered table-striped data-table1">
            <thead>
                <tr>
                    <th>Sl</th>
                    <th>Name</th>
                    <th>Userid</th>
                    <th>Password</th>
                    <th>Sponsor Id</th>
                    <th>Mob. Number</th>
                    <th>Email Id</th>
                    <th>Join Date</th>
                    <th>A/c Status</th>
                    <th>Withdrawal</th>
                    <th>Direct</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sl = 1;
                foreach ($members as $m) {
                ?>
                    <tr>
                        <td><?= $sl++; ?></td>
                        <td><?= $m->first_name . ' ' . $m->last_name; ?></td>
                        <td><?= $m->username; ?></td>
                        <td><?= $m->passwd; ?></td>
                        <td><?= id2userid($m->sponsor_id); ?></td>
                        <td><?= $m->mobile; ?></td>
                        <td><?= $m->email_id; ?></td>
                        <td><?php echo  date("Y-m-d h:i:s a", strtotime($m->join_date)); ?></td>
                        <td> <?php if ($m->status == 1) { ?>
                                <a href="<?= admin_url('members/change-status/' . $m->id . '/0'); ?>" class="btn btn-xs btn-success btn-status">Block</a>
                            <?php } else { ?>
                                <a href="<?= admin_url('members/change-status/' . $m->id . '/1'); ?>" class="btn btn-xs btn-danger btn-status">Unblock</a>
                            <?php } ?>
                        </td>
                        <td>
                            <?php if ($m->payout == 1) { ?>
                                <a href="<?= admin_url('members/change-payout/' . $m->id . '/0'); ?>" class="btn btn-xs btn-success btn-status">ON</a>
                            <?php } else { ?>
                                <a href="<?= admin_url('members/change-payout/' . $m->id . '/1'); ?>" class="btn btn-xs btn-danger btn-status">OFF</a>
                            <?php } ?>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a title="Edit" href="<?php echo admin_url('members/edit/' . $m->id); ?>" class="btn btn-primary btn-xs"> <i class="fa fa-pencil"></i> </a>
                                <a class="btn btn-xs btn-dark" target="_blank" href="<?= site_url('home/autologin?user=' . $m->username . '&pass=' . $m->passwd); ?>">Login</a>
                            </div>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.data-table1').DataTable({
            "order": [],
            "pageLength": 50,
            "aoColumns": [{
                "bSearchable": false
            }, {
                "bSearchable": false
            }, {
                "bSearchable": true
            }, {
                "bSearchable": false
            }, {
                "bSearchable": false
            }, {
                "bSearchable": true
            }, {
                "bSearchable": false
            }, {
                "bSearchable": false
            }, {
                "bSearchable": false
            }, {
                "bSearchable": false
            }, {
                "bSearchable": false
            }]
        });
    });
</script>