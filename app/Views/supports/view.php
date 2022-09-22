<div class="page-header">
    <h5>Ticket ID: #<?= $ticket->id; ?></h5>
</div>

<div class="clearfix">
    <div class="row">
        <div class="col-12">
            <div class="box">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= $ticket->subject; ?></td>
                            <td><?= date('d-M-Y', strtotime($ticket->created)); ?></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <?= $ticket->description; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-12">
            <div class="box box-body p-4">
                <form action="<?= admin_url('supports/views/' . $ticket->id); ?>" method="post">
                    <p>
                        <textarea required rows="6" cols="80" class="form-control" name="description" placehoder="Write your reply..."></textarea>
                    </p>
                    <button type="submit" name="s" value="v" class="btn btn-success">Send</button>
                </form>
            </div>
            <?php
            $db = db_connect();
            $builder = $db->table("users");
            $u = $builder->getWhere(['id' => $ticket->user_id])->getRow();
            ?>
            <!-- Chat box -->
            <?php
            if (is_array($views) && count($views) > 0) {
                foreach ($views as $v) {
            ?>
                    <div class="box box-success">
                        <div class="box-body p-2">
                            <?php
                            if ($v->from_id == 0) {
                            ?>
                                <p class="text-end"><b>Support:</b> <?= date("d-m-Y h:i a", strtotime($v->created)); ?></p>
                                <div class="text-end">
                                    <?= $v->description; ?>
                                </div>
                            <?php
                            } else {
                            ?>
                                <p><b><?= $u->first_name . ' ' . $u->last_name; ?> :</b> <?= date("d-m-Y h:i a", strtotime($v->created)); ?></p>
                                <?= $v->description; ?>
                            <?php
                            }
                            ?>

                        </div>
                    </div>
            <?php
                }
            }
            ?>
        </div><!-- /.col -->
    </div>
</div>