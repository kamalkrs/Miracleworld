<div class="page-header">
    <h5>Rewards</h5>
    <a href="<?= admin_url('settings/add-reward') ?>" class="btn btn-sm btn-primary">Add Reward</a>
</div>
<div class="card card-info">
    <table class="table">
        <thead>
            <tr>
                <th>Sl No</th>
                <th>Reward/Rank</th>
                <th>Left Business</th>
                <th>Right Business</th>
                <th>Reward</th>
                <th width="140"></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($items as $item) {
            ?>
                <tr>
                    <td><?= $item->reward_order; ?></td>
                    <td><?= $item->reward_title; ?></td>
                    <td><?= $item->left_count; ?></td>
                    <td><?= $item->right_count; ?></td>
                    <td><?= $item->gift_item; ?></td>
                    <td>
                        <a href="<?= admin_url('settings/add-reward/' . $item->id) ?>" class="btn btn-xs btn-primary"> <i class="mdi mdi-pencil"></i> Edit</a>
                        <a href="<?= admin_url('settings/delete-reward/' . $item->id) ?>" class="btn btn-xs btn-danger btn-delete"> <i class="mdi mdi-delete"></i> Delete</a>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>