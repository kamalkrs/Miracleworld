<?php

use App\Models\User_model;

$user = new User_model();
$bal = $user->getFundBalance(user_id());
$rep = 0;
?>
<div id="app" class="row">
    <div class="col-sm-8 m-auto">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5>Order Summary</h5>
            </div>
            <a href="<?= site_url('dashboard/clear-cart') ?>" class="btn btn-sm btn-primary">Remove All</a>
        </div>
        <hr>
        <form action="<?= site_url('dashboard/order-confirm'); ?>" method="post">
            <div class="box">
                <div class="box-p">
                    <div class="row">
                        <label class="col-sm-3">Pay via</label>
                        <div class="col-sm-8">
                            <label class="label-checkbox">
                                <input type="radio" checked name="payfrom" value="repurchase"> Repurchase Balance ( <i class="fa fa-inr"></i> <?= $rep; ?>)
                            </label> <br />
                            <label class="label-checkbox">
                                <input type="radio" name="payfrom" value="fund"> Fund Balance ( <i class="fa fa-inr"></i> <?= $bal; ?>)
                            </label> <br />
                            <label class="label-checkbox">
                                <input type="radio" name="payfrom" value="cash"> CASH
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box">
                <div class="box-p">
                    <div class="row">
                        <label class="col-sm-3">Select Franchise</label>
                        <?php
                        
                        $list = $this->db->order_by("first_name", "ASC")->get_where("users", array("franchise" => 1))->result();

                        ?>
                        <div class="col-sm-8">
                            <select name="fuser" class="form-select select2">
                                <?php
                                foreach ($list as $fu) {
                                ?>
                                    <option value="<?= $fu->id; ?>"><?= $fu->first_name . ' (' . $fu->address . ', ' . $fu->city_name . ')'; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box">
                <div class="box-p">
                    <?php
                    $cart = Cart::create();
                    $items = $cart->getItems();
                    ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sl No</th>
                                <th>Product</th>
                                <th>DP/Offer</th>
                                <th>BV</th>
                                <th>Qty</th>
                                <th>Sub Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sl = 1;
                            foreach ($items as $ob) {
                            ?>
                                <tr>
                                    <td><?= $sl++; ?></td>
                                    <td><?= $ob->title; ?></td>
                                    <td><?= $ob->price; ?></td>
                                    <td><?= $ob->bv * $ob->qty; ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?= site_url('dashboard/rem-cart/' . $ob->id); ?>" class="btn btn-outline-dark btn-sm">-</a>
                                            <button type="button" class="btn btn-outline-dark btn-sm"><?= $ob->qty; ?></button>
                                            <a href="<?= site_url('dashboard/add-cart/' . $ob->id); ?>" class="btn btn-outline-dark btn-sm">+</a>
                                        </div>
                                    </td>
                                    <td><?= ($ob->price * $ob->qty); ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                    <hr />
                    <div class="from-group row">
                        <div class="col-sm-9">
                            Total Amount: <b><i class="fa fa-inr"></i> <?= $cart->price(); ?> </b>
                        </div>
                        <div class="col-sm-3">
                            <?php
                            if ($cart->price() > 0) {
                            ?>
                                <input type="submit" name="btnorder" value="Confirm Order" class="btn btn-sm btn-block btn-primary">
                            <?php
                            } else {
                            ?>
                                <a href="<?= site_url('dashboard/products') ?>" class="btn btn-sm btn-block btn-primary">Back</a>
                            <?php
                            }
                            ?>

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>