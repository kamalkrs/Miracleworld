<?php

use Config\AppConfig;

$cfg = new AppConfig();
?>
<div class="page-header">
    <h5>Activate Account</h5>
</div>
<div id="origin" class="row">
    <div class="col-sm-5 m-auto">
        <div v-if="errmsg.length" class="alert" :class="errcls">{{ errmsg }}</div>
        <div class="box">
            <div class="box-body">
                <div class="mb-3">
                    <label>Enter Member Id</label>
                    <div class="d-flex">
                        <input type="text" v-model="userid" @blur="getName" class="form-control me-2">
                        <button @click="getName" class="btn btn-primary">Search</button>
                    </div>
                    <div v-if="sponsor_info.length" class="small text-primary" id="usresp">{{ sponsor_info }}</div>
                </div>
                <div class="mb-3">
                    <label>Amount</label>
                    <input type="text" readonly value="<?= $cfg->joiningAmount; ?>" class="form-control">
                </div>
                <button @click="activate" class=" btn btn-primary">{{ button }}</button>
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
            userid: '',
            button: 'Activate',
            sponsor_info: '',
            valid_sponsor: false,
            errcls: '',
            clicked: false,
            errmsg: ''
        },
        methods: {
            getName: function() {
                let url = ApiUrl + 'userinfo/?username=' + this.userid;
                fetch(url).then(ab => ab.json())
                    .then(resp => {
                        if (resp.success) {
                            let us = resp.data;
                            this.sponsor_info = "Name: " + us.first_name + ' ' + us.last_name;
                            this.valid_sponsor = true;
                        } else {
                            this.sponsor_info = "Invalid Sponsor ID";
                            this.valid_sponsor = false;
                        }
                    });
            },
            activate: function() {
                if (!this.valid_sponsor) {
                    this.errmsg = 'Please enter valid member id';
                    this.errcls = 'alert-danger';
                    return;
                }
                if (this.clicked) {
                    this.errmsg = 'Action in Progress... Please wait';
                    this.errcls = 'alert-warning';
                    return;
                }
                this.clicked = true;

                this.errcls = 'alert-info';
                this.errmsg = "Activating...";
                let url = ApiUrl + 'activate';
                axios.post(url, {
                    from_id: this.fromId,
                    username: this.userid
                }).then(result => {
                    let resp = result.data;
                    this.errmsg = resp.message;
                    this.errcls = resp.success ? 'alert-success' : 'alert-danger';
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