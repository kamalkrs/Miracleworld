<?php

use Config\AppConfig;

$cfg = new AppConfig();
?>
<div class="page-header">
    <h5>Auto Pool Purchase</h5>
</div>
<div id="origin" class="row">
    <div class="col-sm-6 m-auto">
        <div v-if="errmsg.length" class="alert" :class="errcls">{{ errmsg }}</div>
        <div class="box">
            <div class="box-body">
                <div class="row mb-3">
                    <label class="col-sm-4 control-label">Autopool Quantity</label>
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
                        <button :disabled="disabled" @click="doTopup" class=" btn btn-primary">{{ button }}</button>
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
            qty: 0,
            amount: 0,
            maxAllowed: 0,
            disabled: true
        },
        methods: {
            checkMaxRetopup: function() {
                let url = ApiUrl + 'max-pool-purchase';
                axios.post(url, {
                    user_id: this.fromId
                }).then(result => {
                    let resp = result.data;
                    this.maxAllowed = resp.data;
                })
            },
            doCalculate: function() {
                if (this.qty > this.maxAllowed) {
                    this.errcls = 'alert-danger';
                    this.errmsg = "Max " + this.maxAllowed + " retop-up allowed for today.";
                    this.qty = this.maxAllowed;
                    this.disabled = false;
                } else if (this.qty < 0) {
                    this.errcls = 'alert-danger';
                    this.errmsg = "Min 1 retopup required.";
                    this.qty = 1;
                    this.disabled = true;
                } else {
                    this.disabled = false
                }
                this.amount = this.qty * 10
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
                let url = ApiUrl + 'pool-purchase';
                axios.post(url, {
                    qty: this.qty,
                    amount: this.amount
                }).then(result => {
                    let resp = result.data;
                    console.log(resp);
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
        },
        created: function() {
            this.checkMaxRetopup()
        }
    });
</script>