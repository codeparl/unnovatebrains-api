# TODO

- [x] Move app2/Auth/* into Laravel-standard locations under app/ (controllers -> app/Http/Controllers, middleware -> app/Http/Middleware, services -> app/Services, models -> app/Models or app/Auth/Models)
- [x] Move app2/Chat/* into Laravel-standard locations under app/ (controllers -> app/Http/Controllers, services -> app/Services)
- [x] Move app2/Database/Database.php into app/Database/Database.php if not already present
- [x] Update moved PHP namespaces to match their new paths
- [x] Update routess/api.php imports/usages to the new namespaces

- [x] Ensure code compiles (php -l on moved files)
- [x] Run composer dump-autoload
- [x] Run php artisan test (or at least php artisan -q routes check if available)


