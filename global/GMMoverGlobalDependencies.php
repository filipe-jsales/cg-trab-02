<?php
/****************************************************
 * PRIME MOVE GLOBAL DEPENDENCIES
 * Gets the requisite check instance
 * **************************************************
 */

if (!defined('ABSPATH')) {
    exit;
}

class GMMoverGlobalDependencies
{
    /**
     * Get GM Mover requisites check instance
     * @return GMMoverRequirementsCheck
     */
    public function primeMoverGetRequisiteCheck()
    {
        global $prime_mover_plugin_manager;
        if (is_object($prime_mover_plugin_manager) && $prime_mover_plugin_manager->primeMoverMaybeLoadPluginManager()) {
            return true;
        }
        
        if (wp_doing_ajax()) {
            return true;
        }
        
        if (is_multisite() && !is_network_admin()) {
            return true;
        }
        
        $phprequirement = '5.6';
        
        $phpverdependency = new GMMoverPHPVersionDependencies($phprequirement);
        $wpcoredependency = new GMMoverWPCoreDependencies('4.9.8');
        $phpfuncdependency = new GMMoverPHPCoreFunctionDependencies();
        $foldernamedependency = new GMMoverPluginSlugDependencies(array(PRIME_MOVER_DEFAULT_FREE_BASENAME, PRIME_MOVER_DEFAULT_PRO_BASENAME));
        $coresaltdependency = new GMMoverCoreSaltDependencies();
        
        $required_paths = array(
            PRIME_MOVER_PLUGIN_CORE_PATH,
            PRIME_MOVER_PLUGIN_PATH,
            PRIME_MOVER_THEME_CORE_PATH,
            get_stylesheet_directory(),
            WPMU_PLUGIN_DIR
        );
        
        $wp_upload_dir = primeMoverGetUploadsDirectoryInfo();
        if ( ! empty( $wp_upload_dir['basedir'] ) )  {
            $required_paths[] = $wp_upload_dir['basedir'];
        }
        if ( ! empty( $wp_upload_dir['path'] ) )  {
            $required_paths[] = $wp_upload_dir['path'];
        }
        
        $filesystem_dependency = new GMMoverFileSystemDependencies($required_paths);
        return new GMMoverRequirementsCheck($phpverdependency, $wpcoredependency, $phpfuncdependency, $filesystem_dependency, $foldernamedependency, $coresaltdependency);
    }    
}