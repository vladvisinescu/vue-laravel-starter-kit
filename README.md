#### Installation
1. clone the repo
2. `composer install`
3. `npm install` (from host if working on vagrant, otherwise you'll get filesystem and permissions errors)
4. copy `.env.example` to `.env` (contains some extra keys compared to original)
5. `php artisan migrate`
6. `php artisan passport:install`
    - fill in `PERSONAL_CLIENT_ID`, `PERSONAL_CLIENT_SECRET`, `PASSWORD_CLIENT_ID`, `PASSWORD_CLIENT_SECRET` in `.env` with info from this command 
7. update `APP_URL` in `.env` to reflect you local dev environment (eg: http://local.smartsearch.com)
8. `npm run dev` / `npm run watch` for developing the frontend
9. `php artisan snick:admin` to create a new admin
10. `php artisan trans:js` to recompile translations to JS
11. `sudo apt install beanstalkd`
12. `sudo apt install supervisor`
     ```
        [program:laravel-worker]
        process_name=%(program_name)s_%(process_num)02d
        command=php /var/www/boilerplate/artisan queue:work --tries=3
        autostart=true
        autorestart=true
        user=visinescu
        numprocs=2
        redirect_stderr=true
        stdout_logfile=/var/www/boilerplate/worker.log
     ```
13. to be continued

##### TODO

- API client wrapper?
- reset password messages / toasts
- email verification
- component lazy loading chunks
- Gate.js clone
- https://github.com/laravolt/avatar
- https://github.com/oussamahamdaoui/forgJs

