=== Plugin Name ===
Contributors: jackreichert
Donate link: http://www.jackreichert.com/the-human-fund/
Tags: category, random, excerpts, testimonials
Requires at least: 2.8
Tested up to: 3.2.1
Stable tag: trunk

Creates a widget that takes randomly a number of excerpts from a category of your choice and fades them in and out.

== Description ==

This plugin was developed to display excerpts from random posts in a category. jQuery is used for fading the plugin in and out. It's a great way for displaying testimonials of clients or just giving a snapshot of content from your site. 

**Shortcode added as of version 1.4**

The most basic use is: `[reFader]` this will use the default options.
Here is an example of all the options in use 
`[reFader title="Random Excerpts" cat="23" amount="5" length="50" duration="5000" linked="no" url="http://www.jackreichert.com/plugins/random-excerpts-fader/"]`

This will show 5 (amount) posts from category #23 (cat). It will show 50 (length) words from each post and fade out/in every 5 seconds (duration). It will not link each title to the corresponding post, but it **will** link **all** the titles to this plugins page (url). 

== Installation ==

1. Upload the RandomExcerptsFader folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place widget in desired place and set options there.

== Frequently Asked Questions ==

= Are you planning on developing this further to include thumbnails? =

Eventually.

= What about foo bar? =

The foo bar is not a problem here. Don't worry about it.

== Changelog ==
= 1.4 =
* Added Shortcode
* Cleaned up jQuery

= 1.3.1 =
* Opacity bug

= 1.3 =
* Fixed IE bugs
* Rewrote the js to make it more efficient
* Added fixed height option

= 1.2.7 =
* Tested on 3.2 RC1
* "all categories" had stopped working in this version - Fixed.


= 1.2.5 =
* Fixed title when not linked

= 1.2.4 =
* fixed linking bug (thanks to Martin).

= 1.2.3 =
* Switched loop to get_posts.

= 1.2.2 =

* Styles enqueued properly.

= 1.2 =

* Replaced deprecated the_content_rss() with custom function.

= 1.1 =

* Fix for includes.

= 1.0 =
* This is the first live version
