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
            <div class="box-body border-bottom">
                <div class="row g-2 text-center">
                    <div class="col">
                        <div class="py-4 bg-light">
                            <h6>Main Balance</h6>
                            <h2>{{ wallet.balance }}</h2>
                        </div>
                    </div>
                    <div class="col">
                        <div class="py-4 bg-light">
                            <h6>Activation Wallet</h6>
                            <h2>{{ wallet.activation_wallet}}</h2>
                        </div>
                    </div>
                    <div class="col">
                        <div class="py-4 bg-light">
                            <h6>Withdrawal Limit</h6>
                            <h2>{{ wallet.withdraw_limit}}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-body">
                <div class="row mb-3">
                    <label class="col-sm-4 control-label">Username</label>
                    <div class="col-sm-6">
                        <input type="text" @blur="getName" v-model="username" class="form-control text-uppercase me-2">

                        <div v-if="sponsor_info.length" class="small text-primary" id="usresp">{{ sponsor_info }}</div>
                    </div>

                </div>
                <div class="row mb-3">
                    <label class="col-sm-4 control-label">Wallet</label>
                    <div class="col-sm-6">
                        <select v-model="wallet_id" class="form-select">
                            <option value="1">Main Wallet ({{ wallet.balance }}) </option>
                            <option value="2">Activation Wallet ({{ wallet.activation_wallet }}) </option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-4 control-label">Retopup Quantity</label>
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
            amount: 27,
            username: '',
            sponsor_info: '',
            wallet_id: 1,
            wallet: {
                balance: '-',
                withdraw_limit: '-',
                activation_wallet: '-'
            }
        },
        methods: {
            getName: function() {
                let url = ApiUrl + 'userinfo/?username=' + this.username;
                fetch(url).then(ab => ab.json())
                    .then(resp => {
                        if (resp.success) {
                            let us = resp.data;
                            this.sponsor_info = "Name: " + us.first_name + ' ' + us.last_name;
                        } else {
                            this.sponsor_info = "Invalid Sponsor ID";
                            this.username = ''
                        }
                    });
            },
            getBalanceInfo: function() {
                let url = ApiUrl + 'get-balance-info'
                axios.post(url, {
                    user_id: this.fromId
                }).then(result => {
                    let resp = result.data;
                    console.log(resp)
                    this.wallet = resp.data;
                })
            },
            doCalculate: function() {
                this.amount = this.qty * 27
            },
            doTopup: function() {
                if (this.username == '') {
                    this.errmsg = 'Username field is required'
                    this.errcls = 'alert-danger'
                    return;
                }
                if (this.clicked) {
                    this.errmsg = 'Action in Progress... Please wait';
                    this.errcls = 'alert-warning';
                    return;
                }
                this.clicked = true;
                this.errcls = 'alert-info';
                this.errmsg = "Processing...";
                let url = ApiUrl + 'retopup-others';
                axios.post(url, {
                    qty: this.qty,
                    amount: this.amount,
                    username: this.username,
                    wallet: this.wallet_id
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
        },
        created: function() {
            this.getBalanceInfo()
        }
    });
</script>