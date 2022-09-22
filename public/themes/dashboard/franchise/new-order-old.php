<div id="order">
    <div class="mb-2">
        <div class="bg-white p-2 border-rounded">
            <div class="d-flex align-items-center">
                <a href="<?= site_url('franchise/orders') ?>" class="btn btn-primary me-3"> <i class="fa fa-arrow-left"></i> Go Back</a>
                <input type="text" v-model="username" v-on:keyup.enter="search()" placeholder="userid" style="flex: 1;" class="form-control text-uppercase">
                <button type="button" v-on:click="search()" class="btn btn-primary ms-3"> <i class="fa fa-search"></i> Search</button>
            </div>
        </div>
    </div>
    <div class="mb-2 bg-white p-2 border-rounded">
        <div v-if="loader">{{ errorMsg }}</div>
        <div v-if="found" class="d-flex">
            <div class="px-2"><b>Name: </b> {{ user.first_name + ' ' + user.last_name }}</div>
            <div class="px-2"><b>Mobile: </b> {{ user.mobile }}</div>
            <div class="px-2"><b>Userid: </b> {{ user.username }}</div>
        </div>
    </div>
    <div a-if="found">
        <div class="mb-2 bg-white d-flex border-rounded">
            <table class="table m-0">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Product name</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Sub Total</th>
                        <th style="width: 140px;"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="bg-light">
                        <td>
                            <input type="text" ref="input_1" v-on:keyup.enter="searchProduct()" v-model="product.pcode" style="width: 140px;" placeholder="Product code" class="form-control text-uppercase">
                            <div class="small text-danger">{{ errmsg }}</div>
                        </td>
                        <td>
                            {{ product.ptitle }} <br />
                        </td>
                        <td>{{ product.pprice }}</td>
                        <td><input type="text" ref="input_2" v-on:keyup.enter="addProduct()" v-model="product.pqty" placeholder="Qty" style="width: 80px;" class="form-control"></td>
                        <td></td>
                        <td>
                            <button v-on:click="addProduct()" class="btn btn-sm btn-primary"> <i class="fa fa-check"></i> </button>
                        </td>
                    </tr>
                    <tr v-for="(item, index) in products">
                        <td>{{ item.product_code }}</td>
                        <td>{{ item.ptitle }} <br />
                            <span class="text-muted">SKU: {{ item.sku }}</span>
                        </td>
                        <td>{{ item.price }}</td>
                        <td>{{ item.qty }}</td>
                        <td>{{ item.subtotal }}</td>
                        <td class="tex-center">
                            <button v-on:click="removeProduct(index)" class="btn btn-sm btn-danger"> <i class="fa fa-trash"></i> </button>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-end">Total</td>
                        <td class="tex-center">Rs {{ total_amt }}</td>
                        <td class="tex-center">
                            <button v-if="products.length > 0" style="width: 120px;" v-on:click="submitOrder()" class="btn btn-sm btn-primary">{{ button_text }}</button>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<script>
    const vm = new Vue({
        el: '#order',
        data: {
            username: '',
            loader: false,
            found: false,
            errorMsg: '',
            pcode: 'hi',
            user: {
                first_name: '',
                last_name: '',
                mobile: '',
                username: null
            },
            product: {
                id: '0',
                pcode: null,
                ptitle: '-',
                psku: null,
                pqty: 0,
                pprice: 0,
                ptotal: 0
            },
            products: [],
            errmsg: '',
            total_amt: 0,
            button_text: 'Submit Order'
        },
        methods: {
            search: function() {
                if (this.username == '') {
                    this.loader = true;
                    this.errorMsg = "Please enter username to search";
                    return;
                }
                this.loader = true;
                let url = '<?= site_url('api/call/userinfo/?username=') ?>' + this.username;
                fetch(url).then(ab => ab.json())
                    .then(resp => {
                        if (resp.status) {
                            this.found = true;
                            this.loader = false;
                            this.user = resp.data;
                        } else {
                            this.errorMsg = "Invalid Username. Try again";
                            this.found = false;
                        }
                    });

            },
            searchProduct: function() {
                if (this.product.pcode !== '') {
                    let url = '<?= site_url('api/call/product-search/?code=') ?>' + this.product.pcode;
                    fetch(url).then(ab => ab.json())
                        .then(resp => {
                            this.errmsg = '';
                            if (resp.status) {
                                let p = resp.data;
                                this.product.id = p.id;
                                this.product.ptitle = p.ptitle;
                                this.product.pprice = p.price;
                                this.product.pqty = 1;
                                this.product.psku = p.sku;

                                this.$refs.input_2.focus();
                            } else {
                                this.errmsg = "Product not found";
                            }
                        })
                }
            },
            addProduct: function() {
                let item = {
                    id: this.product.id,
                    product_code: this.product.pcode.toUpperCase(),
                    ptitle: this.product.ptitle,
                    sku: this.product.psku,
                    price: this.product.pprice,
                    qty: this.product.pqty,
                    subtotal: this.product.pprice * this.product.pqty
                }
                this.products.push(item);
                this.product = {
                    id: 0,
                    pcode: null,
                    ptitle: '-',
                    psku: '',
                    pprice: '',
                    pqty: ''
                }
                this.$refs.input_1.focus();
                this.calculate();
            },
            removeProduct: function(index) {
                this.products.splice(index, 1);
                this.calculate();
            },
            calculate: function() {
                let total = 0;
                this.products.forEach(item => {
                    total += (item.subtotal);
                });
                this.total_amt = total;
            },
            submitOrder: function() {
                if (!this.found) {
                    alert("Please select customer");
                    return;
                }
                if (!confirm("Are you sure to submit order?"))
                    return false;
                this.button_text = "Saving...";
                let url = "<?= site_url('api/call/add-order/?') ?>" +
                    'user_id=' + this.user.id + '&fuser_id=' + <?= user_id(); ?> + '&total_amt=' + this.total_amt;

                fetch(url).then(ab => ab.json())
                    .then(resp => {
                        if (resp.status) {
                            let order_id = resp.data.id;
                            // Loop iteration
                            this.products.forEach(item => {
                                url = '<?= site_url('api/call/add-items/?') ?>' +
                                    'order_id=' + order_id + '&product_id=' + item.id + '&price=' + item.price + '&qty=' + item.qty;
                                fetch(url);

                            });
                            window.location.href = '<?= site_url('franchise/orders') ?>'
                        } else {
                            alert(resp.message);
                        }
                    });
            }
        }
    })
</script>