@servers(['web' => 'root@178.128.208.118'])

@setup
    $repository = 'git@github.com:afandiyusuf/envoytest.git';
    $releases_dir = '/var/www/envoytest';
    $staging_dir = '/var/www/envoytest2';
@endsetup

@story('deploy_staging')
    clone_repository_staging
    run_npm_staging
    start_develop_nuxt
@endstory

@story('deploy_production')
    clone_repository_release
@endstory

@task('clone_repository_staging')
    echo '===========Cloning repository staging=============='
    cd {{ $staging_dir}}
    git reset --hard HEAD
    git pull origin staging
    cd {{ $staging_dir }}
    git reset --hard {{ $commit }}
@endtask

@task('clone_repository_release')
   echo '===========Cloning repository release=============='
    cd {{ $releases_dir}}
    git reset --hard HEAD
    git pull origin master
    cd {{ $releases_dir }}
    git reset --hard {{ $commit }}
@endtask

@task('run_npm_staging')
    echo "========== Starting deployment ({{ $staging_dir }}) ================="
    cd {{ $staging_dir }}
    npm install --quiet --no-progress
@endtask

@task('run_npm_release')
    echo "================== Starting deployment ({{ $releases_dir }}) ================"
    cd {{ $releases_dir }}
    npm install --quiet --no-progress
@endtask

@task('start_develop_nuxt')
    echo "=============== build NUXT JS DEV================="
    cd {{ $staging_dir }}
    npm run build
    npm run generate --fail-on-page-error
    cp ./.htaccess ./dist/.htaccess 
@endtask

@task('start_release_nuxt')
    echo "=============== build NUXT JS RELEASE================="
    cd {{ $releases_dir }}
    npm run build
    npm run generate --fail-on-page-error
    cp ./.htaccess ./dist/.htaccess
@endtask
