<?php
$cart = [];
if (isset($_SESSION['cart'])) {
    $cart = $_SESSION['cart'];
}
?>
<table class="table">
    <thead>
        <tr>
            <th>Sl No</th>
            <th>Product</th>
            <th>Qty</th>
            <th>Option</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sl = 1;
        foreach ($cart as $ob) {
            $p = $this->db->get_where("products", array("id" => $ob->id))->row();
        ?>
            <tr>
                <td><?= $sl++; ?></td>
                <td><?= $p->ptitle; ?></td>
                <td><?= $ob->qty; ?></td>
                <th><button type="button" data-product="<?= $ob->id; ?>" class="btn btn-remove btn-xs btn-danger"> <i class="fa fa-remove"></i> </button> </th>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>
<?php
if (count($cart) > 0) {
?>
    <div class="d-flex justify-content-between align-item-center">
        <div>
            <button class="btn btn-clear btn-dark btn-xs">Remove All</button>
        </div>
        <div>
            <button class="btn btn-confirm btn-primary btn-sm">Confirm Order !!</button>
        </div>
    </div>
<?php
}
?>