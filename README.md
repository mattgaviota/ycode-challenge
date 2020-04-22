# Test assignment for the ycode.com developer

Hi! Are you ready to join our new team working on an innovative and exciting project?
We are looking for a frontend, backend or full-stack developer.
If you are not applying for a full-stack position, let us know and try to solve as much as you can.

For the test assignment, we have a partly finished banking application, where one account can send the money to the other.
We need you to fix and finish it by paying attention to these factors:

- **Security** - we do not want to be hacked
- **Logic** - bank should not allow overspending your balance
- **Best practices** - code should be clean and easy to maintain
- **Tests** - test the parts that you feel necessary to

Use small commits and descriptive commit messages while working on the assignment. One commit solving one issue.

Authentication **IS NOT** in the scope of this assignment. Getting the transactions list with the request `GET /accounts/<id>/transactions` is not a security hole.

Use this repository as your starting point but **DO NOT** fork it. Create a public repository on GitHub for your application source code, push it and send a link to jobs@ycode.com.

# Setup instructions

## Api

In order to test this api you need Docker, Docker compose. Then you must
execute these instructions in a terminal.

1. git clone https://github.com/mattgaviota/ycode-challenge.git
2. cd ycode-challenge
3. docker-compose up -d
4. docker-compose exec --user=\$UID api bash
5. composer install
6. cp .env.example .env
7. php artisan migrate --seed

## Web

For the frontend you need install Yarn or NPM. Then execute these instructions.

1. cd web
2. yarn install
3. yarn dev

## Considerations

The API will be running on http://localhost:8080, if the port 8080 is already allocated you can change it
in the docker-compose.yml.
The WEB will run on http://localhost:3000, the apiUrl env var can be changed in the nuxt.config.js

You can read the docs for the API on http://localhost:8080/api/docs, and if you use Insomnia, on the root
folder of the repo you will find the ybank.json file to import into it. Also, on the docs page, yo can download
a Postman collection.

## Tests

The API can be tested inside of the Docker container with the following commands:

    $ docker-compose exec --user=\$UID api bash
    $ php artisan test

The WEB can be tested from the terminal with the following command inside the _web_ folder:

    $ npx cypress run
