# readme

```console
sudo service docker start && sudo chmod 666 /var/run/docker.sock && sudo service apache2 stop && sudo service mariadb stop && sudo service lighttpd stop && sudo service nginx stop
```

```console
docker run --rm \                                                                         
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```

```console
./vendor/bin/sail up -d 
```
```console
CREATE USER 'sail'@'%' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON *.* TO 'sail'@'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;
```

## Solution

### Linux users
export NODE_OPTIONS=--openssl-legacy-provider

### Windows users
set NODE_OPTIONS=--openssl-legacy-provider

You can add NODE_OPTIONS=--openssl-legacy-provider to npm scripts.