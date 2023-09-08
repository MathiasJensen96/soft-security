# soft-security

## Configuration

Secrets and credentials are stored in `.env`. This file is not tracked by git, but there's an example file to see what needs to be filled out.

1. Copy it.
2. Set those values.
3. Rename it to `.env`.

For the credentials for the Redis database, it's recommended to use Redis `ACL GENPASS` to generate a password.

```shell
docker run -d --name quick-genpass redis:alpine
docker exec -it quick-genpass redis-cli ACL GENPASS
docker exec -it quick-genpass redis-cli ACL GENPASS
docker stop quick-genpass
docker rm quick-genpass
```

If you want, you can also get the SHA256 hash of the password if you prefer to not store it in plain text.

```shell
echo -n password | sha256sum
```

After obtaining the passwords:

1. Set them in `.env`.
2. Copy `conf/users.acl.example` to `conf/users.acl`.
3. Add `><password>` or `#<hash>` to the end of each line, corresponding to the password of each user.

## Usage

Development environment:

The compose override contains development configuration, such as bind mounts and port forwarding.

The bind mount will copy in source code, so we can edit it directly without re-building the image, but it will also obscure that folder within the container. This will remove the dependencies installed in the image. You must therefore install the dependencies in your local project folder, so the bind mount copies them into the container.

1. Install [Composer](https://getcomposer.org/).
2. Possibly enable the Sodium PHP extension in `php.ini` (`extension=sodium`). It may be required by some JWT libraries.
3. Stop MySQL service on host machine, if it's running on port 3306.

```bash
composer install
```

```bash
docker-compose up
```

Production environment:

Specify the compose file which will disable the override.

```bash
docker-compose -f docker-compose.yml up
```
