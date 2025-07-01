# Cerberus

Nextcloud plugin that alows to GET the file permissions and file ownerships for files.

## Usage

1. Start the docker container using

```
docker compose up -d
```

You can check the volumes mounted using:

```
docker volume ls
```

You can inspect the files using:

```
docker volume inspect cerberus_nextcloud
```

2. You can access nextcloud at: http://localhost:8089/
