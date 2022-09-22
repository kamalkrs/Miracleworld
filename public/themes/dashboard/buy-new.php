<div class="page-header">
    <h5>Buy new plan</h5>
</div>

<div id="origin" class="row">
    <div class="col-sm-6">
        <div v-if="errmsg.length" class="alert" :class="errcls">{{ errmsg }}</div>
        <div class="card">
            <div class="card-body1">
                <div class="p-2 border-bottom d-flex justify-content-between">
                    <b>Plan name: </b>
                    <span><?= $plan->plan_title; ?></span>
                </div>
                <div class="p-2 border-bottom d-flex justify-content-between">
                    <b>Plan Value: </b>
                    <span>$ <?= $plan->amount; ?></span>
                </div>
                <div class="p-2 border-bottom d-flex justify-content-between">
                    <b>ROI: </b>
                    <span><?= $plan->roi; ?> %</span>
                </div>
                <div class="p-2 border-bottom d-flex justify-content-between">
                    <b>Validity: </b>
                    <span><?= $plan->validity; ?> days</span>
                </div>
                <div class="p-2 d-flex justify-content-between">
                    <b>Your Balance: </b>
                    <span> $ <?= $balance; ?></span>
                </div>
                <div class="p-2">
                    <button @click="buyPlan()" :disabled="disabled" class="btn btn-sm btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let vm = new Vue({
        el: '#origin',
        data: {
            user_id: '<?= user_id(); ?>',
            plan_id: '<?= $plan->id; ?>',
            errmsg: '',
            errcls: '',
            disabled: false
        },
        methods: {
            buyPlan: function() {
                let url = ApiUrl + 'buy-plan';
                let form = new FormData();
                form.append('user_id', this.user_id);
                form.append('plan_id', this.plan_id)
                axios.post(url, form)
                    .then(result => {
                        let resp = result.data;
                        this.errmsg = resp.message;
                        if (resp.success) {
                            this.errcls = 'alert-success';
                            this.disabled = true;
                            setTimeout(() => {
                                window.location = '<?= site_url('dashboard/plans') ?>';
                            }, 3000)
                        } else {

                            this.errcls = 'alert-danger';
                        }
                    })
            }
        }
    });
</script>