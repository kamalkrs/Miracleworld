<div class="page-header">
    <h5>Deposit Funds</h5>
</div>
<div id="wallet" class="hgradiant position-relative logo-box footer-margin">
    <div class="bg-3 p-3 bg-white rounded mybox">
        <div class="alert mb-3" v-if="errmsg.length" :class="errcls">{{ errmsg }}</div>
        <div class="form-group mb-3 row">
            <label class="col-sm-2 col-form-label">Fund Deposit</label>
            <div class="col-sm-5">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="pay_type" value="USDT.TRC20" id="btc" checked>
                    <label class="form-check-label" for="btc">
                        TRC20 USDT
                    </label>
                </div>
                <!-- <div class="form-check">
                    <input class="form-check-input" type="radio" name="pay_type" value="LTCT" id="usdt">
                    <label class="form-check-label" for="usdt">
                        Bitcoin
                    </label>
                </div> -->
            </div>
        </div>
        <div class="form-group mb-3 row">
            <label class="col-sm-2 col-form-label">Amount</label>
            <div class="col-sm-3">
                <input type="number" v-model="amount" name="amount" min="<?= $minAmt; ?>" max="<?= $maxAmt; ?>" class="form-control">
                <span class="small text-muted">Min. $<?= $minAmt; ?> and Max: $<?= $maxAmt; ?></span>
            </div>
        </div>
        <div class="form-group mb-3 row">
            <label class="col-sm-2 col-form-label"></label>
            <div class="col-sm-5">
                <input type="button" :disabled="clicked" @click="addFund()" name="submit" :value="btntext" class="btn btn-primary">
            </div>
        </div>
    </div>
</div>

<script>
    let vm = new Vue({
        el: '#wallet',
        data: {
            amount: <?= $minAmt; ?>,
            user_id: <?= user_id(); ?>,
            errmsg: '',
            errcls: '',
            btntext: 'Submit',
            minAmt: <?= $minAmt; ?>,
            clicked: false
        },
        methods: {
            addFund: function() {
                if (parseFloat(this.amount) < parseInt(this.minAmt)) {
                    this.errmsg = 'You must add min. ' + this.minAmt + ' USD';
                    this.errcls = 'alert-danger';
                    return
                }
                this.btntext = 'Saving....';
                let url = ApiUrl + 'add-funds';
                this.clicked = true;

                axios.post(url, {
                        user_id: this.user_id,
                        amount: this.amount
                    })
                    .then(result => {
                        let resp = result.data;
                        console.log(resp);
                        this.errmsg = resp.message;
                        if (resp.success) {
                            let url = '<?= site_url('dashboard/order-view/') ?>' + resp.data.data.order_id;
                            this.errcls = 'alert-success';
                            window.location = url;
                        } else {
                            this.errcls = 'alert-danger';
                        }
                        this.btntext = "Submit"
                    });

            }
        }
    })
</script>