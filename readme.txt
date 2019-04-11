=== Tweettee ===
Contributors: wladweb
Donate link: 
Tags: Twitter, Tweets, Timeline, Mentions
Requires at least: 4.6
Tested up to: 5.1.1
Stable tag: trunk
Requires PHP: 5.4.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Plugin can show tweets in widget and in home page. 

== Description ==

Plugun show twitter user timeline, home timeline, mentions, home timeline another user, search result by post tags, category name and any words. 
Tweets can be show in widget and also in home page between posts in main loop.

== Installation ==

1. Upload plugin folder to the `/wp-content/plugins/` directory
2. Activate plugin in menu 'Plugins'.
3. Go to plugin settings page.

== Frequently Asked Questions ==

= Where i can create new application =

https://developer.twitter.com/en/apps


== Screenshots ==

1. Tweettee settings.
2. Widget.
3. Block in home page.

== Changelog ==

= 1.1.0 =
Add caching
Add logging
Were rewritten some parts of plugin

= 1.0 =
* First version

== Upgrade Notice ==

= 1.1.0 =
New caching system allows avoid making request in Twitter from every page loading in your website.
Now set of twitts received once will be stored in database for time_interval which you may specify
in cache settings. After that time, request will be accomplished again with new pack of twitts. 

= 1.0 =
* First version
