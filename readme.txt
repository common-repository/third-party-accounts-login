=== Plugin Name ===
Contributors: Anant Garg
Tags: Comments, Third Party Login, Google, Yahoo, Gmail, Flickr, AOL
Requires at least: 2.0
Tested up to: 2.7
Stable tag: 1.0

Enable your users to comment by entering their name/email/website details via well known OpenID service providers like Google, Yahoo, Flickr etc. 

== Description ==

The Third Party Accounts Login plugin allows you to enable your users to comment via well known OpenID service providers like Google, Yahoo, Flickr etc.  The functionality is similar to that provided by StackOverflow and RPXNow.

The plugin allows the user to provide his/her details via all the famous OpenID service providers (Google, Yahoo etc.) while commenting. A working example of the plugin can be found on http://anantgarg.com

To make this plugin work make sure you have OpenID for WordPress Plugin installed.

== Installation ==

1. Download the plugin file
2. Extract the archive contents to the wp-content/plugins folder i.e. wp-content/plugins/third-party-accounts-login
3. Login to WordPress Administration Center
4. Activate the plugin
5. Goto Settings -> Third Party Accounts Login and set your preferences. Currently, you can choose from three different views- Only text, small icons, X highlighted (large icons) + remaining small icons. You can hide the icons you do not want to display by double-clicking on them and drag them to order them.
6. Add the following line in comments.php: &lt;?php `third_party_accounts_login();` ?&gt;


7. Make sure your OpenId textbox has an id="openid_identifier"

Sample code of comments.php will be something like:

`<label >Please select one of these third-party accounts:</label><br/> 
<?php third_party_accounts_login(); ?>
<br/>
<label>Or enter your OpenId URL:</label><br/>
<input type="text" name="openid_identifier" id="openid_identifier"  class="textfield" tabindex="4" style="width:300px" />`


For more details, please visit: http://anantgarg.com/wordpress-third-party-accounts-login-plugin

== Frequently Asked Questions ==

= What are the plugin requirements? =

This plugin requires OpenID plugin to be installed.

= I have a question/I want to add X OpenID provider? =

Feel free to contact me at http://anantgarg.com/wordpress-third-party-accounts-login-plugin

== Screenshots ==

1. Only text view
2. Small icons view
3. Highlighted + small icons view
