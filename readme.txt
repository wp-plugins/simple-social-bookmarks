=== Simple Social Bookmarks ===
Contributors: dartiss
Donate link: http://tinyurl.com/bdc4uu
Tags: AddToAny, Delicious, Digg, Email, Facebook, Google Reader, Google Bookmarks, Google Buzz, LinkedIn, MySpace, Ping.FM, Reddit, StumbleUpon, Technorati, Twitter, Yahoo! Bookmarks, Yahoo! Buzz
Requires at least: 2.0.0
Tested up to: 2.9.1
Stable tag: 1.6

Simple Social Bookmarks displays icons for various popular social bookmarks sites on your posts and/or pages.

== Description ==

**From version 1.3 you must now install the [Simple URL Shortener](http://wordpress.org/extend/plugins/simple-url-shortener/ "Simple URL Shortener") plugin for the URL shortening to work in this plugin**

**From version 1.4 the caching of short URLs is now performed by [Simple URL Shortener](http://wordpress.org/extend/plugins/simple-url-shortener/ "Simple URL Shortener") - please ensure you have the latest version to ensure that this works**

The code for Simple Social Bookmarks should be added to the bottom of appropriate post/page templates within your theme (and in the case of posts, within the loop).

Here is an example...

`<?php echo simple_social_bookmarks('','Currently reading a blog post titled %title% - %url%','padding-left: 5px; padding-right: 5px','service=is.gd&iconfolder=24x24'); ?>`

There are 3 parameters in total that require passing. 

The first parameter is the URL that you wish the Social Bookmarks to respond to. If left blank (and this is probably the default for most people) it will use the URL of the current post/page.

The second parameter is the text that is passed to Twitter. If you don't specify anything then it will take you to your Twitter account and pre-fill in the status with "I'm currently reading xxx", where xxx will be the URLaddress of the page/post that you just came from.

If, however, you'd like to define your own Twitter text, then you simply pass this as the second parameter. `%url%` must be specified within the sentence, as this specifies where you want the URL to go. You can also the tag `%title%`, which will show the post/page title in the Twitter text.

So, in the example above, it will set the status to "Currently reading a blog post titled yyy - xxx", where xxx is the URL and yyy is the title.

The third parameter is a style definition which applies to the bookmark icons. In the example above adds a 5 pixel padding to the left and right of each icon.

Finally, the fourth parameter can contain a number of sub-parameters, all of which should be divided by an ampersand. These sub-parameters are as follows...

**default=** - This should be on or off and defines whether ALL the social bookmarks should display by default or not. Not specifying this sub-parameter will cause all to be displayed.

**service=** - This allows you to specify a URL shortening service to be used with the URLs. See the documentation for Simple URL Shortener for a complete list of those that are available.

**iconfolder=** - By default, a set of icons in the plugin's folder will be used (kindly provided by komodomedia.com). If, however, you'd like to use your own icons then you will need to add a sub-folder and then use this parameter to specify the sub-folders name. In the above example, I am specifying a sub-folder named `24x24`.

**nofollow=** - If specified as `YES` this will turn on the `NOFOLLOW` attribute for the links. By default, this is switched off.

**target=** - Allows you to override the standard `TARGET` of `_BLANK`.

**cache=** - Specify as `NO` to turn off the caching of short URLs

There are also sub-parameters for EACH of the social bookmarking services, allowing you to specify for each whether they should be turned on or off. So, for example, to turn Digg off, you would specify `digg=off`.

So, as a further example, if you ONLY wanted the Delicious and Digg bookmarks to appear, it would be best to specify the `default` as off and then turn Delicious and Digg on individually, like so...

`<?php echo simple_social_bookmarks('','','','default=off&delicious=on&digg=on'); ?>`

The full list of social bookmark services are as follows...

* AddToAny#
* Delicious
* Digg
* Email#
* Facebook
* Google Bookmarks# (use "Google" as the bookmark name)
* Google Buzz# (use "GoogleBuzz" as the bookmark name)
* Google Reader# (use "Reader" as the bookmark name)
* LinkedIn
* MySpace
* Ping.FM#
* Reddit
* StumbleUpon
* Technorati#
* Twitter
* Yahoo! Bookmarks# (use "Yahoo" as the bookmark name)
* Yahoo! Buzz# (use "YahooBuzz" as the bookmark name)

# These services will not appear by default, as they were added later. This includes when using the default=on parameter.

Here is a further version of the original example, but this time with a check to confirm that they plugin is active...

`<?php if (function_exists('simple_social_bookmarks')) : ?>`
`<?php echo simple_social_bookmarks('','Currently reading a blog post titled %title% - %url%','padding-left: 5px; padding-right: 5px','service=is.gd&iconfolder=24x24'); ?>`
`<?php endif; ?>`

It's also possible to add the Social Bookmarks to your WordPress feed. Open up your `functions.php` file within your theme folder and add the following code...

`<?php`
`function insertRss($content) {`
`    if ((is_feed())&&(function_exists('simple_social_bookmarks'))) {`
`        $content = $content."<p>".simple_social_bookmarks('','Currently reading a blog post titled %title% - %url%','padding-left: 2px; padding-right: 2px','service=is.gd&iconfolder=16x16')."</p>";`
`    }`
`    return $content;`
`}`
`add_filter('the_content', 'insertRss');`
`?>`

Obviously, you will need to modify the parameters that are passed to the Simple Social Bookmarks plugin, to make it appropriate for yourself. In the above example you may notice that I'm using smaller icons with a small padded space between them.

== Installation ==

1. Upload the entire simple-social-bookmarks folder to your wp-content/plugins/ directory.
2. Activate the plugin through the ‘Plugins’ menu in WordPress.
4. There is no options screen - configuration is done in your code.

== Frequently Asked Questions ==

= The Social Bookmarking site is not appearing =

If it's a newly added site then it will not (even if you specify `default=on`) - you will need to specifically switch it on.

Here's an example of switching on Google Buzz...

`<?php echo simple_social_bookmarks('','Currently reading a blog post titled %title% - %url%','padding-left: 5px; padding-right: 5px','service=is.gd&googlebuzz=on'); ?>`

= The Google Buzz option isn't working =

There is no "official" option to share a link with Google Buzz. Instead, this uses the Google Reader share option - therefore your Google Reader shared account has to be hooked up to your Google Buzz account (assuming you use Google Reader, Google did this automatically for you anyway).

= You haven't included my favourite Social Bookmarking service ==

I didn't want to maintain a large number of, mainly, smaller and lesser used services. I have therefore only included those that I believe are the most popular. If, however, you think I have missed one of great importance, please get in contact with me and plead your case!

= How can I get help or request possible changes =

Feel free to report any problems, or suggestions for enhancements, to me either via my contact form or by the plugins homepage at http://www.artiss.co.uk/simple-social-bookmarks

== Changelog ==  
  
= 1.0 =  
* Initial release

= 1.1 =
* Now XHTML compliant

= 1.2 =
* Fixed issue with ampersands (and probably other characters) in the blog title causing linking issues
* Added su.pr as another URL shortening service

= 1.3 =
* Updated default icons to a set kindly provided by komodomedia.com (With the exception of the AddToAny and Ping.FM icons)
* Added AddToAny, Ping.fm, Google Bookmarks, Google Reader and email to the list of providers. By default these will be turned off so as to not to suddenly appear on existing installs and cause problems!
* Further improvement to blog title issues
* Removed URL shortening on all services with the exception of Twitter, as it was not required and may have even have caused some issues (such as some services not being able to get hold of thumbnails and general page content).
* Now using [Simple URL Shortener](http://wordpress.org/extend/plugins/simple-url-shortener/ "Simple URL Shortener") plugin to provide URL shortening
* Caching of short URLs now takes place providing large performance improvements
* NOFOLLOW and TARGET parameters added
* Compressed default icons

= 1.4 =
* Removed caching, as this is now handled by [Simple URL Shortener](http://wordpress.org/extend/plugins/simple-url-shortener/ "Simple URL Shortener"). Please ensure you update to the latest version of this plugin to ensure that caching continues to work - once you know it does, the cache folder for this plugin can be removed.
* Added additional parameter - cache=
* Tidied code and updated shared functions

= 1.5 =
* Use different encoding for email links
* Added Technorati to the list of providers. By default this will be turned off (including using the default=on option) so as to not to suddenly appear on existing installs.

= 1.6 =
* Added Yahoo! Bookmarks, Yahoo! Buzz and Google Buzz to the list of providers. By default these will be turned off (including using the default=on option) so as to not to suddenly appear on existing installs.

== Upgrade Notice ==

= 1.5 =
* Upgrade if you use the email option, as this will fix a problem with the text encoding
* Also added Technorati as a social bookmarking service

= 1.6 =
* Upgrade if you want to use the Yahoo! Bookmarks, Yahoo! Buzz or Google Buzz social bookmarking services