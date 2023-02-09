<?php
namespace GreenMainframe;

/*
 * This file is part of the GreenMainframe package.
 *
 * (c) GreenMainframe
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use GreenMainframe\GMMoverFramework\classes\PrimeMoverSystemAuthorization;
use GreenMainframe\GMMoverFramework\classes\PrimeMoverSystemInitialization;
use GreenMainframe\GMMoverFramework\classes\PrimeMoverProgressHandlers;
use GreenMainframe\GMMoverFramework\classes\PrimeMoverSystemFunctions;
use GreenMainframe\GMMoverFramework\utilities\PrimeMoverSystemUtilities;
use GreenMainframe\GMMoverFramework\utilities\PrimeMoverSystemCheckUtilities;
use GreenMainframe\GMMoverFramework\classes\PrimeMoverSystemChecks;
use GreenMainframe\GMMoverFramework\utilities\PrimeMoverUploadUtilities;
use GreenMainframe\GMMoverFramework\classes\PrimeMoverImporter;
use GreenMainframe\GMMoverFramework\classes\PrimeMoverExporter;
use GreenMainframe\GMMoverFramework\utilities\PrimeMoverExportUtilities;
use GreenMainframe\GMMoverFramework\classes\PrimeMoverSystemProcessors;
use GreenMainframe\GMMoverFramework\classes\PrimeMoverHookedMethods;
use GreenMainframe\GMMoverFramework\classes\GMMover;
use GreenMainframe\GMMoverFramework\utilities\PrimeMoverShutdownUtilities;
use GreenMainframe\GMMoverFramework\classes\PrimeMoverErrorHandlers;
use GreenMainframe\GMMoverFramework\utilities\PrimeMoverImportUtilities;
use GreenMainframe\GMMoverFramework\utilities\PrimeMoverDownloadUtilities;
use GreenMainframe\GMMoverFramework\utilities\PrimeMoverSearchReplaceUtilities;
use GreenMainframe\GMMoverFramework\utilities\PrimeMoverLockUtilities;
use GreenMainframe\GMMoverFramework\utilities\PrimeMoverOpenSSLUtilities;
use GreenMainframe\GMMoverFramework\classes\PrimeMoverValidationHandlers;
use GreenMainframe\GMMoverFramework\utilities\PrimeMoverValidationUtilities;
use GreenMainframe\GMMoverFramework\streams\PrimeMoverStreamFilters;
use GreenMainframe\GMMoverFramework\utilities\PrimeMoverConfigUtilities;
use GreenMainframe\GMMoverFramework\utilities\PrimeMoverComponentAuxiliary;
use GreenMainframe\GMMoverFramework\utilities\PrimeMoverFreemiusIntegration;
use GreenMainframe\GMMoverFramework\compatibility\PrimeMoverCompatibility;
use GreenMainframe\GMMoverFramework\streams\PrimeMoverResumableDownloadStream;
use GreenMainframe\GMMoverFramework\streams\PrimeMoverIterators;
use GreenMainframe\GMMoverFramework\cli\PrimeMoverCLIShellArchiver;
use GreenMainframe\GMMoverFramework\cli\PrimeMoverCLIArchive;
use GreenMainframe\GMMoverFramework\menus\PrimeMoverBackupMenus;
use GreenMainframe\GMMoverFramework\menus\PrimeMoverGearBoxScreenOptions;
use GreenMainframe\GMMoverFramework\utilities\PrimeMoverBackupUtilities;
use GreenMainframe\GMMoverFramework\classes\PrimeMoverUsers;
use GreenMainframe\GMMoverFramework\utilities\PrimeMoverUserUtilities;
use GreenMainframe\GMMoverFramework\users\PrimeMoverUserFunctions;
use GreenMainframe\GMMoverFramework\users\PrimeMoverUserQueries;
use GreenMainframe\GMMoverFramework\archiver\PrimeMoverArchiver;
use GreenMainframe\GMMoverFramework\compatibility\PrimeMoverElementorCompat;
use GreenMainframe\GMMoverFramework\compatibility\PrimeMoverCachingCompat;
use GreenMainframe\GMMoverFramework\compatibility\PrimeMoverCleanUp;
use GreenMainframe\GMMoverFramework\streams\PrimeMoverDatabaseUtilities;
use GreenMainframe\GMMoverFramework\compatibility\PrimeMoverFreemiusCompat;
use GreenMainframe\GMMoverFramework\compatibility\PrimeMoverCustomMultisite;
use GreenMainframe\GMMoverFramework\compatibility\PrimeMoverWooCommerceCompat;
use GreenMainframe\GMMoverFramework\compatibility\PrimeMoverMultilingualCompat;
use GreenMainframe\GMMoverFramework\compatibility\PrimeMoverHotFix;
use GreenMainframe\GMMoverFramework\compatibility\PrimeMoverMigrationOptions;
use GreenMainframe\GMMoverFramework\compatibility\PrimeMoverPageBuilderCompat;
use GreenMainframe\GMMoverFramework\compatibility\PrimeMoverCustomConfig;
use wpdb;

if (! defined('ABSPATH')) {
    exit;
}

/**
 *  Instantiate new plugin object and uninstallation methods.
 */
