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
    "id": 4,
    "name": "Matt",
    "balance": 14000,
    "currency": "USD"
}
```
> Example response (404):

```json
{
    "message": "Not Found!"
}
```

### HTTP Request
`GET api/accounts/{account}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `id` |  required  | The id of the account.

<!-- END_9b44b4cf3baf08caef7a0b0feb798b91 -->

<!-- START_2e3bad94a1c50a2d03acc0ec870caefa -->
## Create an Account

> Example request:

```bash
curl -X POST \
    "http://localhost:8080/api/accounts" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"name":"Matt","currency":"USD"}'

```

```javascript
const url = new URL(
    "http://localhost:8080/api/accounts"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "Matt",
    "currency": "USD"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (201):

```json
{
    "id": 4,
    "message": "Welcome Matt!. Your Account ID is 4"
}
```
> Example response (422):

```json
{
    "name": "The name must be at least 3 characters.",
    "currency": "The selected currency is invalid."
}
```

### HTTP Request
`POST api/accounts`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `name` | string |  required  | The name of the owner.
        `currency` | string |  required  | The ID of the account.
    
<!-- END_2e3bad94a1c50a2d03acc0ec870caefa -->

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
> Example response (404):

```json
{
    "message": "Not Found!"
}
```

### HTTP Request
`GET api/accounts/{account}/transactions`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `id` |  required  | The id of the account.

<!-- END_e9485e6bd8fbd6ab9d07f08f48a28ac4 -->

<!-- START_f264c3bf65231a66c5de7ac1205f1c04 -->
## Make a transactions

> Example request:

```bash
curl -X POST \
    "http://localhost:8080/api/accounts/1/transactions" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"to":2,"amount":500,"details":"Sample transactions of $500"}'

```

```javascript
const url = new URL(
    "http://localhost:8080/api/accounts/1/transactions"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "to": 2,
    "amount": 500,
    "details": "Sample transactions of $500"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (201):

```json
{
    "message": "Transaction was successful"
}
```
> Example response (422):

```json
{
    "to": [
        "The to field is required."
    ],
    "amount": [
        "The amount field is required."
    ]
}
```
> Example response (422):

```json
{
    "to": [
        "The destination account does not exists"
    ]
}
```
> Example response (409):

```json
{
    "message": "The amount to transfer exceeds your balance"
}
```
> Example response (409):

```json
{
    "message": "The destination account does not had the same currency of your account"
}
```

### HTTP Request
`POST api/accounts/{account}/transactions`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `id` |  required  | The id of the account.
#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `to` | number |  required  | The id of the destination account.
        `amount` | number |  required  | The amount to be transfered.
        `details` | string |  required  | The detail of the transactions.
    
<!-- END_f264c3bf65231a66c5de7ac1205f1c04 -->


