#### Installation
1. clone the repo
2. `composer install`
3. `npm install` (from host if working on vagrant, otherwise you'll get filesystem and permissions errors)
4. copy `.env.example` to `.env` (contains some extra keys compared to original)
5. `php artisan migrate`
6. `php artisan passport:install`
    - fill in `PERSONAL_CLIENT_ID`, `PERSONAL_CLIENT_SECRET`, `PASSWORD_CLIENT_ID`, `PASSWORD_CLIENT_SECRET` in `.env` with info from this command 
7. update `APP_URL` in `.env` to reflect you local dev environment (eg: http://local.smartsearch.com)
8. `npm run watch`
9. `php artisan snick:admin` to create a new admin
10. `php artisan trans:js` to recompile translations to JS
11. `sudo apt install beanstalkd`

#### Left to do _so far_

- API client wrapper?
- reset password messages / toasts
- email verification
- component lazy loading chunks
- ~~password complexity indicator~~ done using Dropbox's `https://github.com/dropbox/zxcvbn` library
- git ignore assets js/css
- clean mailgun config
- github develop branches
- Gate.js clone
- https://github.com/laravolt/avatar
- https://github.com/oussamahamdaoui/forgJs

