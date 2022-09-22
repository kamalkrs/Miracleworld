<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14"></script>
<script>
    var ApiUrl = '<?= site_url('api/call/'); ?>';
</script>
<?php $this->load->view("alert.php"); ?>
<div class="box-p p-3" id="signup">
    <form class="form-horizontal" method="POST" action="<?= site_url('register'); ?>">
        <h4>Register an Account</h4>
        <hr />
        <div class="form-group mb-2 row">
            <label class="col-md-3 col-form-label "><span id="jtext">{{ lbl }}</span> <span class="text-danger">*</span> </label>
            <div class="col-md-8">
                <input class="form-control form-control-sm" required type="text" v-on:blur="doValidate()" v-model="sp" id="resinput" name="form[sponsor_id]" placeholder="e.g">
                <small v-bind:class="txtClass" style="font-weight: normal;">{{ respval }}</small>
            </div>
        </div>
        <div class="form-group mb-2 row">
            <label class="col-md-3 col-form-label "><span id="jtext">Joining PIN</span> <span class="text-danger">*</span> </label>
            <div class="col-md-8">
                <input class="form-control form-control-sm" required type="text" name="form[epin]" placeholder="e.g">
            </div>
        </div>
        <div class="form-group mb-2 row">
            <label class="col-md-3 col-form-label ">Full Name <span class="text-danger">*</span> </label>
            <div class="col-md-4">
                <input class="form-control form-control-sm" type="text" required placeholder="First name" value="<?= set_value('form[first_name]', ''); ?>" name="form[first_name]">
            </div>
            <div class="col-md-4">
                <input class="form-control form-control-sm" type="text" required placeholder="Last name" value="<?= set_value('form[last_name]', ''); ?>" name="form[last_name]">
            </div>
        </div>
        <div class="form-group mb-2 row">
            <label class="col-md-3 col-form-label ">Mobile <span class="text-danger">*</span></label>
            <div class="col-md-4">
                <input class="form-control form-control-sm" required type="mobile" placeholder="Mobile no" value="<?= set_value('form[mobile]', ''); ?>" name="form[mobile]">
            </div>
            <!-- <div class="col-md-4">
                <select class="form-control form-control-sm" required name="form[position]">
                    <option value="">Position</option>
                    <option value="1">Left</option>
                    <option value="2">Right</option>
                </select>
            </div> -->
        </div>
        <div class="form-group mb-2 row">
            <label class="col-md-3 col-form-label">Address </label>
            <div class="col-md-8">
                <input class="form-control form-control-sm" type="text" placeholder="Address" value="<?= set_value('form[address]'); ?>" name="form[address]">
            </div>
        </div>
        <div class="form-group mb-2 row">
            <label class="col-md-3 col-form-label ">Aadhar Number </label>
            <div class="col-md-8">
                <input class="form-control form-control-sm" type="text" placeholder="Aadhar number" value="<?= set_value('form[adhar_no]'); ?>" name="form[adhar_no]">
            </div>
        </div>

        <div class="form-group mb-3 row">
            <label class="col-sm-3"></label>
            <div class="col-sm-9" style="font-size: 12px; color: #888">
                <input type="checkbox" checked disabled>
                I Agree the <a href="<?= site_url('terms'); ?>" target="_blank">Terms & Conditions</a> and <a href="<?= site_url('privacy-policy'); ?>" target="_blank">Privacy Policy</a>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-3"></label>
            <div class="col-sm-6">
                <button class="btn btn-primary" type="submit" name="save" value="Create">Create</button>
                <a href="<?= site_url(); ?>" class="btn btn-dark">Cancel</a>
            </div>
        </div>
    </form>
</div>
<p class="box p-2">
</p>
<script>
    var vm = new Vue({
        el: '#signup',
        data: {
            tp: 1,
            lbl: 'Sponsor ID',
            sp: '<?= isset($_GET['ref']) ? $_GET['ref'] : ''; ?>',
            respval: null,
            txtClass: ''
        },
        methods: {
            setType: function(i) {
                if (i == 1) this.lbl = "SPONSOR ID";
                if (i == 2) this.lbl = "JOINING PIN";
                this.sp = this.respval = null;
            },
            doValidate: function() {
                let url = '<?= site_url('api/ajax_signup_check'); ?>/?txt=' + this.sp + '&type=' + this.tp;
                fetch(url).then(ab => ab.json())
                    .then(resp => {
                        this.respval = resp.message;
                        this.txtClass = resp.success ? 'badge bg-success' : 'badge bg-danger';
                        if (resp.success == false) {
                            this.sp = '';
                        }
                    });
            }
        },
        created: function() {
            if (this.sp !== '') {
                this.doValidate();
            }
        }
    });
</script>