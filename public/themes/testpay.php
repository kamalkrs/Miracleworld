<script>
    var options = {
        method: 'POST',
        headers: {
            'TRON-PRO-API-KEY': '05fb5dbe-8f82-4d4b-82dd-2c5a57425636',
            'Content-Type': 'application/json'
        },
        body: {
            to_address: 'TSbCQYvr6bfqumQhAoa1suLCSg3H3SgAkq',
            owner_address: 'TLAuR4NdME1tNEKviTeVBSBCEJ6Ldxh2HT',
            amount: 2.00
        },
        json: true
    };

    fetch('https://api.trongrid.io/wallet/createtransaction', options)
        .then(response => response.json())
        .then(response => console.log(response))
        .catch(err => console.error(err));
</script>