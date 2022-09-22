<style>
    .text-danger {
        color: red;
    }

    .text-success {
        color: green;
    }
</style>
<div class="p-4 d-flex justify-content-center align-items-center">
    <a href="#">
        <img src="<?= theme_url('img/logo.png') ?>" width="120" class="img-fluid" />
    </a>
</div>
<div class="container">
    <div id="login" class="global-container p-2">
        <div class="row">
            <div class="col-sm-6 m-auto">
                <div class="card border-0 login-form">
                    <div class="card-body">
                        <h3 class="card-title text-center">Create an account</h3>
                        <div class="card-text">
                            <div v-if="message" class="alert" :class="msgcls">{{ message }}</div>
                            <form action="<?= site_url('signup'); ?>" method="post">
                                <div class="login-inner p-4">
                                    <?= view("alert.php"); ?>
                                    <label>Referral Id</label>
                                    <input type="text" required v-on:blur="doValidate()" v-model="sp" name="sponsor_id" class="form-control text-uppercase" />
                                    <div class="mb-3"><small v-bind:class="txtClass" style="font-weight: normal;">{{ respval }}</small></div>
                                    <label>Name</label>
                                    <input type="text" name="form[first_name]" class="form-control" value="<?= set_value('form[first_name]', ''); ?>" />
                                    <label>Mobile No</label>
                                    <input type="tel" required name="form[mobile]" minlength="10" maxlength="12" class="form-control" value="<?= set_value('form[mobile]', ''); ?>" />
                                    <label>Email Address</label>
                                    <input type="email" required name="form[email_id]" class="form-control" value="<?= set_value('form[email_id]', ''); ?>" />
                                    <label>Password</label>
                                    <input :type="tpetxt" required name="form[passwd]" class="form-control" value="<?= set_value('form[passwd]', ''); ?>" />
                                    <div @click="togglePass()" class="eye-view"><i class="fa" :class="icon"></i></div>
                                    <label>Confirm Password</label>
                                    <input :type="tpetxt" required name="cnfpass" class="form-control" />
                                    <div class="d-flex forget-passwd terms">
                                        <label class="checkbox">
                                            <input type="checkbox" value="1" name="policy" />
                                            I accept all <a target="_blank" href="<?= site_url('terms') ?>" class="text-dark">terms and policy</a>
                                        </label>
                                    </div>
                                    <div class="p-4 text-center">
                                        <button name="save" value="Save" class="btn btn-primary ">Create account</button>
                                    </div>
                                </div>
                            </form>
                            <div class="sign-up text-center">
                                Already have login ? <a href="<?= site_url('login') ?>">Click here</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var Appurl = '<?= site_url('api/site/'); ?>';
    var vm = new Vue({
        el: '#login',
        data: {
            tp: 1,
            lbl: 'Sponsor ID',
            sp: '<?= isset($_GET['ref']) ? $_GET['ref'] : ''; ?>',
            respval: null,
            txtClass: '',
            tpetxt: 'password',
            icon: 'fa-eye-slash',
            message: ''
        },
        methods: {
            doValidate: function() {
                let url = Appurl + 'userinfo/?username=' + this.sp;
                fetch(url).then(ab => ab.json())
                    .then(resp => {
                        this.txtClass = resp.success ? 'text-success' : 'text-danger';
                        if (resp.success) {
                            this.respval = resp.data.first_name + ' ' + resp.data.last_name;
                        } else {
                            this.sp = '';
                            this.respval = 'Invalid sponsor id';
                        }
                    });
            },
            togglePass: function() {
                if (this.tpetxt == 'text') {
                    this.tpetxt = 'password'
                    this.icon = 'fa-eye-slash'
                } else {
                    this.tpetxt = 'text';
                    this.icon = 'fa-eye'
                }
            }
        },
        created: function() {
            if (this.sp !== '') {
                this.doValidate();
            }
        }
    });
</script>