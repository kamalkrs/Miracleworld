<h5>Fund Transfer</h5>
<hr />

<div class="row">
    <div class="col-sm-6">
        <div class="box bg-white">
            <div class="p-3 align-items-center" id="funds">
                <form class="form-horizontal" method="POST" action="<?= site_url('dashboard/fund-transfer'); ?>">
                    <div class="form-group row">
                        <label class="col-sm-4 control-label">Fund Balance </label>
                        <div class="col-md-8">
                            <i class="fa fa-usd"></i> <?= $balance; ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 control-label">Amount <span class="required">*</span> </label>
                        <div class="col-md-8">
                            <input class="form-control" type="number" v-model="amount" placeholder="0.00" name="amount">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 control-label">Transfer To <span class="required">*</span></label>
                        <div class="col-sm-7">
                            <input class="form-control text-uppercase" v-on:blur="getName()" type="text" v-model="userid" placeholder="Username" name="to">
                            <div v-if="sponsor_info.length" class="small" :class="errcls" id="usresp">{{ sponsor_info }}</div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4"></div>
                        <div class="col-md-8">
                            <button class="btn btn-success" type="submit" name="save" value="Submit">Submit</button>
                            <button type="reset" class="btn btn-danger">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    var vm = new Vue({
        el: '#funds',
        data: {
            fromId: <?= user_id(); ?>,
            amount: 100,
            userid: null,
            button: 'Transfer',
            sponsor_info: '',
            valid_sponsor: false,
            errcls: ''
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
                            this.errcls = 'text-success';
                        } else {
                            this.sponsor_info = "Invalid Sponsor ID";
                            this.valid_sponsor = false;
                            this.errcls = 'text-danger';
                        }
                    });
            }
        }
    });
</script>