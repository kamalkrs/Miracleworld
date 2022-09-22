<div class="page-header">
    <h5>Email Templates</h5>
</div>
<div class="card card-info">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Sl No</th>
                    <th>Template name</th>
                    <th>Email Subject</th>
                    <th>Status</th>
                    <th width="100">Options</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sl = 1;
                foreach ($items as $item) {
                ?>
                    <tr>
                        <td><?= $sl++; ?></td>
                        <td><?= $item->email_heading; ?></td>
                        <td><?= $item->email_subject; ?></td>
                        <td><?= $item->status ? '<span class="badge bg-success">Enabled</span>' : '<span class="badge bg-danger">Disabled</span>' ?></td>
                        <th>
                            <a href="<?= admin_url('settings/edit-template/' . $item->id) ?>" class="btn btn-primary btn-xs"> <i class="mdi mdi-pencil"></i> Edit</a>
                        </th>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>