<div class="page-header">
    <h5>Withdraw</h5>
</div>
<div id="origin" class="hgradiant position-relative logo-box footer-margin">
    <div class="row">
        <div class="col-sm-6 m-auto">
            <div class="bg-3 p-3 bg-white rounded mybox">
                <div v-if="errmsg.length" class="alert" :class="errcls">{{ errmsg }}</div>
                <h6>Available Balance: USD <?= $wallet_bal; ?></h6>
                <hr />
                <div class="mb-3 row">
                    <div class="col-sm-6">
                        <label for="">Withdrawal Amount</label>
                        <input type="number" @keyup="payamt=amount-2" v-model="amount" min="<?= $withdraw_min; ?>" required placeholder="e.g. <?= $withdraw_min; ?>" class="form-control">
                        <small class="text-muted">Min withdrawal USD <?= $withdraw_min; ?> </small>
                    </div>
                    <div class="col-sm-6">
                        <label>Withdrawal Limit</label>
                        <div>
                            <span class="d-inline-block text-white bg-dark p-2">${{ maxLimit }}</span>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="">Amount after processing fee</label>
                    <input readonly class="form-control" :value="payamt">
                </div>
                <div class="mb-3">
                    <input type="button" @click="withdraw" :value="button" class="btn btn-block btn-primary">
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
            button: 'Submit',
            errmsg: '',
            errcls: '',
            clicked: false,
            userId: '<?= user_id(); ?>',
            maxLimit: 0
        },
        methods: {
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
                this.clicked = true;
                this.button = 'Processing';
                let url = ApiUrl + 'withdraw';
                axios.post(url, {
                        amount: this.amount,
                        user_id: '<?= user_id(); ?>'
                    })
                    .then(result => {
                        let resp = result.data;
                        console.log(resp)
                        this.errcls = resp.success ? 'alert-success' : 'alert-danger';
                        this.errmsg = resp.message;
                        if (resp.success) {
                            setTimeout(() => {
                                window.location.reload()
                            }, 3000)
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
        }
    })
</script>