<?php
/*
 *     ______            __             __
 *    / ____/___  ____  / /__________  / /
 *   / /   / __ \/ __ \/ __/ ___/ __ \/ /
 *  / /___/ /_/ / / / / /_/ /  / /_/ / /
 *  \______________/_/\__/_/   \____/_/
 *     /   |  / / /_
 *    / /| | / / __/
 *   / ___ |/ / /_
 *  /_/ _|||_/\__/ __     __
 *     / __ \___  / /__  / /____
 *    / / / / _ \/ / _ \/ __/ _ \
 *   / /_/ /  __/ /  __/ /_/  __/
 *  /_____/\___/_/\___/\__/\___/
 *
 * Copyright www.controlaltdelete.dev
 */

namespace Deployer;

require 'recipe/magento2.php';

// Configuration

set('repository', 'git@github.com:controlaltdelete-nl/shop.magedispatch.com.git');
set('keep_releases', 3);
set('release_name', date('YmdHis')); // Use timestamp for release name

set('default_timeout', 900); // Increase timeouts because builds can take long

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

set('writable_mode', 'chmod');

set('static_content_locales', 'en_US nl_NL');
set('static_deploy_options', '--force --no-parent --no-js-bundle');

function getCpuCount(int $default): int {
    if (is_file('/proc/cpuinfo')) {
        $cpuinfo = file_get_contents('/proc/cpuinfo');
        $count = substr_count(strtolower($cpuinfo), 'processor');
        return $count > 0 ? $count : $default;
    }
    return $default;
}

set('static_content_jobs', getCpuCount(4));

set('shared_files', [
    // Copied from the recipe
    '{{magento_dir}}/app/etc/env.php',
    '{{magento_dir}}/var/.maintenance.ip',
]);

set('shared_dirs', [
    // Copied from the recipe
    '{{magento_dir}}/pub/media',
    '{{magento_dir}}/pub/sitemap',
    '{{magento_dir}}/pub/static/_cache',
    '{{magento_dir}}/var/backups',
    '{{magento_dir}}/var/export',
    '{{magento_dir}}/var/import',
    '{{magento_dir}}/var/importexport',
    '{{magento_dir}}/var/import_history',
    '{{magento_dir}}/var/log',
    '{{magento_dir}}/var/report',
    '{{magento_dir}}/var/session',
    '{{magento_dir}}/var/tmp',
]);

//
// Hosts are loaded from hosts.yml
import(__DIR__ . '/hosts.yml');

//
// Hooks

after('deploy:failed', 'deploy:unlock');

//
// Tasks

# Clear Deployer default command.
task('cachetool:clear:opcache', function () {});

task('database:development:copy', [
    'database:development:dump',
    'database:development:download',
]);

task('database:development:dump', function () {
    run('rm -f ~/stripped-dump.sql || true');
    run('rm -f ~/stripped-dump.sql.gz || true');

    run('cd {{current_path}} && magerun2 db:dump ~/stripped-dump.sql --strip="@stripped_project"');
    run('gzip ~/stripped-dump.sql');
});

task('database:development:download', function () {
    download('~/stripped-dump.sql.gz', 'stripped-dump.sql.gz');
    run('rm ~/stripped-dump.sql.gz');
});

task('database:development:import', function () {
    runLocally('ddev exec magerun2 db:import -c gz stripped-dump.sql.gz');
    runLocally('ddev exec bin/magento setup:upgrade --keep-generated --no-interaction');
    runLocally('ddev exec magerun2 admin:user:create --admin-user=michiel --admin-password=asdfasdf1 --admin-email=michiel@example.com --admin-firstname=Michiel --admin-lastname=Gerritsen --no-interaction');
});

task('database:development:import-to-local', [
    'database:development:dump',
    'database:development:download',
    'database:development:import',
]);

task('database:production:copy', [
    'database:production:backup',
    'download:database:production:backup',
]);

task('database:production:backup', function () {
    run('rm -f ~/shop.magedispatch.com-backup.sql || true');
    run('rm -f ~/shop.magedispatch.com-backup.sql.gz || true');

    run('cd {{current_path}} && magerun2 db:dump ~/shop.magedispatch.com-backup.sql');
    run('gzip ~/shop.magedispatch.com-backup.sql');
});

task('download:database:production:backup', function () {
    download('~/shop.magedispatch.com-backup.sql.gz', 'shop.magedispatch.com-backup.sql.gz');
    run('rm ~/shop.magedispatch.com-backup.sql.gz');
});

task('php-fpm:restart', function () {
    run('sudo service php8.3-fpm reload');
});

after('deploy:symlink', 'php-fpm:restart');

// Deploy Hyva
task('hyva:deploy', function () {
    run('npm --prefix app/design/frontend/ControlAltDelete/MageDispatch/web/tailwind/ ci');
    run('npm --prefix app/design/frontend/ControlAltDelete/MageDispatch/web/tailwind/ run build-prod');
});

before('magento:deploy:assets', 'hyva:deploy');

task('local:media:sync', function () {
    // Define paths
    $remoteMediaPath = '{{deploy_path}}/shared/pub/media/';
    $localMediaPath = __DIR__ . '/pub/media/';

    // Define rsync command
    $rsyncCommand = sprintf(
        'rsync -avz --progress {{remote_user}}@{{hostname}}:%s %s',
        $remoteMediaPath,
        $localMediaPath
    );

    // Run the rsync command locally
    runLocally($rsyncCommand);
})->desc('Sync media files from the remote server to the local machine');
