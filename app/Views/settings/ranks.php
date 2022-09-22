<div class="page-header">
    <h5>Ranking</h5>
    <a href="<?= admin_url('settings/add-rank') ?>" class="btn btn-sm btn-primary">Add New Rank</a>
</div>
<div class="card card-info">
    <table class="table">
        <thead>
            <tr>
                <th>Sl No</th>
                <th>Rank</th>
                <th>Self Business Min</th>
                <th>Self Business Max</th>
                <th>Team Business</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($items as $item) {
            ?>
                <tr>
                    <td><?= $item->rank_order; ?></td>
                    <td><?= $item->rank_title; ?></td>
                    <td><?= $item->self_business_min; ?></td>
                    <td><?= $item->self_business_max; ?></td>
                    <td><?= $item->team_business; ?></td>
                    <td>
                        <a href="<?= admin_url('settings/add-rank/' . $item->id) ?>" class="btn btn-xs btn-primary">Edit</a>
                        <a href="<?= admin_url('settings/delete-rank/' . $item->id) ?>" class="btn btn-xs btn-danger btn-delete">Delete</a>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>