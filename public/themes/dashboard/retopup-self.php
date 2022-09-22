<?php

use Config\AppConfig;

$cfg = new AppConfig();
?>
<div class="page-header">
    <h5>Retop-up Self Account</h5>
</div>
<div id="origin" class="row">
    <div class="col-sm-6 m-auto">
        <div v-if="errmsg.length" class="alert" :class="errcls">{{ errmsg }}</div>
        <div class="box">
            <div class="box-body">
                <div class="row mb-3">
                    <label class="col-sm-4 control-label">Self Retoup Quantity</label>
                    <div class="col-sm-6">
                        <input type="text" @keyup="doCalculate" v-model="qty" class="form-control me-2">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-4 control-label">Amount</label>
                    <div class="col-sm-6">
                        <input type="text" readonly :value="amount" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label"> Fund Balance </label>
                    <div class="col-md-6"> <?= $balance; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 offset-sm-4">
                        <button @click="doTopup" class=" btn btn-primary">{{ button }}</button>
                    </div>
                </div>

            </div>
        </div>
        <div class="box bg-danger text-white">
            <div class="box-body text-center">
                Don't have Sufficient Funds ? <a class="text-warning" href="<?= site_url('dashboard/add-funds') ?>">Click here</a>
            </div>
        </div>
    </div>
</div>

<script>
    var vm = new Vue({
        el: '#origin',
        data: {
            fromId: <?= user_id(); ?>,
            button: 'Submit',
            errcls: '',
            clicked: false,
            errmsg: '',
            qty: 1,
            amount: 27
        },
        methods: {
            doCalculate: function() {
                this.amount = this.qty * 27
            },
            doTopup: function() {
                if (this.clicked) {
                    this.errmsg = 'Action in Progress... Please wait';
                    this.errcls = 'alert-warning';
                    return;
                }
                this.clicked = true;
                this.errcls = 'alert-info';
                this.errmsg = "Processing...";
                let url = ApiUrl + 'retopup';
                axios.post(url, {
                    qty: this.qty,
                    amount: this.amount
                }).then(result => {
                    let resp = result.data;
                    this.errmsg = resp.message;
                    this.errcls = resp.success ? 'alert-success' : 'alert-danger';
                    this.clicked = false;
                    if (resp.success) {
                        setTimeout(() => {
                            window.location.reload()
                        }, 2000)
                    }
                })
            }
        }
    });
</script>