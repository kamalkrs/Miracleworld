<h5>Create new account</h5>
<hr>
<div id="register" class="row">
    <div class="col-sm-8">
        <div class="box">
            <div class="box-p">
                <p>Please fill the details carefully. Password will be sent on given mobile no. </p>
                <div v-if="errors.length" class="alert alert-danger">
                    <p>Please correct following errors:</p>
                    <ul>
                        <li v-for="error in errors">{{ error }}</li>
                    </ul>
                </div>
                <form class="form-horizontal" method="POST" action="<?= site_url('dashboard/addnew'); ?>">
                    <div class="form-group row">
                        <label class="col-md-2 text-right">Sponsor Id <span class="required">*</span> </label>
                        <div class="col-md-10">
                            <input v-model="sponsor" v-on:blur="getName()" class="form-control" type="text">
                            <div class="small text-success" id="result">{{ sponsor_info }}</div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-2 text-right">First name <span class="required">*</span> </label>
                        <div class="col-md-4">
                            <input v-model="first_name" class="form-control" type="text" placeholder="First name">
                        </div>
                        <label class="col-md-2 text-right">Last name</label>
                        <div class="col-md-4">
                            <input v-model="last_name" class="form-control" type="text" placeholder="Last name">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-2 text-right">Email Id <span class="required">*</span> </label>
                        <div class="col-md-4">
                            <input v-model="email_id" class="form-control" type="email" placeholder="Email id">
                        </div>
                        <label class="col-md-2 text-right">Password</label>
                        <div class="col-md-4">
                            <input v-model="passwd" class="form-control" type="password" placeholder="Password">
                        </div>
                    </div>

                    <input type="hidden" name="form[position]" value="1" />
                    <div class="form-group row">
                        <label class="col-sm-2"></label>
                        <div class="col-sm-10">
                            <button v-on:click="createAccount()" v-bind:disabled="clicked" class="btn btn-primary" type="button" name="save">{{ button }}</button>
                            <a href="<?= site_url('dashboard/pin-list'); ?>" class="btn btn-dark">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="box">
            <div class="p-2">
                <h4 class="h6 m-0">Terms & Conditions</h4>
            </div>
            <hr class="my-1" />
            <div class="p-2">
                <ul>
                    <li>One PAN Card, One Account</li>
                    <li>Must be valid mobile number.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    var vm = new Vue({
        el: '#register',
        data: {
            errors: [],
            sponsor: '<?= id2userid(user_id()); ?>',
            first_name: '',
            last_name: '',
            mobile: '',
            address: '',
            pin_code: '',
            position: 1,
            pan: '',
            sponsor_info: '',
            plan_total: '0',
            valid_sponsor: true,
            button: 'Create now',
            balance: <?= $balance; ?>,
            clicked: false,
            selected: false,
            email_id: '',
            passwd: ''
        },
        methods: {
            getName: function() {
                let url = ApiUrl + 'userinfo/?username=' + this.sponsor;
                fetch(url).then(ab => ab.json())
                    .then(resp => {
                        if (resp.success) {
                            let us = resp.data;
                            this.sponsor_info = "Name: " + us.first_name + ' ' + us.last_name + ' Mobile: ' + us.mobile;
                            this.valid_sponsor = true;
                        } else {
                            this.sponsor_info = "Invalid Sponsor ID";
                            this.valid_sponsor = false;
                        }
                    });
            },
            createAccount: function() {
                if (this.clicked) {
                    alert("Work in Progress!! Wait please..");
                    return;
                }

                let url = ApiUrl + 'register';
                this.errors = [];

                let plan_v = this.plan_total;
                if (!this.sponsor) this.errors.push("Sponsor Id required");
                if (!this.valid_sponsor) this.errors.push("Please correct sponsor Id");
                if (!this.first_name) this.errors.push("First name is required");
                if (!this.email_id) this.errors.push("Email Id is required");
                if (!this.passwd) this.errors.push("Password is required");
                if (this.mobile && this.mobile.length != 10) this.errors.push("Mobile no must be of 10 digits");
                if (plan_v > this.balance) this.errors.push("You don't have sufficient Fund Balance");
                if (this.errors.length) return false;

                this.clicked = true;
                let sel = this.selected ? 1 : 0;

                url += '/?sponsor=' + this.sponsor + '&first_name=' + this.first_name +
                    '&last_name=' + this.last_name + '&mobile=' + this.mobile + '&pan=' + this.pan +
                    '&position=' + this.position + '&plan_total=' + this.plan_total + '&selected=' + sel +
                    '&address=' + this.address + '&pin_code=' + this.pin_code + '&email_id=' + this.email_id + '&passwd=' + this.passwd;

                this.button = "Creating...";

                fetch(url).then(ab => ab.json())
                    .then(resp => {
                        if (resp.success) {
                            this.button = "Created";
                            location.reload();
                        } else {
                            this.errors.push(resp.message);
                            this.button = "Create now";
                        }
                    });
            },
            toggleSelect: function() {
                this.selected = !this.selected;
            }
        },
        created: function() {
            this.getName();
        }
    })
</script>