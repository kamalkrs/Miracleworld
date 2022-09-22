<?php
$bal = $this->User_model->getFundBalance(user_id());
$rep = $this->User_model->getRepurchaseIncome(user_id());
?>
<div id="order" class="row">
    <div class="col-sm-8 m-auto">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5>Order Summary</h5>
            </div>
            <a href="<?= site_url('dashboard/clear-cart') ?>" class="btn btn-sm btn-primary">Remove All</a>
        </div>
        <hr>

        <div class="box">
            <div class="box-p">
                <div class="row">
                    <label class="col-sm-3 col-form-label">Select User</label>
                    <div class="col-sm-5">
                        <input type="text" v-model="username" v-on:keyup.enter="search()" placeholder="userid" class="form-control form-control-sm text-uppercase">
                    </div>
                    <div class="col-sm-2">
                        <button type="button" v-on:click="search()" class="btn btn-sm btn-primary ms-3"> <i class="fa fa-search"></i> Search</button>

                    </div>
                </div>
            </div>
        </div>
        <div class="mb-2 bg-white p-2 border-rounded">
            <div v-if="loader">{{ errorMsg }}</div>
            <div v-if="found" class="d-flex">
                <div class="px-2"><b>Name: </b> {{ user.first_name + ' ' + user.last_name }}</div>
                <div class="px-2"><b>Mobile: </b> {{ user.mobile }}</div>
                <div class="px-2"><b>Userid: </b> {{ user.username }}</div>
            </div>
        </div>
        <form action="<?= site_url('franchise/order-confirm'); ?>" method="post">
            <div class="box">
                <div class="box-p">
                    <div class="row">
                        <label class="col-sm-3">Pay via</label>
                        <div class="col-sm-8">
                            <label class="label-checkbox">
                                <input type="radio" checked name="payfrom" value="repurchase"> Repurchase Balance ( <i class="fa fa-inr"></i> {{ repurchase_bal }})
                            </label> <br />
                            <label class="label-checkbox">
                                <input type="radio" name="payfrom" value="fund"> Fund Balance ( <i class="fa fa-inr"></i> {{ fund_bal }})
                            </label> <br />
                            <label class="label-checkbox">
                                <input type="radio" name="payfrom" value="cash"> CASH
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <input type="hidden" name="user_id" v-model="user.id" />
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
                        <div class="col-sm-6">
                            Total Amount: <b><i class="fa fa-inr"></i> <?= $cart->price(); ?> </b>
                        </div>

                        <div class="col-sm-6">
                            <div class="d-flex justify-content-end">
                                <?php
                                if ($cart->price() > 0) {
                                ?>
                                    <div class="row">
                                        <div class="col">
                                            <input type="number" maxlength="4" required placeholder="Purchase Code" name="pcode" class="form-control form-control-sm" />
                                        </div>
                                        <div class="col">
                                            <input type="submit" name="btnorder" value="Confirm Order" class="btn btn-sm btn-block btn-primary">
                                        </div>
                                    </div>

                                <?php
                                } else {
                                ?>
                                    <a href="<?= site_url('franchise/orders') ?>" class="btn btn-sm btn-block btn-primary">Back</a>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    const vm = new Vue({
        el: '#order',
        data: {
            username: '',
            loader: false,
            found: false,
            errorMsg: '',
            user: {
                id: 0,
                first_name: '',
                last_name: '',
                mobile: '',
                username: null
            },
            button_text: 'Submit Order',
            fund_bal: 0,
            repurchase_bal: 0,
            purchase_code: ''
        },
        methods: {
            search: function() {
                if (this.username == '') {
                    this.loader = true;
                    this.errorMsg = "Please enter username to search";
                    return;
                }
                this.loader = true;
                let url = '<?= site_url('api/call/order-user/?username=') ?>' + this.username;
                fetch(url).then(ab => ab.json())
                    .then(resp => {
                        console.log(resp);
                        if (resp.status) {
                            this.found = true;
                            this.loader = false;
                            this.repurchase_bal = resp.data.repurchase;
                            this.fund_bal = resp.data.fund;
                            this.user = resp.data;
                        } else {
                            this.errorMsg = "Invalid Username. Try again";
                            this.found = false;
                        }
                    });

            },
        }
    })
</script>