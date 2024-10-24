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

set('repository', 'git@github.com:controlaltdelete-nl/3dprintentexel.nl.git');
set('keep_releases', 3);
set('release_name', date('YmdHis')); // Use timestamp for release name

set('default_timeout', 900); // Increase timeouts because builds can take long

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

set('writable_mode', 'chmod');

set('static_content_locales', 'en_US nl_NL');
set('static_deploy_options', '--force --no-parent --no-js-bundle');
set('static_content_jobs', '12');

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
// Hosts

localhost()
    ->set('local', true)
;

host('49.12.101.75')
    ->set('remote_user', 'forge')
    ->set('deploy_path', '/home/forge/www.3dprintentexel.nl')
    ->set('php_version', '8.3')
    ->setLabels([
        'stage' => 'production',
        'branch' => 'main',
    ])
;

//
// Hooks

after('deploy:failed', 'deploy:unlock');

//
// Tasks

# Clear Deployer default command.
task('cachetool:clear:opcache', function () {});

task('copy:database:dump', [
    'database:dump',
    'download:database:dump',
]);

task('database:dump', function () {
    run('rm -f ~/stripped-dump.sql || true');
    run('rm -f ~/stripped-dump.sql.gz || true');

    run('cd {{current_path}} && magerun2 db:dump ~/stripped-dump.sql --strip="@stripped_project"');
    run('gzip ~/stripped-dump.sql');
});

task('download:database:dump', function () {
    download('~/stripped-dump.sql.gz', 'stripped-dump.sql.gz');
    run('rm ~/stripped-dump.sql.gz');
});

task('php-fpm:restart', function () {
    run('sudo service php8.3-fpm reload');
});

after('deploy:symlink', 'php-fpm:restart');
