<h5>Topup Account</h5>
<hr>
<div id="origin" class="row">
    <div class="col-sm-6">
        <div class="box box-p">
            <form method="POST" action="<?= site_url('dashboard/upgrade'); ?>" class="form-horizontal">
                <p><i>Your account will be upgraded to next selected rank.</i></p>
                <hr>
                <div class="form-group row">
                    <label class="col-sm-4">Userid</label>
                    <div class="col-sm-8">
                        <input type="text" name="user_id" v-on:blur="doValidate()" v-model="userid" class="form-control text-uppercase" />
                        <small class="text-white" v-bind:class="txtClass" style="font-weight: normal;">{{ respval }}</small>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Amount: </label>
                    <div class="col-md-6">
                        <input type="text" name="amount" class="form-control" />
                        <div>
                            <span class="text-muted small">Fund Balance: <?= $current_balance; ?></span>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4"></label>
                    <div class="col-sm-4">
                        <button type="submit" name="btn" value="upgrade" class="btn btn-primary">Upgrade Now</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    var vm = new Vue({
        el: '#origin',
        data: {
            userid: '<?= isset($_GET['username']) ? $_GET['username'] : '' ?>',
            respval: null,
            txtClass: ''
        },
        methods: {
            doValidate: function() {
                let url = ApiUrl + 'userinfo/?username=' + this.userid;
                fetch(url).then(ab => ab.json())
                    .then(resp => {
                        this.txtClass = resp.success ? 'badge bg-success' : 'badge bg-danger';
                        if (resp.success) {
                            this.respval = resp.data.first_name + ' ' + resp.data.last_name;
                        } else {
                            this.userid = '';
                            this.respval = 'Invalid sponsor id';
                        }
                    });
            }
        },
        created: function() {
            if (this.userid != '') {
                this.doValidate();
            }
        }
    });
</script>