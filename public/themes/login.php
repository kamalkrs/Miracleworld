<!--start blog-container-->
<div class="p-4 d-flex justify-content-center align-items-center">
    <a href="#">
        <img src="<?= theme_url('img/logo.png') ?>" width="120" class="img-fluid" />
    </a>
</div>
<style>
    label {
        margin-bottom: 10px;
    }
</style>
<div class="container">
    <div id="login" class="global-container p-2">
        <div class="row">
            <div class="col-sm-6 m-auto">
                <div class="card border-0 login-form mb-5">
                    <div class="card-body">
                        <h3 class="card-title text-center">Log in</h3>
                        <?= front_view('alert'); ?>
                        <div class="card-text">
                            <div v-if="message" class="alert" :class="msgcls">{{ message }}</div>
                            <form>
                                <!-- to error: add class "has-danger" -->
                                <fieldset class="mb-3">
                                    <label>Username</label>
                                    <input type="email" v-model="username" class="form-control text-uppercase" id="exampleInputEmail1">
                                    <label>Password</label>
                                    <input type="password" v-model="password" class="form-control" id="exampleInputPassword1">
                                </fieldset>
                                <p class="text-right"><a href="<?= site_url('forget-password'); ?>" class="forgot" style="font-size:12px;">Forgot password?</a></p>
                                <button v-on:click="doLogin()" type="button" class="btn btn-primary mb-2 btn-block">{{ button }}</button>

                                <div class="sign-up">
                                    Don't have an account? <a href="<?= site_url('signup'); ?>">Create One</a>
                                </div>
                            </form>
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
                    button: "Sign in",
                    msgcls: ''
                },
                methods: {
                    doLogin: function() {
                        if (this.username && this.password) {
                            this.button = "Checking...";
                            let url = Appurl + 'login';
                            axios.post(url, {
                                    user: this.username,
                                    pass: this.password
                                })
                                .then(result => {
                                    let resp = result.data;
                                    this.message = resp.message;
                                    this.button = "Sign in";
                                    this.msgcls = resp.success ? 'alert-success' : 'alert-danger';
                                    if (resp.success) {
                                        window.location = '<?= site_url('dashboard'); ?>'
                                    }
                                });

                        } else {
                            this.message = "Fill Username/Password";
                            this.msgcls = 'alert-danger';
                        }
                    }
                }
            });
        </script>
    </div>
</div>
<!--//end .blog-container-->