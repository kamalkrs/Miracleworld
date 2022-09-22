<div class="page-header">
    <h5>Change Password</h5>
</div>
<div id="origin" class="hgradiant position-relative logo-box footer-margin">
    <div class="row">
        <div class="col-sm-6">
            <div class="bg-3 p-3 bg-white rounded mybox">
                <div v-if="errmsg.length" class="alert" :class="errcls">{{ errmsg }}</div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label for="old-password" class="col-sm-3 col-form-label">Old Password:</label>
                            <div class="col-sm-9">
                                <input type="password" class="form-control" placeholder="Old Password" v-model="oldp">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label for="new-password" class="col-sm-3 col-form-label">New Password:</label>
                            <div class="col-sm-9">
                                <input type="password" class="form-control" placeholder="New Password" v-model="newp">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label for="verify-password" class="col-sm-3 col-form-label">Confirm Password
                                :</label>
                            <div class="col-sm-9">
                                <input type="password" class="form-control" placeholder="Confirm Password" v-model="cnfp">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-3"></label>
                    <div class="col-sm-8">
                        <button type="button" @click="changePassword()" :disabled="clicked" name="submit" value="Submit" class="btn btn-primary">{{ button }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let vm = new Vue({
        el: '#origin',
        data: {
            oldp: '',
            newp: '',
            cnfp: '',
            errmsg: '',
            errcls: '',
            user_id: '<?= user_id(); ?>',
            button: 'Change Password',
            clicked: false
        },
        methods: {
            changePassword: function() {
                if (this.oldp == '' || this.newp == '' || this.cnfp == '') {
                    this.errcls = 'alert-danger';
                    this.errmsg = "Please fill all fields";
                    return;
                }
                if (this.newp.length < 6) {
                    this.errcls = 'alert-danger';
                    this.errmsg = "Password must be min 6 character long";
                    return;
                }
                if (this.newp !== this.cnfp) {
                    this.errcls = 'alert-danger';
                    this.errmsg = "New password and Confirm password not matching"
                    return;
                }
                this.errmsg = '';
                this.clicked = true;
                this.button = "Changing...";
                let url = ApiUrl + 'change-password';
                axios.post(url, {
                        old_pass: this.oldp,
                        new_pass: this.newp,
                        user_id: this.user_id
                    })
                    .then(result => {
                        let resp = result.data;
                        this.errmsg = resp.message;
                        if (resp.success) {
                            this.errcls = 'alert-success';
                        } else {
                            this.errcls = 'alert-danger';
                        }
                    })
            }

        }
    });
</script>