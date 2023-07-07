@servers(['live' => '', 'server' => ''])

@task('deploy-live', ['on' => 'live'])
    cd ~/applications/cpjnavnaah/public_html
    git fetch
    git checkout {{ $branch }}
    git pull origin {{ $branch }}
    composer install
    php artisan migrate --force
    php artisan db:seed
    php artisan optimize:clear
    php artisan xme:generate_lang_js
    npm install
    npm run build
    php artisan xme:release_app_version
@endtask

@task('deploy', ['on' => 'server'])
    cd /home/dkjfshkfjewrew/public_html
    git fetch
    git checkout {{ $branch }}
    git pull origin {{ $branch }}
    composer install
    php artisan migrate --force
    php artisan db:seed
    php artisan optimize:clear
    php artisan xme:generate_lang_js
    npm install
    npm run build
    php artisan xme:release_app_version
@endtask
{{-- 
@story('deploy')
    git
@endstory --}}
