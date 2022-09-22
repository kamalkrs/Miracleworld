<div class="page-header">
    <h5>Cron Jobs</h5>
</div>
<div class="card card-info p-3">
    <table class="table">
        <thead>
            <tr>
                <th>Action</th>
                <th>Url</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Database Backup</td>
                <td>
                    <div class="w-100 bg-light p-2">wget <?= site_url('home/cront-backup') ?></div>
                </td>
            </tr>
            <tr>
                <td>Daily ROI</td>
                <td>
                    <div class="w-100 bg-light p-2">wget <?= site_url('home/daily-roi') ?></div>
                </td>
            </tr>
        </tbody>
    </table>
</div>