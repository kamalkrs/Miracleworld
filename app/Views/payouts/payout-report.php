<h5>Payout Reports</h5>
<hr>
<?php
$date1 = isset($_GET['from']) ? $_GET['from'] : null;
$date2 =   isset($_GET['to']) ? $_GET['to'] : null;
?>
<div class="box box-p">
    <form action="<?= admin_url('payout/payout-report') ?>" method="get">
        <div class="row">
            <div class="col-sm-3">
                <label>From</label>
                <input type="text" name="from" placeholder="From" value="<?= $date1; ?>" class="form-control datepicker">
            </div>
            <div class="col-sm-3">
                <label>To</label>
                <input type="text" name="to" placeholder="To" value="<?= $date2; ?>" class="form-control datepicker">
            </div>
            <div class="col-sm-3">
                <div>&nbsp;</div>
                <input type="submit" name="btn" value="Search" class="btn btn-primary">
            </div>
        </div>
    </form>
</div>
<?php
$newlist = array();
if ($this->input->get("btn")) {
    $date1 = $_GET['from'];
    $date2 =   $_GET['to'];
    $this->db->where("DATE(created) BETWEEN '$date1' AND '$date2'");
    $this->db->where("matching >", 0);
    $lists = $this->db->get("report")->result();


    $data = array();
    $ar1 = $ar2 = $ar3 = $ar4 = array();
    foreach ($lists as $ob) {
        if (!in_array($ob->user_id, $data)) {
            $data[] = $ob->user_id;
        }
    }
    $newlist = array();
    foreach ($data as $user_id) {
        $ar1 = 0;
        $us = new stdClass();
        $us->user_id = $user_id;
        foreach ($lists as $ob) {
            if ($ob->user_id == $user_id) {
                $ar1 += $ob->matching;
            }
        }
        $us->matching = $ar1;
        $newlist[] = $us;
    }

    //print_r($newlist);
}
?>
<div class="box box-p">
    <table class="table">
        <thead>
            <tr>
                <th>Sl</th>
                <th>Userinfo</th>
                <th>Matching</th>
                <th>Total</th>
                <th>Admin @ 10%</th>
                <th>TDS @ 5%</th>
                <th>Repurchase @ 5%</th>
                <th>Net Pay</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sl = 1;
            $s1 = 0;
            $sum1 = $sumTotal = $payTotal  = 0;
            foreach ($newlist as $ob) {
                $s1 += $ob->matching;

                $us = $this->db->get_where("users", array("id" => $ob->user_id))->row();
                $name = $us->first_name . "<br />" . $us->username . "<br />Mob: " . $us->mobile;

                $total = $ob->matching * ($us->plan_total / 8);
                $netPay = $total - ($total * 0.10) - ($total * 0.05) - ($total * 0.05);
                $payTotal += $netPay;
                $sumTotal += $total;

            ?>
                <tr>
                    <td><?= $sl++; ?></td>
                    <td><?= $name; ?></td>
                    <td><?= $ob->matching; ?></td>
                    <td><?= $total; ?></td>
                    <td><?= $total * 0.10; ?></td>
                    <td><?= $total * 0.05; ?></td>
                    <td><?= $total * 0.05; ?></td>
                    <td>
                        <?= $netPay; ?>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th></th>
                <th>Total</th>
                <th><?= $s1; ?></th>
                <th> <i class="fa fa-inr"></i> <?= $sumTotal; ?></th>
                <th> <i class="fa fa-inr"></i> <?= $sumTotal * 0.10; ?></th>
                <th> <i class="fa fa-inr"></i> <?= $sumTotal * 0.045; ?></th>
                <th> <i class="fa fa-inr"></i> <?= $sumTotal * 0.045; ?></th>
                <th> <i class="fa fa-inr"></i> <?= $payTotal; ?></th>
            </tr>

        </tfoot>
    </table>
</div>