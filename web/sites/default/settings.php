<?php

/**
 * Load services definition file.
 */
$settings['container_yamls'][] = __DIR__ . '/services.yml';

/**
 * Include the Pantheon-specific settings file.
 *
 * n.b. The settings.pantheon.php file makes some changes
 *      that affect all environments that this site
 *      exists in.  Always include this file, even in
 *      a local development environment, to ensure that
 *      the site settings remain consistent.
 */
include __DIR__ . "/settings.pantheon.php";

/**
 * Skipping permissions hardening will make scaffolding
 * work better, but will also raise a warning when you
 * install Drupal.
 *
 * https://www.drupal.org/project/drupal/issues/3091285
 */
// $settings['skip_permissions_hardening'] = TRUE;

/**
 * Config sync directory — one level above the web root.
 */
$settings['config_sync_directory'] = dirname(DRUPAL_ROOT) . '/config/default';

/**
 * Pantheon environment detection.
 */
$is_pantheon_env = isset($_ENV['PANTHEON_ENVIRONMENT']);
$pantheon_env    = $is_pantheon_env ? $_ENV['PANTHEON_ENVIRONMENT'] : NULL;

$is_pantheon_dev_env   = $pantheon_env === 'dev'
  || (is_string($pantheon_env) && str_contains($pantheon_env, 'ci-'))
  || (is_string($pantheon_env) && str_contains($pantheon_env, 'pr-'))
  || (is_string($pantheon_env) && str_contains($pantheon_env, 'develop'));
$is_pantheon_stage_env = $pantheon_env === 'test';
$is_pantheon_prod_env  = $pantheon_env === 'live';
$is_local_env          = !$is_pantheon_env || $pantheon_env === 'lando';

/**
 * Initialize all config splits to disabled.
 * Exactly one is enabled below based on the detected environment.
 */
$config['config_split.config_split.local']['status'] = FALSE;
$config['config_split.config_split.dev']['status']   = FALSE;
$config['config_split.config_split.stage']['status'] = FALSE;
$config['config_split.config_split.prod']['status']  = FALSE;

if ($is_pantheon_env && !$is_local_env) {
  if ($is_pantheon_dev_env) {
    $config['config_split.config_split.dev']['status'] = TRUE;
  }
  elseif ($is_pantheon_stage_env) {
    $config['config_split.config_split.stage']['status'] = TRUE;
  }
  elseif ($is_pantheon_prod_env) {
    $config['config_split.config_split.prod']['status'] = TRUE;
  }
}
else {
  // Local environment (Lando or no PANTHEON_ENVIRONMENT).
  $config['config_split.config_split.local']['status'] = TRUE;
}

/**
 * If there is a local settings file, then include it.
 * Place environment-specific overrides (database, Redis, Solr credentials)
 * in settings.local.php — never commit that file.
 */
$local_settings = __DIR__ . '/settings.local.php';
if (file_exists($local_settings)) {
  include $local_settings;
}

$config['system.logging']['error_level'] = 'verbose';


