<h5>Debit/Credit Balance</h5>
<hr>
<div id="app" class="row">
    <div class="col-sm-6">
        <div class="box box-p">
            <div v-if="message" class="alert" :class="msgcls">{{ message }} </div>
            <div class="form-group">
                <label>Username</label>
                <input v-on:blur="getUser" v-model="username" type="text" class="form-control text-uppercase" />
                <div class="py-2">
                    <span v-if="found" class="badge bg-success">{{ userinfo }}</span>
                    <span v-if="!found" class="badge bg-danger">{{ userinfo }}</span>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-6">
                    <label>Debit/Credit</label>
                    <select v-model="dr_cr" class="form-control">
                        <option value="dr">Debit</option>
                        <option value="cr">Credit</option>
                    </select>
                </div>
                <div class="col-sm-6">
                    <label>Amount</label>
                    <input type="text" v-model="amount" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label>Comments</label>
                <input v-model="comment" type="text" class="form-control" />
                <div class="py-2 text-primary">Max 100 Character</div>
            </div>
            <button v-on:click="saveData" class="btn btn-sm btn-primary">{{ button }}</button>
            <a href="<?= admin_url('dashboard') ?>" class="btn btn-sm btn-dark">Cancel</a>
        </div>
    </div>
</div>

<script>
    var vm = new Vue({
        el: '#app',
        data: {
            username: null,
            userinfo: null,
            message: null,
            dr_cr: 'dr',
            amount: 100,
            button: 'Save',
            errors: [],
            message: null,
            comment: '',
            msgcls: '',
            found: false
        },
        methods: {
            getUser: function() {
                let url = ApiUrl + 'userinfo/?username=' + this.username;
                console.log(url);

                this.userinfo = "Checking...";
                fetch(url).then(ab => ab.json())
                    .then(resp => {
                        if (resp.success) {
                            this.msgcls = 'alert-success';
                            this.userinfo = resp.data.first_name + ' ' + resp.data.last_name + ' (' + resp.data.username + ')';
                            this.found = true;
                        } else {
                            this.msgcls = 'alert-danger';
                            this.userinfo = "Opps!! Invalid Username";
                            this.found = false;
                        }
                    });
            },
            saveData: function() {
                if (!this.username) this.errors.push("Please fix error");
                if (this.errors.length) {
                    this.message = "Please fill all the fields";
                    this.msgcls = 'alert-danger';
                    return;
                }
                if (!this.found) {
                    this.message = "Please fill correct username";
                    this.msgcls = 'alert-danger';
                    return;
                }
                this.errors = [];
                this.button = "Saving...";
                let url = ApiUrl + 'drcr/?username=' + this.username + '&dr=' + this.dr_cr + '&amount=' + this.amount + '&msg=' + this.comment;
                fetch(url).then(ab => ab.json())
                    .then(resp => {
                        this.message = resp.message;
                        if (resp.success) {
                            this.username = this.userinfo = this.amount = null;
                        }
                        this.button = "Save";
                    });
            }
        }
    });
</script>