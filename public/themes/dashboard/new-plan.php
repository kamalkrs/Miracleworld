<div class="d-flex justify-content-between align-items-center">
    <h5>Available Investment Plans</h5>
</div>
<div class="row">
    <div class="col-sm-8">
        <div class="card card-body">
            <table class="table m-0 text-center">
                <thead>
                    <tr>
                        <th>Sl No</th>
                        <th>Plan name</th>
                        <th>Amount</th>
                        <th>Daily ROI</th>
                        <th>Validity</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (is_array($plans)) {
                        $sl = 1;
                        foreach ($plans as $plan) {
                    ?>
                            <tr>
                                <td><?= $sl++; ?></td>
                                <td><?= $plan->plan_title; ?></td>
                                <td>$ <?= $plan->amount; ?></td>
                                <td><?= $plan->roi; ?>%</td>
                                <td><?= $plan->validity; ?> Days</td>
                                <td class="text-end">
                                    <a href="<?= site_url('dashboard/buy-now/' . $plan->id) ?>" class="btn btn-sm btn-primary">Continue</a>
                                </td>
                            </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>