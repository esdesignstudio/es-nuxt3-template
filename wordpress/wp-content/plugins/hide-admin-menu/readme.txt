=== Hide Admin Menu ===
Contributors: Bhavin Thummar & Maulik Patel
Donate link: https://www.paypal.me/BThummar
Tags: wordpress, admin, menu hide, admin menu, admin menu hide, admin menu show, admin menu plugin, user role
Requires at least: 4.6
Tested up to: 5.9.3
Stable tag: 1.1.1
License: GPLv2 or later
License URI: 
 
Using this plugin we can hide the admin menu easily.
 
== Description ==

This plugin gives the facility for hiding and showing the admin menu of side and top bar.

This plugin gives the easy way to hide admin menus by checking the checkbox of particular menu in the form then submit the form so that checked menus hide from the admin.

Admin also can hide menu according the role of users.

<iframe width="560" height="315" src="https://www.youtube.com/embed/LiXcE6aEvdI" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
 
== Installation ==
 
1. Upload hide-admin-menu to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Open Hide Menu from menu bar and then check or tick mark those menus that you want hide from admin bar.
 
== Frequently Asked Questions ==
 
= How can i show hide menus? =
 
 You can show menu that is hide by this plugin doing the uncheck those menus from the menu list in form.
 
= What should you do when you hide all the menu by this plugin? =

You should open below URL
YOUR-WEBSITE-URL/wp-admin/admin.php?page=hide-admin-menu

Update necessary setting to show any menu again by this URL.	
 
== Screenshots ==

1. This is the page of menu hide where user can hide and show admin menu.

 
== Changelog ==
1. Solved some warning and notices at time of save process at version WordPress 4.8.  
2. Solved 2 character error issue at time of activation the plugin and remove deprecate function on version 1.0.4
3. Tested with WordPress version 5.4.1.
4. Solved the error which shown in the site health tools.
5. Solved the issue of the session related in the version of the 1.0.7. So please update the version of 1.0.8 which is the latest one.
6. Removed the use of the $_SESSION of PHP and used the wp_session in version 1.0.9 to solve the session related warning in the website. 
7.In version 1.1.0 , solved the issue of not hiding the customize menu. Also removed unnecessary css for one class from the css file which are conflicts with other css.
8. Tested with WordPress version 5.9.3
== Upgrade Notice ==
1. This new version where you can also hide the sub menu of the side bar of admin. 
2. Tested up to WordPress version 5.0.2
3. We have added parent and child structure for top admin menu to version 1.0.3 according one user request. 
4. We have added new feature of menu hide according user role   .