class GMMoverFactory
{

    /**
     * Mode of execution, CLI or normal
     * @var boolean
     */
    private $cli = false;
    
    /**
     * Parameters passed, $argv for CLI
     * @var array
     */
    private $parameters = [];
    
    /**
     * Constructor
     * @param boolean $cli
     * @param array $parameters
     */
    public function __construct($cli = false, $parameters = [])
    {
        $this->cli = $cli;
        $this->parameters = $parameters; 
    } 
        
    /**
     * Get Cli
     * @return boolean
     */
    public function getCli()
    {
        return $this->cli;
    }
    
    /**
     * Get parameters
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }
        
    /**
     * Initialize hook
     */
    public function initHook()
    {
        add_action('init', [$this, 'composeObjects'], 0);
        add_filter( 'determine_current_user', [$this, 'setUser'], 10, 1 );
    } 
    
    /**
     * Set user if needed
     * @param mixed $user
     * @return mixed
     */
    public function setUser($user)
    {
        if ($this->getCli() && defined('PRIME_MOVER_COPY_MEDIA_SHELL_USER') && defined('PRIME_MOVER_DOING_SHELL_ARCHIVE') && PRIME_MOVER_DOING_SHELL_ARCHIVE) {
            return PRIME_MOVER_COPY_MEDIA_SHELL_USER;
        }        
        return $user;        
    }
    
