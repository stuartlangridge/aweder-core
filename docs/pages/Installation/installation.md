---
layout: page
title: Installation
permalink: /installation/
nav_order: 2
---
# Aweder (awe-der, like order, not ah-weder!)

## TL;DR

```
docker-compose up --build -d
docker exec -it ukr_php bash
composer app-install
```

---

## Getting Started

It's recommended that you use Docker.

You can download [Docker Desktop](https://www.docker.com/products/docker-desktop) for Windows or Mac, if you use Linux you can install [Docker CE](https://docs.docker.com/install/linux/docker-ce/ubuntu/).

You can then start the development server by running this command:

```
docker-compose up --build -d
```

It will take a while to run the first time, as it will need to download and setup the docker images, but once run it will be quick to start when used again. Whilst waiting, continue with the next step.

Edit your `/etc/hosts` file and add this line:

```
127.0.0.1    uk-restaurants.test
```

Once docker has completed you will have six containers running:

- Nginx (latest)
- PHP (7.4)
- MySQL (5.7)
- Redis
- Queue
- Scheduler


## Install Application

SSH into the php docker container.

```
docker exec -it ukr_php bash
```


From inside the docker container, run:

```
composer app-install
```

Goto [https://uk-restaurants.test](https://uk-restaurants.test) in your browser.

You will see an SSL warning, just bypass the warning by clicking Advanced > Ignore. (Do not save the certificate to your Keychain, this is not necessary and may cause issues if the certificate is regenerated).


## Setup Testing Environment

The unit tests require a separate database. Our MySQL container has already created the empty database using the file `docker/mysql/seeds/uk_restaurant_testing.sql`, so we will need to run the migrations for the testing environment.

SSH into the php docker container.

```
docker exec -it ukr_php bash
```

From inside the docker container, run:

```
cd /var/www/vhost/
php artisan migrate --env=testing
php artisan db:seed --env=testing
```

Now you can run your tests from inside the docker container:

```
vendor/bin/phpunit --testdox
```

---

## Troubleshooting

### Connecting to MySQL

You can connect to MySQL using a tool like [Sequel Pro](https://sequelpro.com/test-builds) or [MySQL Workbench](https://dev.mysql.com/downloads/workbench/) using these details:

If you have MySQL installed locally, you will need to stop the local server for this to work.  there is a GUI available that will assist in doing this that can be found at [DBNgin](https://https://dbngin.com/)
```
Host: 127.0.0.1
Port: 3306
Username: root
Password: root
```

From inside the docker containers for Nginx or PHP, you have to connect to the database server using a different hostname.

```
Host: mysql
Port: 3306
Username: root
Password: root
```

### Connecting to Redis

You can connect to Redis using a tool like [Table Plus](https://tableplus.com/) using these details:

If you have Redis installed locally, you will need to stop the local server for this to work.  there is a GUI available that will assist in doing this that can be found at [DBNgin](https://https://dbngin.com/)
```
Host: 127.0.0.1
Port: 6390
Password: null
```

From inside the docker containers for Nginx or PHP, you have to connect to the redis database server using a different hostname.

```
Host: ukr_redis
Port: 6379
Password: null
```

### SSH into the Docker containers

Nginx container:

```
docker exec -it ukr_nginx bash
```

PHP container:

```
docker exec -it ukr_php bash
```

MySQL container:

```
docker exec -it ukr_mysql bash
```


### Problems with composer?

If you previously used something other than docker to run this project, you will likely need to delete your `composer.lock` and `vendor/` files to avoid errors when installing dependencies.

You must run `composer install` from inside docker's php container to ensure the `composer.lock` file generates correctly using the correct version of PHP.

```
docker exec -it ukr_php bash
cd /var/www/vhost/
rm -rf composer.lock
rm -rf vendor/
composer install
```


### Problems with git pre-commit hooks?

If you are getting errors when trying to commit, error messages with something along these lines:

```
.git/hooks/pre-commit: line 12: EXEC_GRUMPHP_COMMAND: command not found
.git/hooks/pre-commit: line 12: /var/www/vhost/vendor/bin/grumphp: No such file or directory
```

You will need to run a `git:init` script for grumphp from inside docker's php container.

```
docker exec -it ukr_php bash
cd /var/www/vhost/
vendor/bin/grumphp git:init
```

This will ensure pre-commit hooks run their commands from inside the docker container.


### Need to Regenerate SSL Certificates?

If the self-signed SSL certificate expires and you need to regenerate it, then navigate to the SSL directory.

```
cd docker/nginx/ssl/
```

Generate the SSL certificate and private key.

```
openssl req -config openssl.cnf \
-new -sha256 -newkey rsa:2048 -nodes -keyout self-signed.key \
-x509 -days 825 -out self-signed.crt
```

Any changes you make, you will need to rebuild the docker containers.

```
docker-compose down
docker-compose up --build -d
```


#### Debugging SSL Certificates

View Certificate information.

```
openssl x509 -text -noout -in self-signed.crt
```

Read the SSL Certificate information from a remote server.

```
openssl s_client -showcerts -connect localhost:443
```
