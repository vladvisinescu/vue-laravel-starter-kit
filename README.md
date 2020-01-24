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
11. `sudo pecl install redis` for making queues work

#### Left to do _so far_

- API client wrapper?
- reset password messages / toasts
- email verification
- component lazy loading chunks
- ~~password complexity indicator~~ done using Dropbox's `https://github.com/dropbox/zxcvbn` library


sudo apt install -y --allow-change-held-packages php7.4-cli php7.4-bcmath php7.4-curl php7.4-dev php7.4-gd php7.4-imap php7.4-intl
 php7.4-json php7.4-ldap php7.4-mbstring php7.4-mysql php7.4-odbc php7.4-phpdbg php7.4-pspell php7.4-soap php7.4-sqlite3 
 php7.4-xml php7.4-zip php7.4-readline
