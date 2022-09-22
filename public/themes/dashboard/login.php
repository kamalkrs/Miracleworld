<style>
    .login {
        border: solid 1px #EEE;
        border-radius: 3px;
    }

    .mb-2 {
        margin-bottom: 15px;
    }

    .p-3 {
        padding: 15px;
    }
</style>
<div id="login" class="login">
    <div class="p-3">
        <h4 class="h5">Account Login</h4>
    </div>
    <div style="background: #EEE; height: 1px"></div>
    <div class="p-3">
        <div v-if="error" class="alert alert-info">{{ errorMsg }} </div>
        <div class="form-group mb-2 row">
            <label class="col-sm-4">Userid</label>
            <div class="col-sm-8">
                <input class="form-control text-uppercase" placeholder="Userid" type="text" v-model="username">
            </div>

        </div>
        <div class="form-group mb-3 row">
            <label class="col-sm-4">Password</label>
            <div class="col-sm-8">
                <input class="form-control" placeholder="Password" type="password" v-model="password">
            </div>

        </div>
        <div class="form-group row">
            <label class="col-sm-4"></label>
            <div class="col-sm-8">
                <button type="button" v-on:click="doLogin()" class="btn btn-dark">{{ button_text }}</button>
            </div>
        </div>
        <p style="font-size: 13px;">Don't have an account? <a class="btn btn-sm btn-primary" href="<?= site_url('register'); ?>">Register</a> </p>
        <p style="font-size: 13px;">Forget your password? <a href="<?= site_url('reset'); ?>">Click here</a> </p>
    </div>
</div>

<script>
    var vm = new Vue({
        el: '#login',
        data: {
            username: null,
            password: null,
            error: false,
            errorMsg: 'Login required',
            button_text: 'Login'
        },
        methods: {
            doLogin: function() {
                if (this.username && this.password) {
                    this.error = true;
                    this.errorMsg = "Processing";
                    let url = ApiUrl + 'login/?user=' + this.username + '&pass=' + this.password;
                    fetch(url).then(ab => ab.json())
                        .then(resp => {
                            this.error = true;
                            this.errorMsg = resp.message;
                            if (resp.status) {
                                window.location = '<?= site_url('dashboard'); ?>';
                            }
                        });
                } else {
                    this.error = true;
                    this.errorMsg = "Please fill all details";
                }
            }
        }
    });
</script>