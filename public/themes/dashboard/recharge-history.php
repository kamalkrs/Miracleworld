<h6>Recharge History</h6>
<hr>
<?php
$list = $this->db->order_by("id", "DESC")->get_where("recharge_history", array('user_id' => user_id()))->result();
$ar = array();
$rest = $this->db->order_by('operator', 'ASC')->get_where('sercode', array('status' => 1))->result();
foreach ($rest as $ob) {
    $ar[$ob->scode] = $ob->operator;
}
?>
<div class="box box-p">
    <table class="table">
        <thead>
            <tr>
                <th>Sl No</th>
                <th>Userinfo</th>
                <th>Mobile/DTH Id</th>
                <th>Network</th>
                <th>Type</th>
                <th>Recharge Amount</th>
                <th>Status</th>
                <th>TXN No</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sl = 1;
            foreach ($list as $ob) {
                $resp = json_decode($ob->rech_resp);
                $status = $resp->STATUSCODE == 0 ? 'Success' : 'Failed/Pending';
                $msg = $resp->STATUSMSG;
                $m = $this->db->get_where("users", array("id" => $ob->user_id))->row();
            ?>
                <tr>
                    <td><?= $sl++; ?></td>
                    <td><?= $m->username; ?> <br /><?= $m->first_name . ' ' . $m->last_name; ?></td>
                    <td><?= $ob->mobile_no; ?></td>
                    <td><?= $ar[$ob->rech_provider]; ?></td>
                    <td><?= $ob->rech_type; ?></td>
                    <td><?= $ob->rech_amt; ?></td>
                    <td><?= $status; ?></td>
                    <td><?= $resp->TRNID; ?></td>
                    <td><?= date("d M Y h:i:s a", strtotime($ob->created)); ?></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>