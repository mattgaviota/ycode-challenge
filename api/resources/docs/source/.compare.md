---
title: API Reference

language_tabs:
- bash
- javascript

includes:

search: true

toc_footers:
- <a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a>
---
<!-- START_INFO -->
# Info

Welcome to the generated API reference.

<!-- END_INFO -->

#general


<!-- START_9b44b4cf3baf08caef7a0b0feb798b91 -->
## Display the account

> Example request:

```bash
curl -X GET \
    -G "http://localhost:8080/api/accounts/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8080/api/accounts/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "id": 1,
    "currency": "USD",
    "balance": "15000",
    "name": "John"
}
```

### HTTP Request
`GET api/accounts/{account}`


<!-- END_9b44b4cf3baf08caef7a0b0feb798b91 -->

<!-- START_e9485e6bd8fbd6ab9d07f08f48a28ac4 -->
## Display all the transactions

> Example request:

```bash
curl -X GET \
    -G "http://localhost:8080/api/accounts/1/transactions" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8080/api/accounts/1/transactions"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
[
    {
        "id": 3,
        "from": 2,
        "to": 1,
        "details": "sample transaction 3",
        "amount": "15"
    },
    {
        "id": 2,
        "from": 1,
        "to": 2,
        "details": "sample transaction 2",
        "amount": "24"
    },
    {
        "id": 1,
        "from": 1,
        "to": 2,
        "details": "sample transaction",
        "amount": "14"
    }
]
```

### HTTP Request
`GET api/accounts/{account}/transactions`


<!-- END_e9485e6bd8fbd6ab9d07f08f48a28ac4 -->

<!-- START_f264c3bf65231a66c5de7ac1205f1c04 -->
## Make a transactions

> Example request:

```bash
curl -X POST \
    "http://localhost:8080/api/accounts/1/transactions" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8080/api/accounts/1/transactions"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/accounts/{account}/transactions`


<!-- END_f264c3bf65231a66c5de7ac1205f1c04 -->


