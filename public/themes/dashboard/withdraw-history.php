<div class="page-headers">
    <h5>Withdrawal Reports</h5>
</div>
<div class="hgradiant position-relative logo-box footer-margin">
    <div class="bg-3 p-3 bg-white mybox">
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>SN</th>
                        <th>Req. Amount</th>
                        <th>Rcv. Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sl = 1;
                    foreach ($reqlist as $ob) {
                    ?>
                        <tr>
                            <td><?= $sl++; ?></td>
                            <td><?= usd_rs($ob->amount); ?></td>
                            <td><?= usd_rs($ob->paid_total); ?></td>
                            <td><?php
                                if ($ob->status == 0) echo "<span class='badge bg-warning'>Pending</span>";
                                if ($ob->status == 1) echo "<span class='badge bg-success'>Approved</span>";
                                if ($ob->status == 2) echo "<span class='badge bg-danger'>Rejected</span>";
                                if ($ob->status == 3) echo "<span class='badge bg-dark'>Cancelled</span>";
                                ?></td>
                            <td><?= date('jS M, Y H:i', strtotime($ob->created)); ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>