# Keiri

Keiri is an accounting web application.

## Setup

1. Clone and access the project folder
```
git clone git@gitlab.com:lcdss/keiri.git
cd keiri
```

2. Copy the `.env.example` file to `.env` (server and client)

3. Start the containers
```
docker-compose up -d
```

The services will be available at:

- Client: http://localhost:3000
- Server: http://localhost:8000

Yes, that's all you need to do to run the project.

To access the app container:

```
docker-compose exec app sh
```

To connect to the database server as root (the default password is `root`):

```
docker-compose exec db mysql -u root -p
````

And optionally, you can seed the database running the command `php artisan db:seed` at `/usr/src/myapp/server` inside the app container.

> Obs.: The default credentials to access the application is `admin@example.com` (email) and `password` (password).
