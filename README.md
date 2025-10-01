# Cerberus

Nextcloud plugin that alows to GET the file permissions and file ownerships for files.

## Usage - general

1. Start your own docker container or nextcloud instance. When using docker, you need to find the directory that
   nextcloud uses. It will be something like /srv/nextcloud/data/nextcloud/custom_apps - it is important that you clone
   the cerberus app to the "custom_apps" folder.

2. Once you cloned the repo, make sure it has the right permissions for www-data:

```
sudo chown -R www-data:www-data srv/nextcloud/data/nextcloud/custom_apps/cerberus
sudo chmod -R 755 srv/nextcloud/data/nextcloud/custom_apps
```

3. Important: The repo contains the a subfolder called "cerberus" - this is where the actual plugin is. You need to copy
   it to "custom_apps" folder.

```
 cp -r cerberus/ ../
```

After doing this, go to the nextcloud GUI and log in. Click on your profile in the upper right corner and on "Apps"
and "Your apps". You should now see "enable" next to cerberus when you scroll down. Once its enabled, you will see a
cloud icon in the upper bar. When you click on it, there is just a blue screen.

4. Test routes:

a) File permission route

To test the first route, you need to "share" an image with somebody. In the nextcloud GUI, go to "Files", check a file
and then click on the share icon. Share the file with somebody, now it appears in "Shares". You can now test the first
curl:

```
curl -u admin:admin "http://localhost:8181/apps/cerberus/permissions/file?path=files/Nextcloud.png"
```

For files that were not shared but exist within nextcloud, use:

```
curl -u admin:admin "http://localhost:8181/apps/cerberus/permissions/file-unshared?path=files/Nextcloud.png"
```

To fetch the ID of a file, use:

```
curl -u admin:admin "http://localhost:8181/apps/cerberus/permissions/file-id?path=files/Nextcloud.png&username=user1"
```

Use a path like "files/image.png" and the username of that file. The username param is there to make sure you are
getting the correct ID. For example, when 2 users have a file with the same name, the username param will make sure that
only the ID of the correct corresponding username is returned. You may also leave out this param.

When fetching the id of a file within a groupfolder, keep in mind that the path needs to start with "__groupfolders",
because files in groupfolders always look like this on the filesystem: __groupfolders/1/1710322553730.jpg . Also, leave
out the username for a groupfolder file, as the groupfolder path already contains the ID of the group ("1" in this
case).

b) Group folders route

The second route is for group access. You need to first create a group and add users to that group. You can do this in
the nextcloud gui in your Profile in "Accounts". You can then also share files across that group.

Now, you need to enable the "group folders" extension in nextcloud. You can do this in the docker container with:

```
php occ app:enable groupfolders
```

Or alternatively via nextcloud gui. Then, click on your profile > Administration settings > Team folders and create a
new folder "test". You can then add the group you created before. Then the second curl should be possible:

```
curl -u admin:admin "http://localhost:8181/apps/cerberus/permissions/group?mount_point=test"
```

You can also do the same request when you only have the groupfolder id:

```
curl -u admin:admin "http://localhost:8181/apps/cerberus/permissions/group-id?folder_id=1"
```

Both requests will deliver the same result, when called accordingly. In nextcloud, a groupfolder always has an id. In
the frontend, you will see the name of the groupfolder only , like "test", but on the nextcloud system itself, via
terminal, when you check the folder, the name will not be visible, only the id: /data/nextcloud/data/__groupfolders/1

c) Available users route

You can also check what users are available in nextcloud using:

```
curl -u admin:admin "http://localhost:8089/apps/cerberus/users/all"
```

This returns a list of all users and all groups together. To fetch only the users:

```
curl -u admin:admin "http://localhost:8089/apps/cerberus/users/user-list"
```

and to fetch only the groups:

```
curl -u admin:admin "http://localhost:8089/apps/cerberus/users/group-list"
```

With the next command you can find out what groups a user is in:

```
curl -u admin:admin "http://localhost:8089/apps/cerberus/users/get-groups?username=admin"
```

## Error handling

You might get an "Access Denied" error. The curl of data is only enabled for the user "root". To change this, you can
modify or deactivate this restriction in cerberus/lib/Controller/FileController.php etc.

## Usage - in a docker container

0. Log in as sudo, otherwise the app might not be there on startup.

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

4. Before you can curl anything, you need to activate the app. Log in into nextcloud, click on your profile icon at the
   top right corner and select "Apps". Then, on the left side, select "your apps" and you should see "cerberus". Click
   on "enable":

5. You can test curling messages:

```
curl -u admin:admin "http://localhost:8089/apps/cerberus/hello"
```

6. You can then upload a file and check its permissions using:

```
http://localhost:8089/apps/cerberus/check-permission/screenshot.png
```

or curl:

```
curl -u admin:admin "http://localhost:8089/apps/cerberus/check-permission/screenshot.png"
```

7. You can check users with:

```
curl -u admin:admin "http://localhost:8089/apps/cerberus/users"
```

8. Log into the db with:

```
docker exec -it cerberus-db-1 mysql -u nextcloud -p nextcloud
```

9. Use the /permissions route:

```
curl -u admin:admin "http://localhost:8089/apps/cerberus/permissions/file?path=files/Nextcloud.png"
```

Attention: In order for this to work you must first share that file. If this is done, you will get a result back.

The second one is:

```
curl -u admin:admin "http://localhost:8089/apps/cerberus/permissions/group?mount_point=test"
```
