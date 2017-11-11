Installation
===============================
```
docker-compose up -d
```

````
docker-compose exec web php ./init  --env=Development --overwrite=y
docker-compose exec web composer install
````

environment
-------------------
Rename __.env-example__ to __.env__


Certificate apns path
-------------------
```
common/config/apns
```

Gcm token
-------------------
configure gcm token at __.env__ file


Start queue worker
-------------------
``
docker-compose exec web ./yii queue/listen
``


