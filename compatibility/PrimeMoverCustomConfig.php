<?php
namespace GreenMainframe\GMMoverFramework\compatibility;

/*
 * This file is part of the GreenMainframe.GMMoverFramework package.
 *
 * (c) GreenMainframe Ltd
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use GreenMainframe\GMMoverFramework\classes\GMMover;

if (! defined('ABSPATH')) {
    exit;
}

/**
 * GM Mover custom configuration class
 * Helper class for WordPress sites with customized wp-config.php file implementation.
 */
class PrimeMoverCustomConfig
{     
    private $prime_mover;
    private $config_utilities;
    private $system_utilities;
    
    /**
     * Construct
     * @param GMMover $prime_mover
     * @param array $utilities
     */
    public function __construct(GMMover $prime_mover, $utilities = [])
    {
        $this->prime_mover = $prime_mover;
        $this->config_utilities = $utilities['config_utilities'];
        $this->system_utilities = $utilities['sys_utilities'];
    }
    
    /**
     * Get system utilities
     * @return array
     */
    public function getSystemUtilities()
    {
        return $this->system_utilities;
    }    
    
    /**
     * Get config utilities
     * @return array
     */
    public function getConfigUtilities()
    {
        return $this->config_utilities;
    }
    
    /**
     * Get GM Mover instance
     * @return \GreenMainframe\GMMoverFramework\classes\GMMover
     */
    public function getPrimeMover()
    {
        return $this->prime_mover;
    }
    
    /**
     * Get system initialization
     * @return \GreenMainframe\GMMoverFramework\classes\PrimeMoverSystemInitialization
     */
    public function getSystemInitialization()
    {
        return $this->getPrimeMover()->getSystemInitialization();    
    }
    
    /**
     * Get system functions
     * @return \GreenMainframe\GMMoverFramework\classes\PrimeMoverSystemFunctions
     */
    public function getSystemFunctions()
    {
        return $this->getPrimeMover()->getSystemFunctions();
    }
    
    /**
     * Get system authorization
     * @return \GreenMainframe\GMMoverFramework\classes\PrimeMoverSystemAuthorization
     */
    public function getSystemAuthorization()
    {
        return $this->getPrimeMover()->getSystemAuthorization();
    }
        
    /**
     * Initialize hooks
     * @tested GreenMainframe\GMMoverFramework\Tests\TestPrimeMoverCustomConfig::itAddsInitHooks() 
     * @tested GreenMainframe\GMMoverFramework\Tests\TestPrimeMoverCustomConfig::itChecksIfHooksAreOutdated() 
     */
    public function initHooks()
    {
        add_filter('prime_mover_is_config_usable', [$this, 'isConfigUsable'], 10, 1);
        add_action('admin_init', [$this, 'initializeMustUseConstantScript'], 16); 
    }
 
    /**
     * Initialize must use plugin constant script
     */
    public function initializeMustUseConstantScript()
    {
        if (!$this->getSystemAuthorization()->isUserAuthorized()) {
            return;
        }
        
        if (wp_doing_ajax() || wp_doing_cron()) {
            return;
        }
        
        $must_use_constant_script = $this->getSystemInitialization()->getCliMustUseConstantScriptPath();        
        if (true === $this->isConfigUsable()) {
            return;
        }
       
        if ($this->getSystemUtilities()->isEncKeyValid() && $this->getSystemUtilities()->isWpSiteUrlValid() && $this->getSystemUtilities()->isWpHomeUrlValid()) {
            return;
        }
       
        if ($this->getSystemFunctions()->nonCachedFileExists($must_use_constant_script)) {
            return;
        }
       
        global $wp_filesystem;
        if (!$this->getSystemFunctions()->isWpFileSystemUsable($wp_filesystem)) {
            return;
        }
       
        $copy = false;
        if (wp_mkdir_p(WPMU_PLUGIN_DIR)) {
            $copy = $wp_filesystem->copy(PRIME_MOVER_MUST_USE_CONSTANT_SCRIPT, $must_use_constant_script, true);
        }
       
        if (!$copy) {
            return;
        }
        
        if (!$this->getSystemUtilities()->isWpSiteUrlValid()) {
            $this->getSystemFunctions()->filePutContentsAppend($must_use_constant_script, $this->getSystemUtilities()->outputSiteUrlParameter());
        }
        
        if (!$this->getSystemUtilities()->isWpHomeUrlValid()) {
            $this->getSystemFunctions()->filePutContentsAppend($must_use_constant_script, $this->getSystemUtilities()->outputHomeUrlParameter());
        }
    }
    
    /**
     * Checks if config file is usable
     * Must be writable and not customized
     * This return TRUE if wp-config.php file is writable AND not customized away from WP standards.
     * @param boolean $ret
     * @return string|boolean
     */
    public function isConfigUsable($ret = false)
    {
        if ($this->getSystemFunctions()->isConfigFileWritable() && false === $this->isUsingCustomConfigFile()) {
            $ret = true;
        }        
        
        return $ret;
    }
    
    /**
     * Checks if wp-config is customized
     * @return boolean
     */
    public function isUsingCustomConfigFile()
    {
        $config_transformer = $this->getConfigUtilities()->getConfigTransformer();
        if (!is_object($config_transformer)) {
            return false;
        }
        
        return (!$config_transformer->exists('variable', 'table_prefix'));
    }
    
    /**
     * Deactivate plugin
     */
    public function deactivate()
    {
        if ($this->getSystemAuthorization()->isUserAuthorized()) {
            primeMoverAutoDeactivatePlugin();
        }        
    }    
}