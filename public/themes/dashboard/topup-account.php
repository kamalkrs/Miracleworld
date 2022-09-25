<?php

use Config\AppConfig;

$app = new AppConfig();
?>
<h5>Topup Account</h5>
<hr />

<div id="app" class="row">
    <div class="col-sm-6 m-auto">
        <div class="box mb-3">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-9">
                        <input type="text" @blur="search" v-model="user_id" placeholder="Enter Username" class="form-control text-uppercase">
                    </div>
                    <div class="col-sm-3">
                        <button type="button" @click="search()" class="btn btn-block btn-primary">Search</button>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="message.length" class="alert" :class="errcls">{{ message }} </div>
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
                <input type="hidden" name="username" v-model="user_id">
                <p><b>User Details: </b></p>
                <div v-if="status" style="border: dashed 1px #DDD; font-size: 12px; background: #EEE; padding: 10px; border-radius: 3px; margin-bottom: 20px;">
                    <b>Name: </b>{{ name }}<br />
                    <b>Mobile: </b>{{ mobile }}<br />
                    <b>Username: </b>{{ username }}
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
                <div class="form-group row">
                    <label class="col-sm-4 control-label"> Amount </label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" readonly value="<?= $app->joiningAmount; ?>">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-4"></div>
                    <div class="col-md-8">
                        <button :disabled="!status" @click="activate" class="btn btn-success">{{ button }} </button>
                        <button type="reset" class="btn btn-danger">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="box bg-danger text-white">
            <div class="box-body text-center">
                Don't have sufficient Funds ? <a class="text-warning" href="<?= site_url('dashboard/add-funds') ?>">Click here</a>
            </div>
        </div>
    </div>
</div>

<script>
    var vm = new Vue({
        el: '#app',
        data: {
            fromId: '<?= user_id(); ?>',
            user_id: null,
            name: null,
            mobile: null,
            username: null,
            loader: false,
            errcls: '',
            message: '',
            status: false,
            clicked: false,
            button: 'Activate Now',
            wallet_id: 1,
            wallet: {
                balance: '-',
                withdraw_limit: '-',
                activation_wallet: '-'
            }
        },
        methods: {
            getBalanceInfo: function() {
                let url = ApiUrl + 'get-balance-info'
                axios.post(url, {
                    user_id: this.fromId
                }).then(result => {
                    let resp = result.data;
                    this.wallet = resp.data;
                })
            },
            search: function() {
                let url = ApiUrl + 'userinfo/?username=' + this.user_id;
                this.error = true;
                this.status = false;
                this.message = "Checking...";
                fetch(url).then(ab => ab.json())
                    .then(resp => {
                        if (resp.success) {
                            let us = resp.data;
                            if (us.ac_status == 1) {
                                this.errcls = 'alert-danger';
                                this.message = "Account is already active";
                            } else {
                                this.status = true;
                                this.errcls = 'alert-success';
                                this.message = "Record found,  Please verify before activation.";
                                this.name = us.first_name;
                                this.mobile = us.mobile;
                                this.username = us.username;
                            }
                        } else {
                            this.errcls = 'alert-danger';
                            this.message = resp.message;
                        }
                    });
            },
            activate: function() {
                if (!this.status) {
                    this.message = 'Please enter valid member id';
                    this.errcls = 'alert-danger';
                    return;
                }
                if (this.clicked) {
                    this.message = 'Action in Progress... Please wait';
                    this.errcls = 'alert-warning';
                    return;
                }
                this.clicked = true;
                this.button = 'Activating...';

                let url = ApiUrl + 'activate';
                axios.post(url, {
                    username: this.user_id,
                    wallet: this.wallet_id
                }).then(result => {
                    let resp = result.data;
                    console.log(resp);
                    this.message = resp.message;
                    this.errcls = resp.success ? 'alert-success' : 'alert-danger';
                    if (resp.success) {
                        this.button = "Activated Successfully";
                        setTimeout(() => {
                            window.location.reload()
                        }, 2000)
                    } else {
                        this.button = 'Activate Now';
                        this.clicked = false;
                    }
                })
            }
        },
        created: function() {
            this.getBalanceInfo();
        }
    });
</script>