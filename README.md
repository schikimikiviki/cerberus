# Cerberus

Nextcloud plugin that alows to GET the file permissions and file ownerships for files.

## Usage

1. Start the docker container using

```
docker compose -p cerberus up -d
```

You can check the volumes mounted using:

```
docker volume ls
```

This should look like this:

```
DRIVER    VOLUME NAME
local     cerberus_cerberus_db
local     cerberus_cerberus_nextcloud
local     cerberus_db
local     cerberus_nextcloud
local     mindstore_pgdata
```

You can inspect the files using:

```
docker volume inspect cerberus_cerberus_nextcloud
```

2. Make the startup script executable and execute it with sudo:

```
chmod +x startup.sh
sudo bash startup.sh
```

3. You can access nextcloud at: http://localhost:8089/

4. You can test curling messages:

```
curl -u admin:admin "http://localhost:8089/apps/cerberus/hello"
```

5. You can then upload a file and check its permissions using:

```
http://localhost:8089/apps/cerberus/check-permission/screenshot.png
```

or curl:

```
curl -u admin:admin "http://localhost:8089/apps/cerberus/check-permission/screenshot.png"
```