    /**
     * Hooked to `init`
     */
    public function composeObjects()
    {        
        $prime_mover_user = wp_get_current_user();        
        $system_authorization = new PrimeMoverSystemAuthorization($prime_mover_user);        
        $system_initialization = new PrimeMoverSystemInitialization($system_authorization);        
        
        $openssl_utilities = new PrimeMoverOpenSSLUtilities($system_initialization);
        $openssl_utilities->initHooks(); 
        
        $system_functions = new PrimeMoverSystemFunctions($system_initialization);  
        $system_functions->systemHooks();
        
        $lock_utilities = new PrimeMoverLockUtilities($system_functions);        
        $lock_utilities->initHooks();
        
        $system_utilities = new PrimeMoverSystemUtilities($system_functions);
        $system_utilities->initHooks();          
        
        $shutdown_utilities = new PrimeMoverShutdownUtilities($system_functions);
        
        global $pm_fs;
        
        $freemius_integration = new PrimeMoverFreemiusIntegration($shutdown_utilities, $pm_fs);
        $freemius_integration->initHooks(); 
        
        $progress_handlers = new PrimeMoverProgressHandlers($shutdown_utilities);
        $progress_handlers->initHooks();
        
        $system_check_utilities = new PrimeMoverSystemCheckUtilities($system_functions, $system_utilities); 
        $system_check_utilities->initHooks();
        
        $system_checks = new PrimeMoverSystemChecks($system_check_utilities);        
        $upload_utilities = new PrimeMoverUploadUtilities($system_checks, $progress_handlers);            
        $upload_utilities->initHooks();
        $stream_entity = new PrimeMoverStreamFilters();      
        
        $error_handlers = new PrimeMoverErrorHandlers($shutdown_utilities);
        $error_handlers->initHooks();       
        $cli_archiver = new PrimeMoverCLIArchive($system_checks, $progress_handlers);        
        
        $user_queries = new PrimeMoverUserQueries($cli_archiver);
        $user_functions = new PrimeMoverUserFunctions($user_queries);
        $user_utilities = new PrimeMoverUserUtilities($user_functions);
        $users = new PrimeMoverUsers($user_utilities);
        $users->initHooks();
        
        $archiver = new PrimeMoverArchiver($cli_archiver, $users, $openssl_utilities);
        $archiver->initHooks();
        
        $importer = new PrimeMoverImporter($cli_archiver, $users);
        $importer->importerHooks();
        
        $iterators = new PrimeMoverIterators($system_functions);         
        $exporter = new PrimeMoverExporter($stream_entity, $iterators, $cli_archiver, $users);       
        $exporter->exporterHooks();
        
        $export_utilities = new PrimeMoverExportUtilities($exporter);
        $export_utilities->initHooks();        
      
        $system_processors = new PrimeMoverSystemProcessors($importer, $upload_utilities, $export_utilities);               
        $hooked_methods = new PrimeMoverHookedMethods($system_checks, $progress_handlers);        
        
        $prime_mover = new GMMover($hooked_methods, $system_processors);
        $prime_mover->primeMoverLoadHooks();         
              
        $import_utilities = new PrimeMoverImportUtilities($importer, $export_utilities, $lock_utilities);
        $import_utilities->initHooks();
        
        $config_utilities = new PrimeMoverConfigUtilities($import_utilities);
        $config_utilities->initHooks();
        
        $resume_download_stream = new PrimeMoverResumableDownloadStream($system_functions);        
        $download_utilities = new PrimeMoverDownloadUtilities($resume_download_stream);
        $download_utilities->initHooks();         
        
        $search_utilities = new PrimeMoverSearchReplaceUtilities($prime_mover);
        $search_utilities->initHooks();       
        
        $backup_utilities = new PrimeMoverBackupUtilities($prime_mover);
        $component_utilities = new PrimeMoverComponentAuxiliary($import_utilities, $download_utilities, $backup_utilities);
        $component_utilities->initHooks();
        
        $prime_mover_gearbox_screenoptions = new PrimeMoverGearBoxScreenOptions($prime_mover, $component_utilities);
        $prime_mover_gearbox_screenoptions->initHooks();
        
        $utilities = [
            'sys_utilities' => $system_utilities,
            'error_handlers' => $error_handlers,
            'import_utilities' => $import_utilities,
            'download_utilties' => $download_utilities,
            'lock_utilities' => $lock_utilities,
            'openssl_utilities' => $openssl_utilities,
            'config_utilities' => $config_utilities,
            'component_utilities' => $component_utilities,
            'freemius_integration' => $freemius_integration,
            'screen_options' => $prime_mover_gearbox_screenoptions,
            'backup_utilities' => $backup_utilities
        ];        
        
        $validation_utilities = new PrimeMoverValidationUtilities($prime_mover, $utilities);
        $validation_utilities->initHooks();
        
        $input_validator = new PrimeMoverValidationHandlers($prime_mover, $utilities, $validation_utilities);
        $input_validator->initHooks();
  
        $compatibility = new PrimeMoverCompatibility($prime_mover, $utilities);
        $compatibility->initHooks();
        
        $elementor_compat = new PrimeMoverElementorCompat($prime_mover, $utilities);
        $elementor_compat->initHooks();
        
        $wc_compat = new PrimeMoverWooCommerceCompat($prime_mover, $utilities);
        $wc_compat->initHooks();

        $pb_compat = new PrimeMoverPageBuilderCompat($prime_mover, $utilities);
        $pb_compat->initHooks();
        
        $ml_compat = new PrimeMoverMultilingualCompat($prime_mover, $utilities);
        $ml_compat->initHooks();
        
        $caching_compat = new PrimeMoverCachingCompat($prime_mover, $utilities);
        $caching_compat->initHooks();
        
        $cleanup = new PrimeMoverCleanUp($prime_mover, $utilities);
        $cleanup->initHooks();
        
        $custom_mu = new PrimeMoverCustomMultisite($prime_mover, $utilities);
        $custom_mu->initHooks();

        $hotfix = new PrimeMoverHotFix($prime_mover, $utilities);
        $hotfix->initHooks();

        $customconfig = new PrimeMoverCustomConfig($prime_mover, $utilities);
        $customconfig->initHooks();
        
        $migration_options = new PrimeMoverMigrationOptions($prime_mover, $utilities);
        $migration_options->initHooks();
        
        $db_utilities = new PrimeMoverDatabaseUtilities($prime_mover, $utilities);
        $db_utilities->initHooks();
        
        if ($this->getCli()) {
            $parameters = $this->getParameters();
            $cli = new PrimeMoverCLIShellArchiver($prime_mover, $utilities, $parameters);
            $cli->initHooks();
        }       
               
        $backup_menu = new PrimeMoverBackupMenus($prime_mover, $utilities);
        $backup_menu->initHooks();        
                
        $fcompat = new PrimeMoverFreemiusCompat($prime_mover, $utilities, $pm_fs);
        $fcompat->registerHooks();
        
        do_action( 'prime_mover_load_module_apps', $prime_mover, $utilities);         
    }
    
