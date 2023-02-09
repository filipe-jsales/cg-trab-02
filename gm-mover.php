<?php

/*
Plugin Name: GM Mover
Plugin URI: https://codexonics.com/
Description: The simplest all-around WordPress migration tool/backup plugin. These support multisite backup/migration or clone WP site/multisite subsite.
Version: 1.8.0
Author: GreenMainframe
Author URI: https://codexonics.com/
Text Domain: gm-mover
Network: True
*/
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
if ( !defined( 'GM_MOVER_MAINPLUGIN_FILE' ) ) {
    define( 'GM_MOVER_MAINPLUGIN_FILE', __FILE__ );
}
if ( !defined( 'GM_MOVER_MAINDIR' ) ) {
    define( 'GM_MOVER_MAINDIR', dirname( GM_MOVER_MAINPLUGIN_FILE ) );
}

if ( function_exists( 'pm_fs' ) ) {
    pm_fs()->set_basename( true, GM_MOVER_MAINPLUGIN_FILE );
} else {
    require_once GM_MOVER_MAINDIR . '/global/GMMoverGlobalFunctions.php';
    if ( defined( 'PRIME_MOVER_PLUGIN_PATH' ) || defined( 'GM_MOVER_PLUGIN_UTILITIES_PATH' ) || defined( 'GM_MOVER_PLUGIN_CORE_PATH' ) || defined( 'PRIME_MOVER_THEME_CORE_PATH' ) ) {
        return;
    }
    include GM_MOVER_MAINDIR . '/global/GMMoverGlobalConstants.php';
    include GM_MOVER_MAINDIR . '/dependency-checks/GMMoverPHPVersionDependencies.php';
    include GM_MOVER_MAINDIR . '/dependency-checks/GMMoverWPCoreDepedencies.php';
    include GM_MOVER_MAINDIR . '/dependency-checks/GMMoverRequirementsCheck.php';
    include GM_MOVER_MAINDIR . '/dependency-checks/GMMoverPHPCoreFunctionDependencies.php';
    include GM_MOVER_MAINDIR . '/dependency-checks/GMMoverFileSystemDependencies.php';
    include GM_MOVER_MAINDIR . '/dependency-checks/GMMoverPluginSlugDependencies.php';
    include GM_MOVER_MAINDIR . '/dependency-checks/GMMoverCoreSaltDependencies.php';
    include GM_MOVER_MAINDIR . '/global/GMMoverGlobalDependencies.php';
    $primemover_global_dependencies = new GMMoverGlobalDependencies();
    $requisitecheck = $primemover_global_dependencies->primeMoverGetRequisiteCheck();
    if ( is_object( $requisitecheck ) && !$requisitecheck->passes() ) {
        return;
    }
    include GM_MOVER_MAINDIR . '/PrimeMoverLoader.php';
    if ( file_exists( PRIME_MOVER_PLUGIN_PATH . '/vendor/autoload.php' ) ) {
        require_once PRIME_MOVER_PLUGIN_PATH . '/vendor/autoload.php';
    }
    include GM_MOVER_MAINDIR . '/GMMoverFactory.php';
    include GM_MOVER_MAINDIR . '/engines/gm-mover-panel/gm-mover-panel.php';
}
