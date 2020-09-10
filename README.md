# Purpose
Create an API to work with Iconic Dataset - Stack: Symfony + MySQL + Nginx

# Requirements
* Docker
* MySQL Workbench (or any other Database client)

# Project Definition
The project skeleton has been created with symfony command to start a project from scratch:
```bash
your-project/
├── assets/
├── bin/
│ └── console
├── config/
│ ├── bundles.php
│ ├── packages/
│ ├── routes.yaml
│ └── services.yaml
├── public/
│ └── index.php
├── src/
│ ├── ...
│ └── Kernel.php
├── templates/
├── tests/
├── translations/
├── var/
│ ├── cache/
│ └── tmp/
└── vendor/
```
**`Important:`** The **/docker** folder has the PHP & Nginx services configuration used by docker-compose.

# Environment Settings

## Database configuration
A MySQL DB has been added to the project, its credentials are:
* user: root
* password: toor
* database name: db
These credentials are used in **`docker-compose-yml`** and on the connection specified on the **`.env`** file.

![db-docker-compose](https://i.imgur.com/puBGHdd.jpg)

![config-db-symfony](https://i.imgur.com/FWDumvF.jpg)

If using workbench it can be setup this way to work witht he DB:

![workbench-config](https://i.imgur.com/kc16Ptb.png)

## Start Stack (Symfony + MySQL + Nginx)
On Linux or Mac, you can execute:
```bash
make start
```
On Windows, execute:
```bash
docker-compose up -d
```

## Stop Stack
On Linux or Mac, you can execute:
```bash
make stop
```
On Windows, execute:
```bash
docker-compose down
```

## Interactive Mode
On Linux or Mac, execute:
```bash
make interactive
```
On Windows, execute:
```bash
docker-compose -f docker-compose.cli.yml run \
    --rm \
    --no-deps \
    -e HOST_USER=${UID} \
    -e TERM=xterm-256color \
    php-cli /bin/zsh -l
```

After starting the stack on interactive mode, execute **`composer install`**

## Check the App is up and running
To visualize the project go to:
```
http://localhost:8000/
```

## Postman Collection to test the API
[![Run in Postman](https://run.pstmn.io/button.svg)](https://app.getpostman.com/run-collection/9b7741ad49e59b74e949)
