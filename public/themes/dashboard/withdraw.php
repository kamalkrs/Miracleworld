<div class="page-header">
    <h5>Withdraw</h5>
</div>
<div id="origin" class="hgradiant position-relative logo-box footer-margin">
    <div class="row">
        <div class="col-sm-6 m-auto">
            <div class="bg-3 bg-white rounded mybox">
                <div v-if="errmsg.length" class="alert" :class="errcls">{{ errmsg }}</div>
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
                                <h6>Withdrawal Limit</h6>
                                <h2>{{ wallet.withdraw_limit}}</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <div class="mb-3 row">
                        <label class="col-sm-4 control-label col-form-label"> Withdrawal Amount</label>
                        <div class="col-sm-6">
                            <input type="number" @keyup="doCalculate" v-model="amount" min="<?= $withdraw_min; ?>" required placeholder="e.g. <?= $withdraw_min; ?>" class="form-control">
                            <small class="text-muted">Min withdrawal USD <?= $withdraw_min; ?> </small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 control-label col-form-label"> Processing Fee (5%)</label>
                        <div class="col-sm-6">
                            <input readonly class="form-control" :value="fees">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 control-label col-form-label"> Net Payment</label>
                        <div class="col-sm-6">
                            <input readonly class="form-control" :value="payamt">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 control-label col-form-label"> </label>
                        <div class="col-sm-6">
                            <input type="button" @click="withdraw" :value="button" class="btn btn-block btn-primary">
                        </div>
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
            amount: 0,
            payamt: 0,
            fees: 0,
            button: 'Submit',
            errmsg: '',
            errcls: '',
            clicked: false,
            userId: '<?= user_id(); ?>',
            maxLimit: 0,
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
                console.log(url);
                axios.post(url, {
                    user_id: this.userId
                }).then(result => {
                    let resp = result.data;
                    this.wallet = resp.data;
                })
            },

            doCalculate: function() {
                this.fees = (this.amount * 0.05).toFixed(2)
                this.payamt = (this.amount * 0.95).toFixed(2)
            },

            withdraw: function() {
                if (this.clicked) {
                    alert("Action in progress. wait")
                    return;
                }
                if (this.amount < 3) {
                    this.errcls = 'alert-danger';
                    this.errmsg = 'Min withdraw $3'
                    return;
                }
                if (this.amount > this.maxLimit) {
                    this.errcls = 'alert-danger';
                    this.errmsg = 'Max withdrawal is ' + this.maxLimit
                    return;
                }
                this.clicked = true;
                this.button = 'Processing';
                let url = ApiUrl + 'withdraw';
                axios.post(url, {
                        amount: this.amount,
                        user_id: '<?= user_id(); ?>'
                    })
                    .then(result => {
                        let resp = result.data;
                        this.errcls = resp.success ? 'alert-success' : 'alert-danger';
                        this.errmsg = resp.message;
                        if (resp.success) {
                            setTimeout(() => {
                                window.location.reload()
                            }, 3000)
                        } else {
                            this.clicked = false;
                        }
                    })
            },
            getLimit: function() {
                let url = ApiUrl + 'know-withdraw-limit';
                axios.post(url, {
                    user_id: this.userId
                }).then(result => {
                    let resp = result.data;
                    this.maxLimit = resp.data;
                })
            }
        },
        created: function() {
            this.getLimit();
            this.getBalanceInfo()
        }
    })
</script>