<div class="page-header">
    <h5>My Downline</h5>
</div>
<div class="hgradiant position-relative logo-box footer-margin">
    <div class="bg-3 p-3 bg-white rounded mybox">
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>SN</th>
                        <th>Rfl. Id</th>
                        <th>Name</th>
                        <th>Balance</th>
                        <th>Date</th>
                        <th>Team</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    use App\Models\User_model;

                    $db = db_connect();
                    $builder = $db->table('level_manager');
                    $ranks = [];
                    $sl = 1;
                    foreach ($members as $ob) {
                        $ids = $db->table("users")->select("count(*) as c")->getWhere(['spil_id' => $ob->id])->getRow()->c;
                    ?>
                        <tr>
                            <td><?= $sl++; ?></td>
                            <td><?= $ob->username; ?></td>
                            <td><?= $ob->first_name; ?></td>
                            <td>
                                <?=
                                $ob->ac_status == 1 ? '<span class="badge badge-status bg-success">Active</span>' : '<span class="badge badge-status bg-danger">In-Active</span>';
                                ?>
                            </td>
                            <td><?= date('Y-m-d H:i', strtotime($ob->join_date)); ?> </td>
                            <td><?= $ids; ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>