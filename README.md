## Docker running Nginx, PHP-FPM, Composer, MySQL and PHPMyAdmin.

#To run the docker commands without using **sudo** you must add the **docker** group to **your-user**:

```
sudo usermod -aG docker your-user
```

## Launch of the app:

```
git clone https://github.com/lexa-dev/simple-task-tracker.git
```

```
docker-compose up
```


## Install/Update composer dependencies:

```
docker-compose run --rm composer install
docker-compose run --rm composer update
```
