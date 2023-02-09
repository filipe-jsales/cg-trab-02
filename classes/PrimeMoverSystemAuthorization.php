<?php
namespace GreenMainframe\GMMoverFramework\classes;

/*
 * This file is part of the GreenMainframe.GMMoverFramework package.
 *
 * (c) GreenMainframe Ltd
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

if (! defined('ABSPATH')) {
    exit;
}

/**
 * The GM Mover System Authorization Class
 *
 * The GM Mover System Authorization provides authentication layer for the class methods and usage.
 */
class PrimeMoverSystemAuthorization
{
    
    /** @var boolean is_authorized */
    private $is_authorized;
    
    /** @var integer GM Mover user ID */
    private $prime_mover_user_id;
    
    /**
     *
     * @param \WP_User $user
     * @compatibility 5.6
     */
    public function __construct(\WP_User $user)
    {
        $this->is_authorized = $this->checksIfUserIsAuthorized($user);
    }
    
    /**
     * Checks if this user is authorized to use the classes and methods
     * @param WP_User object $user
     * @compatibility 5.6
     * @tested GMMoverFramework\Tests\TestPrimeMoverSystemAuthorization::itDoesNotAuthorizeIfNotMultisite() 
     * @tested GMMoverFramework\Tests\TestPrimeMoverSystemAuthorization::itDoesNotAuthorizeIfNotCurrentUser()
     * @tested GMMoverFramework\Tests\TestPrimeMoverSystemAuthorization::itReturnsFalseIfUserIsNotSuperAdmin()
     * @tested GMMoverFramework\Tests\TestPrimeMoverSystemAuthorization::itChecksIfUserIsAuthorizedSuperAdmin() 
     */
    final protected function checksIfUserIsAuthorized($user = null)
    {
        $authorized = false;
        if (! $user) {
            return $authorized;
        }
        $multisite = false;
        if (is_multisite()) {
            $multisite = true;
        }
        if ($this->canManageSite($user->ID, $multisite) && get_current_user_id() === $user->ID) {
            $authorized = true;
        }
        
        if ($authorized) {
            $this->prime_mover_user_id = $user->ID;
        }
        return $authorized;
    }
    
    /**
     * Check if currently logged-in can manage network
     * @param number $user_id
     * @return boolean
     */
    final protected function canManageSite($user_id = 0, $multisite = true) 
    {
        if ( ! $user_id ) {
            return false;
        }
        if ($multisite && user_can($user_id, 'manage_network')) {
            return true;
        }
        if ( ! $multisite && user_can($user_id, 'manage_options')) {
            return true;
        }
        return false;
    }
    /**
     * Gets user authorization
     * @compatibility 5.6
     * @tested GMMoverFramework\Tests\TestPrimeMoverSystemAuthorization::itDoesNotAuthorizeIfNotMultisite() 
     * @tested GMMoverFramework\Tests\TestPrimeMoverSystemAuthorization::itDoesNotAuthorizeIfNotCurrentUser()
     * @tested GMMoverFramework\Tests\TestPrimeMoverSystemAuthorization::itReturnsFalseIfUserIsNotSuperAdmin()
     * @tested GMMoverFramework\Tests\TestPrimeMoverSystemAuthorization::itChecksIfUserIsAuthorizedSuperAdmin() 
     * 
     */
    final public function isUserAuthorized()
    {
        return $this->is_authorized;
    }
    
    /**
     * Checks if current user is GM Mover user
     * @param number $user_id
     * @return boolean
     */
    final public function isPrimeMoverUser($user_id = 0)
    {
        if ( ! $this->isUserAuthorized() ) {
            return false;
        }
        
        if ( ! $user_id ) {
            return false;
        }
        
        return ($this->prime_mover_user_id === $user_id);        
    }
}
