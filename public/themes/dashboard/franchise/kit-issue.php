<h5>Issue Joining Kit</h5>
<hr />
<div id="kit" class="box p-3">
    <div v-if="errmsg != ''" class="alert" v-bind:class="{ 'alert-success': success, 'alert-danger': !success }"> {{ errmsg }} </div>

    <div class="form-group mb-3 row">
        <label class="col-sm-2 col-form-label">Enter Username</label>
        <div class="col-sm-3">
            <input type="text" v-model="username" v-on:keyup.enter="search()" class="form-control text-uppercase" placeholder="BW">
        </div>
        <div class="col-sm-2">
            <button type="button" v-on:click="search()" class="btn btn-primary"> Search </button>
        </div>
    </div>
    <div class="row form-group">
        <label class="col-sm-2 col-form-label">First name</label>
        <div class="col-sm-3">
            <input type="text" v-model="user.first_name" readonly class="form-control" />
        </div>
        <label class="col-sm-2 col-form-label">Last name</label>
        <div class="col-sm-3">
            <input type="text" readonly v-model="user.last_name" class="form-control" />
        </div>
    </div>
    <div class="row form-group">
        <label class="col-sm-2 col-form-label">Mobile</label>
        <div class="col-sm-3">
            <input type="text" readonly v-model="user.mobile" class="form-control" />
        </div>
        <label class="col-sm-2 col-form-label">Join Kit</label>
        <div class="col-sm-3">
            <input type="text" readonly v-model="user.plan_total" class="form-control" />
        </div>
    </div>
    <hr />
    <h6>Receiver Info</h6>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Username</label>
        <div class="col-sm-3">
            <input type="text" v-model="ruser" v-on:keyup.enter="rsearch()" class="form-control text-uppercase">
        </div>
        <div class="col-sm-2">
            <button type="button" v-on:click="rsearch()" class="btn btn-primary"> Search </button>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Name</label>
        <div class="col-sm-3">
            <input type="text" readonly v-model="rname" class="form-control">
        </div>
        <label class="col-sm-2 col-form-label">Mobile</label>
        <div class="col-sm-3">
            <input type="text" readonly v-model="rmobile" class="form-control">
        </div>
        <!-- <label class="col-sm-2 col-form-label">Notes</label>
        <div class="col-sm-3">
            <input type="text" v-model="rcomment" class="form-control">
        </div> -->
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label"></label>
        <div class="col-sm-3">
            <button v-bind:disabled="btnClicked" v-on:click="submitOrder()" class="btn btn-primary"> {{ buttonText }}</button>
        </div>
    </div>
</div>

<script>
    let vm = new Vue({
        el: '#kit',
        data: {
            username: '',
            buttonText: 'Submit',
            btnClicked: false,
            errmsg: '',
            success: false,
            user: {
                first_name: '',
                last_name: '',
                mobile: '',
                plan_total: '',
                ac_status: '',
                kit_issue: 0
            },
            rname: '',
            rmobile: '',
            rcomment: '',
            ruser: ''
        },
        methods: {
            search: function() {
                if (this.username == '') {
                    this.errmsg = "Please enter username";
                    this.success = false;
                    return;
                }
                this.errmsg = '';
                let url = '<?= api_url('userinfo') ?>' +
                    '&username=' + this.username;
                fetch(url).then(ab => ab.json())
                    .then(resp => {
                        this.success = resp.status;
                        if (resp.status) {
                            this.user = resp.data;
                            if (this.user.kit_issue == 1) {
                                this.errmsg = "Joining Kit already issued on " + this.user.kit_issue_date;
                                this.success = false;
                            }
                        } else {
                            this.errmsg = resp.message;
                            this.user = {
                                first_name: '',
                                last_name: '',
                                mobile: '',
                                plan_total: '',
                                ac_status: ''
                            };
                            this.rname = this.rmobile = this.rcomment = '';
                        }

                    });
            },
            submitOrder: function() {
                if (this.user.kit_issue == 1) {
                    this.errmsg = "Joining Kit already issued on " + this.user.kit_issue_date;
                    this.success = false;
                } else {
                    if (this.success) {
                        this.buttonText = "Saving...";
                        this.btnClicked = true;
                        let url = '<?= api_url('kit-issue'); ?>' +
                            'username=' + this.username + '&ruser=' + this.ruser + '&issue_by=' + '<?= user_id(); ?>'
                        fetch(url).then(ab => ab.json())
                            .then(resp => {
                                this.errmsg = resp.message;
                                this.success = resp.status;
                                this.buttonText = "Saved!!";
                            });
                    } else {
                        this.errmsg = "Please fix the errors";
                    }
                }
            },
            rsearch: function() {
                this.errmsg = '';
                let url = '<?= api_url('userinfo') ?>' +
                    '&username=' + this.ruser;
                fetch(url).then(ab => ab.json())
                    .then(resp => {
                        console.log(resp);
                        if (resp.status == false) {
                            this.errmsg = resp.message;
                            this.success = false;
                        } else {
                            let us = resp.data;
                            this.rname = us.first_name + ' ' + us.last_name;
                            this.rmobile = us.mobile;
                        }
                    });
            }
        }
    })
</script>