<h5>PIN Report</h5>
<hr>
<div class="box">
    <div class="box-p table-responsive">
        <table class="table data-table">
            <thead>
                <tr>
                    <th>Sl No</th>
                    <th>PIN</th>
                    <th>Package</th>
                    <th>Status</th>
                    <th>PIN Report</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $package = config_item('package');
                if (is_array($epins) && count($epins) > 0) {
                    $sl = 1;
                    foreach ($epins as $ob) {
                ?>
                        <tr>
                            <td><?= $sl++; ?></td>
                            <td><?= $ob->pin; ?>

                            </td>
                            <td><?= isset($package[$ob->pintype]) ? $package[$ob->pintype] : 'Rs 250 '; ?></td>
                            <td><?php
                                if ($ob->status == 1) {
                                    echo '<span class="badge badge-success">Active</span>';
                                } else {
                                    echo '<span class="badge badge-danger">Used</span>';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if ($ob->status == 1) {
                                    if ($me->ac_status == 0) {
                                ?>
                                        <a href="<?= site_url('dashboard/activate/' . $me->id); ?>" class="btn btn-xs btn-outline-light">Activate Myself</a>
                                    <?php
                                    } else {
                                    ?>
                                        <span class="btn btn-xs btn-dark btn-copy" data-copy="<?= $ob->pin; ?>" style="cursor: pointer;" title="Copy"><i class="fa fa-copy"></i> COPY</span>
                                        <a href="<?= site_url('dashboard/topup/' . $ob->pin); ?>" class="btn btn-xs btn-warning">Apply</a>
                                <?php
                                    }
                                } else {

                                    $u = $this->db->get_where('users', array('epin' => $ob->pin))->row();
                                    if (is_object($u)) {
                                        if ($u->id == user_id()) {
                                            echo '<span class="badge badge-success">Self Activation</span>';
                                        } else {
                                            echo $u->first_name . ' ' . $u->last_name . '<br />' . $u->username . '<br />' . $u->mobile;
                                        }
                                    } else {
                                        echo '-';
                                    }
                                }
                                ?>
                            </td>
                        </tr>
                    <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="5">
                            <div class="text-center p-4">
                                You Don't have any PIN. <a href="<?= site_url('dashboard/pin-request-bank') ?>" class="btn btn-sm btn-outline-light">Order Now</a>
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