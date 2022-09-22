<div class="page-header">
    <h5>Active Plans</h5>
    <a href="<?= site_url('dashboard/new-plan') ?>" class="btn btn-sm btn-primary"> <i class="fa fa-plus-circle"></i> Add More Plan</a>
</div>
<div class="row g-2">
    <?php
    if (is_array($plans)) {
        foreach ($plans as $plan) {
    ?>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body1">
                        <table class="table m-0">
                            <tbody>
                                <tr class="bg-light">
                                    <td>Plan name</td>
                                    <td><?= $plan->plan_title; ?></td>
                                </tr>
                                <tr>
                                    <td>Investment Amount</td>
                                    <td>$<?= $plan->amount; ?></td>
                                </tr>
                                <tr>
                                    <td>Income Earned</td>
                                    <td><?= $user->planIncome($plan->id); ?></td>
                                </tr>
                                <tr>
                                    <td>Start Date</td>
                                    <td><?= date("d M Y", strtotime($plan->start_dt)) ?></td>
                                </tr>
                                <tr>
                                    <td>End Date</td>
                                    <td><?= date("d M Y", strtotime($plan->end_dt)) ?></td>
                                </tr>
                                <tr>
                                    <td>Active</td>
                                    <td>
                                        <?php
                                        if ($plan->status == 1) {
                                            echo '<span class="badge bg-success">Active</span>';
                                        } else {
                                            echo '<span class="badge bg-danger">In-Active</span>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    <?php
        }
    }
    ?>
</div>