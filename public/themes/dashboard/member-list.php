<h5>
    <?php

    use App\Models\User_model;

    $level = isset($_GET['level']) ? $_GET['level'] : 1;
    if ($level == 1) echo "Silver Members List";
    if ($level == 2) echo "Gold Members List";
    if ($level == 3) echo "Platinum Members List";
    if ($level == 4) echo "Diamond Members List";
    if ($level == 5) echo "Royal Diamond Members List";
    ?>
</h5>
<hr>
<?php
$arr = [];
$db = db_connect();
$um = new User_model();

?>
<div class="box box-p">
    <table class="table">
        <thead>
            <tr>
                <th>Level</th>
                <th>Required </th>
                <th>Achieved</th>
                <TH></TH>
            </tr>
        </thead>
        <tbody>
            <?php
            $pvs = 0;
            $a = $b = 0;
            $matched = $me->matching;
            $sl = 1;
            for ($indx = 1; $indx <= 15; $indx++) {
                $sb = $um->level_members(user_id(), $indx, $level);
            ?>
                <tr>
                    <td><?= $sl++; ?></td>
                    <td><?= pow(2, $indx); ?></td>
                    <td><?= count($sb); ?></td>
                    <td>
                        <button data-bs-toggle="modal" data-bs-target="#exampleModal<?= $sl; ?>" class="btn btn-xs btn-outline-info">View Members</button>
                        <div class="modal fade" id="exampleModal<?= $sl; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Member's List</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Sl No</th>
                                                    <th>Userid</th>
                                                    <th>Full name</th>
                                                    <th>Mobile</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $i = 1;
                                                $builder = $db->table('users');
                                                foreach ($sb as $id) {

                                                    $u = $builder->getWhere(['id' => $id])->getRow();
                                                ?>
                                                    <tr>
                                                        <td><?= $i++; ?></td>
                                                        <td><?= $u->username; ?></td>
                                                        <td><?= $u->first_name . ' ' . $u->last_name; ?></td>
                                                        <td><?= $u->mobile; ?></td>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>