    /**
     * Uninstall sequence
     * Only happens when user uninstalls GM Mover
     */
    public function primeMoverCleanUpOnUninstall()
    {
        if (!current_user_can('delete_plugins')) {
            return;
        }
        
        $this->removePluginManagerOnUninstall();        
        $this->removePrimeMoverDirectoriesOnUninstall();
        $this->removePrimeMoverOptionsInDb();
    }
    
    /**
     * Delete GM Mover option
     * @param string $option
     * @param number $user_id
     * @param boolean $network
     */
    private function deletePrimeMoverOption($option = '', $user_id = 0, $network = false)
    {
        if (in_array($option, ['prime_mover_control_panel_settings', 'prime_mover_backup_auth'])) {
            return;
        }
        if ($user_id) {
            delete_user_meta($user_id, $option);
            return;
        }
        if ($network) {
            delete_site_option($option);
        } else {
            delete_option($option);
        }
        if (primeMoverIsShaString($option)) {
            if ($network) {
                delete_site_option($option . '_filename');
            } else {
                delete_option($option . '_filename');
            }
        }        
    }
    
    /**
     * Remove GM Mover options in dB
     */
    private function removePrimeMoverOptionsInDb()
    {
        global $wpdb;
        $user_id = get_current_user_id();
        $user_id = (int)$user_id;
    
        foreach ($this->getPrimeMoverHashedOptions($wpdb->options, 'option_name', $wpdb) as $option) {
            $this->deletePrimeMoverOption($option);
        }        
        if (is_multisite()) {
            foreach ($this->getPrimeMoverHashedOptions($wpdb->sitemeta, 'meta_key', $wpdb) as $option) {
                $this->deletePrimeMoverOption($option, 0, true);
            }
        }      
        foreach ($this->getPrimeMoverHashedOptions($wpdb->usermeta, 'meta_key', $wpdb, $user_id) as $option) {
            $this->deletePrimeMoverOption($option, $user_id);
        } 
    }
    
    /**
     * Return option query
     * @param string $field
     * @param string $table
     * @return string
     */
    private function returnOptionQuery($field = '', $table = '', $user_id = 0)
    {         
        $user_id_query = '';
        $user_id = (int)$user_id;
        if ($user_id) {
            $user_id_query = "AND user_id = $user_id";
        }
        
        return "SELECT {$field} FROM {$table}
        WHERE CHAR_LENGTH({$field}) = 64 {$user_id_query} OR ({$field} LIKE '%prime_mover_%' OR {$field} LIKE '%wprime_%' OR {$field} LIKE '%_filename')";
    }
    
    /**
     * Get GM Mover options for uninstallation/cleanup
     * @param string $table
     * @param string $field
     * @param wpdb $wpdb
     * @param number $user_id
     * @return array|array
     */
    private function getPrimeMoverHashedOptions($table = '', $field = '', wpdb $wpdb = null, $user_id = 0)
    {     
        $valid_tb = [$wpdb->options, $wpdb->usermeta];
        if (is_multisite()) {
            $valid_tb[] = $wpdb->sitemeta;
        }
        $valid_fields = ['option_name', 'meta_key'];
        if (!in_array($field, $valid_fields) || !in_array($table, $valid_tb)) {
            return [];
        }        
        
        $results = $wpdb->get_results($this->returnOptionQuery($field, $table, $user_id), ARRAY_A);                 
        if (!is_array($results)) {
            return [];
        }        
        return array_filter(wp_list_pluck($results, $field), function($string) {            
            if (false !== strpos($string, 'wprime_') || false !== strpos($string, 'prime_mover_')) {
                return true;
            }             
            $exploded = false;
            if (false !== strpos($string, '_filename')) {
                $exploded = explode("_", $string);
            }
            if (is_array($exploded) && isset($exploded[0])) {
                $string = $exploded[0];
            }
            return primeMoverIsShaString($string);
        });
    }
        
