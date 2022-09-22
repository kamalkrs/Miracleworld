<!--start blog-container-->
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
                        <h3 class="card-title text-center">Forget Password</h3>
                        <p class="text-center">Enter your registered Email Id/Username</p>
                        <div class="card-text pt-3">
                            <div v-if="message" class="alert" :class="msgcls">{{ message }}</div>
                            <?= view("alert.php"); ?>
                            <legend>Email Id/Username</legend>
                            <input type="email" v-model="username" class="form-control" />
                            <button v-on:click="doLogin()" class="btn btn-primary">{{ button }}</button>
                            <div class="sign-up py-3">
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
            username: null,
            password: null,
            message: null,
            button: "Submit",
            msgcls: ''
        },
        methods: {
            doLogin: function() {
                if (this.username) {
                    this.button = "Checking...";
                    let url = Appurl + 'reset/?username=' + this.username;
                    fetch(url).then(ab => ab.json())
                        .then(resp => {
                            this.message = resp.message;
                            this.button = "Submit";
                            this.msgcls = resp.success ? 'alert-success' : 'alert-danger';
                            if (resp.success) {
                                window.location = '<?= site_url('dashboard'); ?>'
                            }
                        });
                } else {
                    this.message = "Enter username/Email id";
                    this.msgcls = 'alert-danger';
                }
            }
        }
    });
</script>