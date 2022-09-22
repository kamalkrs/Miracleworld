<div class="page-header">
    <h5>Topup History</h5>
</div>
<div id="origin" class="bg-white p-3">
    <table class="table">
        <thead>
            <tr>
                <th>Sl no</th>
                <th>Member Id</th>
                <th>Amount</th>
                <th>Comments</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(item, sl) in items">
                <td>{{ sl + 1 }}</td>
                <td>{{ item.name + '/' + item.username }}</td>
                <td>27</td>
                <td></td>
                <td>{{ item.created }}</td>
            </tr>
        </tbody>
    </table>
</div>

<script>
    let vm = new Vue({
        el: '#origin',
        data: {
            items: [],
            loading: true
        },
        methods: {
            getData: function() {
                this.loading = true;
                let url = ApiUrl + 'topup-history';
                axios.post(url, {
                        user_id: '<?= user_id(); ?>'
                    })
                    .then(result => {
                        let resp = result.data;
                        this.items = resp.data;
                        this.loading = false;
                    })
            }
        },
        created: function() {
            this.getData();
        }
    })
</script>