=== Migrate WordPress Website & Backups - GM Mover ===
Contributors: codexonics, freemius
Donate link: https://codexonics.com
Tags: migrate wordpress, multisite migration, wordpress backup, multisite, backup, database backup, migration
Requires at least: 4.9.8
Tested up to: 6.1
Requires PHP: 5.6
Stable tag: 1.8.0
License: GPLv3 or later
License URI: https://codexonics.com

The simplest all-around WordPress migration tool/backup plugin. These support multisite backup/migration or clone WP site/multisite subsite.

== Description ==

= Easily Transfer WordPress Site to New Host/Server/Domain =

*   Move single-site installation to another single site server.
*   Move WP single-site to existing multisite sub-site.
*   Migrate subsite to another multisite sub-site.
*   Migrate multisite sub-site to single-site.
*   Migrate within WordPress admin.
*   WordPress backup and restore packages within single-site or multisite.
*   Backup WordPress subsite (in multisite).
*   You can backup the WordPress database within admin before testing something and restore it with one click.
*   Cross-platform compatible (Nginx / Apache / Litespeed / Microsoft IIS / Localhost).
*   Clone single site and restore it to any server.
*   Clone subsite in multisite and restore it as single-site or multisite.
*   Supports legacy multisites.
*   Debug package.
*   Supports backup of the non-UTF8 single-site or multisite database.

https://youtu.be/QAVVXcoQU8g

= PRO Features =

*   Save tons of time during migration with the direct site to site package transfer.
*   Move the backup location outside WordPress public directory for better security.
*   Migrate or backup WordPress multisite main site.
*   Encrypt WordPress database in backups for maximum data privacy.
*   Encrypt WordPress upload files inside backup for better security.
*   Encrypt plugin and theme files inside the backup/package for protection.
*   Export and restore the backup package from Dropbox.
*   Save and restore packages from and to Google Drive.
*   Exclude plugins from the backup (or network activated plugins if multisite).
*   Exclude upload directory files from the backup to reduce the package size.
*   Create a new multisite subsite with a specific blog ID.
*   Disable network maintenance in multisite so only affected subsite is in maintenance mode.
*   Configure migration parameters to optimize and tweak backup/migration packages.
*   It includes all complete restoration options at your own choice and convenience.
*   Full access to settings screen to manage all basic and plugin advanced configurations.
*   Migrate non-UTF8 database charset to standard UTF8 database charset (utf8mb4).
*   Migrate UTF8 database charset (utf8mb4) to non-UTF8 database charset (edge case scenario).

= Documentation =

*	[GM Mover Documentation](https://codexonics.com/prime_mover/prime-mover/)

== Installation ==

1. Upload to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Optionally, opt in to security & feature updates notification including non-sensitive diagnostic tracking with freemius.com. If you skip this, that's okay! GM Mover will still work just fine.
4. You should see the GM Mover Control Panel. Click "Go to Migration Tools" to start migrating sites.

== Frequently Asked Questions ==

= What makes GM Mover unique to other existing migration and backup plugins? =
	
* The free version has no restriction on package size, number of websites or mode of migration (single-site or multisite will work). (Note: Exporting/Restoring a multisite main site is a PRO feature)
* The free version supports WordPress multisite migration on any number of subsites except that exporting/restoring the multisite main site is a PRO feature.
* It can backup WordPress multisite sub-sites or migrate multisite.
* No need to delete your WordPress installation, create/delete the database, and all other technical stuff. It will save you a lot of time.
* This is not hosting-dependent. GM Mover is designed to work with any hosting companies you want to work with.
* The free version has full multisite migration functionality. This feature is usually missing in most migration plugins free version.
* Full versatility - migrating from your localhost, dev site, or from a live site to another live site.
* You will be doing the entire migration inside the WordPress admin. Anyone with administrator access can do it. There is no need to hire a freelancer to do the job - saves you money.
* No messing with complicated migration settings, the free version has built-in settings. Only choose a few options to export and migrate, that's it. 
* You can save, download, delete, and migrate packages using the management page.
* No need to worry about PHP configuration and server settings. Compatible with most default PHP server settings even in limited shared hosting. 
* GM Mover works with modern PHP versions 5.6 to 8.1+ (Google Drive feature requires at least PHP 7.1).
* The code is following PHP-fig coding standards (standard PHP coding guidelines).
* The free version supports backup and restoration of non-UTF8 sites. However, you need the PRO version to migrate non-UTF8 to the UTF8 (utf8mb4) database charset and vice versa.
* You don't need to worry about setting up users or changing user passwords after migration. It does not overwrite existing site users after migration.

For more common questions, please read the [plugin FAQ listed in the developer site](https://codexonics.com/prime_mover/prime-mover/faq/).

== Screenshots ==

1. Single-site Migration Tools
2. Export options dialog
3. Export to single-site format example
4. Export to multisite subsite with blog ID of 23 example
5. Restore package via browser upload
6. Single-site package manager
7. GM Mover network control panel
8. Export and restore package from Network Sites
9. Multisite network package manager

== Upgrade Notice ==

Update now to get all the latest bug fixes, improvements and features!

== Changelog ==

= 1.8.0 =

* Feature: Full support of non-writable wp-config.php.
* Fixed: PHP Warning:  Zend OPcache API is restricted by "restrict_api" configuration directive.
* Fixed: Unable to create plugin manager script if MU plugins directory is not writable.
* Fixed: Runtime error on export due to third party plugin conficts.
* Fixed: Issues with exporting user taxonomies.
* Fixed: Missing constants on uninstall for non-writable wp-config.php.
* Fixed: Usability issue on upload misconfiguration error message.

= 1.7.2 =

* Fixed: Compatibility issues with ModSecurity module.
* Fixed: Unable to activate GM Mover due to hardcoded home/site URL constants in restricted config file.
* Fixed: Updated to latest Freemius 2.5.3 SDK.
* Fixed: Fatal error in Google Drive API when using PHP 5.6/7.0.
* Fixed: Bumped up PHP version requirement for Google Drive API to PHP 7.1+.

= 1.7.1 =

* Fixed: Deprecation notices and errors in PHP 8.1.
* Fixed: CORS issue with font assets.
* Fixed: Remote URL authorization issues with CORS.
* Fixed: cURL errors in processing HEAD request with PHP 8.0+.
* Fixed: Argument #1 ($handle) must be passed by reference, value given in PHP 8.0+.
* Fixed: Updated Freemius SDK to latest version 2.5.2 and compatibility fixes.
* Fixed: Issues on PRO upgrade workflow from free version.
* Fixed: Performance issues in remote URL migration feature.

See the previous changelogs in changelog.txt.
