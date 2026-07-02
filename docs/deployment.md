# Deployment

## nginx

Place the dashboard under its own server block or subdirectory. The front controller (`public/index.php`) handles routing for all PHP routes.

```nginx
server {
    listen 80;
    server_name dashboard.example.com;

    root /opt/atm-dashboard/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.x-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### Bluemap reverse proxy

To serve Bluemap under the same domain (e.g. `/bluemap/`), add a `proxy_pass` location **before** the PHP handler:

```nginx
location /bluemap/ {
    proxy_pass http://127.0.0.1:8100/;
    proxy_set_header Host $host;
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;

    # WebSocket support for live map updates
    proxy_http_version 1.1;
    proxy_set_header Upgrade $http_upgrade;
    proxy_set_header Connection "upgrade";
}
```

The iframe in the `/map` page loads `webmap_url` from config — set it to `/bluemap/` so it serves from the same origin (avoiding X-Frame-Options issues).

## PHP built-in server (development)

```bash
php -S localhost:8000 -t public/ public/index.php
```

The front controller checks for static files before dispatching to the router, so CSS/images work without extra config.

## Collector (cron)

Run every 5 minutes:

```
*/5 * * * * cd /opt/atm-dashboard && php bin/collect.php >> /var/log/atm-collect.log 2>&1
```