    /**
     * Remove plugin manager on uninstall
     */
    private function removePluginManagerOnUninstall()
    {
        $mu = trailingslashit(WPMU_PLUGIN_DIR) . 'prime-mover-cli-plugin-manager.php';
        if (!file_exists($mu)) {
            return;
        }
        unlink($mu);
    }
    
    /**
     * Remove GM Mover directories on uninstall
     */
    private function removePrimeMoverDirectoriesOnUninstall()
    {        
        $upload_dir = wp_upload_dir();
        if (!isset($upload_dir['basedir'])) {
            return;
        }
        global $wp_filesystem;
        if (!is_object($wp_filesystem)) {
            return;
        }
        $basedir = trailingslashit($upload_dir['basedir']);
        if (!$basedir) {
            return;
        }
        $this->removePrimeMoverTmpDownloads($basedir, $wp_filesystem);
        $this->removePrimeMoverLockDir($basedir, $wp_filesystem);
        $this->removePrimeMoverImportDir($basedir, $wp_filesystem);
        $this->removePrimeMoverExportDir($basedir, $wp_filesystem);        
    }
    
    /**
     * Remove GM Mover import directory on uninstall
     * @param string $basedir
     * @param $wp_filesystem
     */
    private function removePrimeMoverImportDir($basedir = '', $wp_filesystem = null)
    {
        $this->uninstallDir($wp_filesystem, $basedir, PRIME_MOVER_IMPORT_DIR_SLUG);
    }
    
    /**
     * Remove GM Mover export dir IF no packages on it.
     * @param string $basedir
     * @param $wp_filesystem
     */
    private function removePrimeMoverExportDir($basedir = '', $wp_filesystem = null)
    {
        $exportdir = wp_normalize_path($basedir . PRIME_MOVER_EXPORT_DIR_SLUG);
        if (!file_exists($exportdir)) {
            return;
        }
        
        if ($this->exportDirHasPackage($basedir)) {
            return;
        }
        
        $this->uninstallDir($wp_filesystem, $basedir, PRIME_MOVER_EXPORT_DIR_SLUG);
    }
    
    /**
     * Check if default export dir has package before uninstallation
     * @param string $basedir
     * @return void|boolean
     */
    private function exportDirHasPackage($basedir = '')
    {
        $files = list_files($basedir . PRIME_MOVER_EXPORT_DIR_SLUG, 2);
        if (!is_array($files)) {
            return;
        }
        
        $output = array_filter($files, function($filename) {            
            return ('gm' === strtolower(pathinfo($filename, PATHINFO_EXTENSION)));
        });
        
        return (count($output) > 0);        
    }
    
    /**
     * Remove GM Mover tmp download directory
     * @param string $basedir
     * @param $wp_filesystem
     */
    private function removePrimeMoverTmpDownloads($basedir = '', $wp_filesystem = null)
    {
        $this->uninstallDir($wp_filesystem, $basedir, PRIME_MOVER_TMP_DIR_SLUG);
    }
    
    /**
     * Remove GM Mover lock directory
     * @param string $basedir
     * @param $wp_filesystem
     */
    private function removePrimeMoverLockDir($basedir = '', $wp_filesystem = null)
    {
        $this->uninstallDir($wp_filesystem, $basedir, PRIME_MOVER_LOCK_DIR_SLUG);
    }
    
    /**
     * Uninstall directory
     * @param $wp_filesystem
     * @param string $basedir
     * @param string $slug
     */
    private function uninstallDir($wp_filesystem = null, $basedir = '', $slug = '')
    {
        $dir = wp_normalize_path($basedir . $slug);
        if (!file_exists($dir)) {
            return;
        }
        $wp_filesystem->delete($dir, true);
    }
}

/**
 * Instantiate
 * @var \GMMoverFramework\GMMoverFactory $loaded_instance
 */
$cli = false;
$parameters = [];

if ("cli" === php_sapi_name()) {
    $cli = true;
    
    /** @var Type $argv Command Line arguments*/
    global $argv;
    $parameters = $argv;
}

$loaded_instance = new GMMoverFactory($cli, $parameters);
$loaded_instance->initHook();

pm_fs()->add_action('after_uninstall', [$loaded_instance, 'primeMoverCleanUpOnUninstall']);
