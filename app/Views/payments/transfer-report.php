<div class="page-header">
    <h5>Fund Transfer Report</h5>
</div>
<div class="card card-info p-4">
    <table class="table data-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Username</th>
                <th>CR/DR</th>
                <th>Amount</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sl = 1;
            foreach ($list as $m) {
            ?>
                <tr>
                    <td><?= date("d M, Y h:i A", strtotime($m->created)); ?></td>
                    <td><?= id2userid($m->user_id); ?></td>
                    <td><?= strtoupper($m->cr_dr); ?></td>
                    <td>$<?= $m->amount; ?></td>
                    <td>$<?= $m->total_bal; ?></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>