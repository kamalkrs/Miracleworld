<div class="page-header">
    <h5>Supports</h5>
    <a href="<?= site_url('dashboard/create-new'); ?>" class="btn btn-sm btn-primary">Create New</a>
</div>
<div class="box p-3">
    <table class="table">
        <thead>
            <tr>
                <th>Sl No</th>
                <th>Subject</th>
                <th>Created</th>
                <th>Status</th>
                <th>Delete</th>
                <th>Options</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sl = 1;
            foreach ($slist as $ob) {
            ?>
                <tr>
                    <td><?= $sl++; ?></td>
                    <td><?= $ob->subject; ?></td>
                    <td><?= date('d-m-y h:i A', strtotime($ob->updated)); ?></td>
                    <td>
                        <?php
                        if ($ob->status == 1) echo '<span class="p-1 small text-white bg-success">OPEN</span>';
                        if ($ob->status == 0) echo '<span class="p-1 small text-white bg-dark">CLOSED</span>';
                        ?>
                    </td>
                    <td>

                        <a href="<?= site_url('dashboard/support-del/' . $ob->id); ?>" class="btn btn-xs btn-danger delete">Delete</a>
                    </td>
                    <td>
                        <a href="<?= site_url('dashboard/support-view/' . $ob->id); ?>" class="btn btn-xs btn-primary">View</a>
                        <?php
                        if ($ob->status == 1) {
                        ?>
                            <a href="<?= site_url('dashboard/support-close/' . $ob->id); ?>" class="btn btn-xs btn-dark">Close</a>
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
<script>
    $('a.delete').click(function() {
        if (!confirm('Are you sure to delete?'))
            return false;
    });
</script>