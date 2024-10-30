=== BP-FBConnect ===
Requires at least: BuddyPress 1.1 / WordPress MU 2.8.4
Tested up to: BuddyPress 1.1 / WordPress MU 2.8.4
Stable Tag: 1.2
Tags: facebook, buddypress, social, friends
Contributors: apeatling, ahupp

== Description ==

Adds support for logging in via Facebook to BuddyPress installations.
Uses the wp-facebookconnect plugin by Adam Hupp. Currently only
supports logging in, but in the future support for news feed publishing
and friend list integration will be added.

== Installation ==

 1. Copy the plugin to wp-content/plugins/bp-fbconnect/
 2. In the Wordpress Admin panel, visit the plugins page and Activate the plugin.
 3. Visit the settings page and select "Facebook Connect".  Follow the
 given instructions to configure the plugin and obtain a Facebook API key.
 4. The login button should appear automatically on your site login bar. If not
you will need to add the following line to your theme (where you want the login button):

  `<?php do_action('fbc_display_login_button') ?>`

== Known Issues ==

When updating anything via AJAX, the FB-connect JS will not fire, this means avatars will
only display the generic Facebook avatar image.