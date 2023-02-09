<?php
/**
 *
 * This is the overall requirements check class.
 *
 */
class GMMoverRequirementsCheck
{    
    /**
     * 
     * @var GMMoverWPCoreDependencies
     */
    private $corewpdependencies;
    
    /**
     * 
     * @var GMMoverPHPVersionDependencies
     */
    private $phpversiondependencies;
    
    /**
     * 
     * @var GMMoverPHPCoreFunctionDependencies
     */
    private $phpfuncdependency;
    
    /**
     * 
     * @var GMMoverFileSystemDependencies
     */
    private $filesystem_dependency;
    
    /**
     *
     * @var GMMoverPluginSlugDependencies
     */
    private $foldernamedependency;
  
    /**
     *
     * @var GMMoverCoreSaltDependencies
     */
    private $coresaltdependency;
    
    /**
     * 
     * @param GMMoverPHPVersionDependencies $phpversiondependencies
     * @param GMMoverWPCoreDependencies $corewpdependencies
     * @param GMMoverPHPCoreFunctionDependencies $phpfuncdependency
     * @param GMMoverFileSystemDependencies $filesystem_dependency
     * @param GMMoverPluginSlugDependencies $foldernamedependency
     * @param GMMoverCoreSaltDependencies $coresaltdependency
     */
    public function __construct( $phpversiondependencies, $corewpdependencies, $phpfuncdependency, $filesystem_dependency, $foldernamedependency, $coresaltdependency)
    {
        $this->phpversiondependencies = $phpversiondependencies;
        $this->corewpdependencies = $corewpdependencies;
        $this->phpfuncdependency = $phpfuncdependency;
        $this->filesystem_dependency = $filesystem_dependency;
        $this->foldernamedependency = $foldernamedependency;
        $this->coresaltdependency = $coresaltdependency;
    }
    
    /**
     * 
     * @return GMMoverCoreSaltDependencies
     */
    public function getCoreSaltDependency()
    {
        return $this->coresaltdependency;
    }

    /**
     * 
     * @return GMMoverPluginSlugDependencies
     */
    public function getPluginFolderNameDependency()
    {
        return $this->foldernamedependency;
    }
    
    /**
     * @compatible 5.6
     * @return GMMoverPHPVersionDependencies
     */
    public function getPHPVersionDependencies()
    {
        return $this->phpversiondependencies;
    }
    
    /**
     * @compatible 5.6
     * @return GMMoverWPCoreDependencies
     */
    public function getCoreWPDependencies() 
    {
        return $this->corewpdependencies;
    }
 
    /**
     * @compatible 5.6
     * @return GMMoverPHPCoreFunctionDependencies
     */
    public function getPHPCoreFunctionDependencies()
    {
        return $this->phpfuncdependency;
    }
    
    /**
     * @compatible 5.6
     * @return GMMoverFileSystemDependencies
     */
    public function getFileSystemPermissionChecks() {
        return $this->filesystem_dependency;
    }
    
    /**
     * Do an overall sanity checks if all dependencies required are meet
     * @return boolean
     * @compatible 5.6
     * @tested GreenMainframe\GMMoverFramework\Tests\TestPrimeMoverRequirements::itChecksCorrectPluginRequirementsSingleSite()
     * @tested GreenMainframe\GMMoverFramework\Tests\TestPrimeMoverRequirements::itChecksCorrectPluginRequirementsMultisite()
     */
    public function passes()
    {
        $passes = false;

        $phpversion_check = $this->getPHPVersionDependencies()->phpPasses();
        $wpversion_check = $this->getCoreWPDependencies()->wpPasses();        
        $phpextensions_check = $this->getPHPCoreFunctionDependencies()->extensionsRequisiteCheck();
        $phpfunction_check = $this->getPHPCoreFunctionDependencies()->functionRequisiteCheck();
        $filesystem_check = $this->getFileSystemPermissionChecks()->fileSystemPermissionsRequisiteCheck();
        $pluginfoldername_check = $this->getPluginFolderNameDependency()->slugPasses();
        $coresaltdependency_check = $this->getCoreSaltDependency()->saltPasses();
        
        if ( $phpversion_check && $wpversion_check && $phpextensions_check && $phpfunction_check && $filesystem_check && $pluginfoldername_check && $coresaltdependency_check) {
            $passes = true;
        }
        
        if (! $passes) {
            global $pm_fs;
            if (is_object($pm_fs)) {
                remove_action( 'admin_init', array($pm_fs, '_admin_init_action' ));
            }            
            add_action('admin_init', array( $this, 'deactivate' ));
        }
        
        return $passes;
    }
        
    /**
     * Deactivate plugin
     * @compatible 5.6
     */
    public function deactivate()
    {
        primeMoverAutoDeactivatePlugin();
    }
}