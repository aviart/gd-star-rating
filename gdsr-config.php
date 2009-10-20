<?php

/**
 * Full path to wp-load file. Use only if the location of wp-content folder is changed.
 *
 * example: define('STARRATING_WPLOAD', '/home/path/to/wp-load.php');
 */
define('STARRATING_WPLOAD', '');

/**
 * Minimal user level required to access all plugins panels except Front and Builder.
 */
define('STARRATING_ACCESS_LEVEL', 9);

/**
 * Should plugin load and use old format legacy functions.
 */
define('STARRATING_LEGACY_FUNCTIONS', true);

/**
 * Global control of debug. Set to false will prevent any kind of debug into file.
 */
define('STARRATING_DEBUG', true);

/**
 * Minimal user level required to access some of the plugins panels.
 */
define('STARRATING_ACCESS_LEVEL_FRONT', 1);
define('STARRATING_ACCESS_LEVEL_BUILDER', 1);
define('STARRATING_ACCESS_LEVEL_SETUP', 9);

/**
 * My Ratings is moved under dahsboard and visible to all registered users regardless of other access settings.
 */
define('STARRATING_ACCESS_MY_RATINGS', false);
define('STARRATING_ACCESS_MY_RATINGS_LEVEL', 0);

/**
 * Main admin account user ID's with access to special Security panel. If not set, all administrators will have access to it. You can set it to single account or to list of accounts, separated by commas ('1,6,23,12').
 */
define('STARRATING_ACCESS_ADMIN_USERIDS', '0');

?>