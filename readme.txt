=== WPJaipho Mobile Gallery ===
Contributors: tolecar, nosf, secretja
Tags: photos,slideshow,images,gallery,media,photo-albums,pictures,photo,picture,image,iphone,ipad,mobile,android,smartphone,swipe
Requires at least: 3.0
Tested up to: 3.9
License: GPLv2
Stable tag: 1.4.3

WPJaipho extends native Wordpress image gallery and NextGEN Gallery with optimized support for mobile users

== Description ==

WPJaipho is plugin which adds mobile optimized look & feel to your WP based galleries.
It is based on the original <a href="http://jaipho.com/" target="_blank">Jaipho</a>, iPhone optimized Javascript gallery and it works on iPhone, iPad and Android devices.

= Supported WP galleries =
WP Jaipho works with: <a href="http://wordpress.org/extend/plugins/nextgen-gallery/" target="_blank">NextGEN</a> Gallery and Default WP Gallery (via Media Library). It also works flawlessly with some mobile theme plugins such as <a href="http://wordpress.org/extend/plugins/wptouch/" target="_blank">WPTouch</a> and <a href="http://wordpress.org/extend/plugins/wordpress-mobile-pack/" target="_blank">WordPress Mobile Pack</a>.

= How Does it Work? =
Simple enough - just activate the plugin, and all your galleries are automatically transformed into mobile optimized gallery, which is visible for mobile users only. Check out demo <a href="http://www.viberfaq.com/viber-android-beta-version-review/" target="_blank">here</a> with your iPhone.

= Further Resources =

* WPJaipho installation on <a href="http://www.viberfaq.com/viber-android-beta-version-review/" target="_blank">The Viber FAQ</a><br>
* <a href="http://jaiphodemo.info/" target="_blank">Jaipho Demo</a><br>
* <a href="http://jaipho.com/" target="_blank">Jaipho Gallery Official Website</a><br>



== Installation ==

1. Download the plugin from Wordpress.org, and install the plugin.

2. Activate WPJaipho after the installation.

3. Optionally - visit Settings page for some modifications.


After the activation, any page containing Gallery made on WP Media Library or NextGEN plugin will work out of the box, transforming the gallery pages into mobile optimized for all iPhone and iPad visitors on your site. 

== Changelog ==

= v1.4.3 - 2.5.2013 - javascript arrays fix =
* Bugfix : fixed javascript compatibility issue with scripts which are modifyng the Array object itself
* Bugfix : disabled javascript debug related options on settings page


= v1.4.2 - 10.2.2013 - ngg permalinks and quotes fix =
* Bugfix : Fixed not working direct slide selection on NextGEN gallery when permalinks are active
* Bugfix : Removed addslashes for NextGEN Gallery images (NextGEN fixes quotes by itself) 
* Bugfix : Forcing the Yoast WordPress SEO to disable "Redirect attachment URL's to parent post URL." option


= v1.4.1 - 26.11.2012 - selection fix =
* Bugfix : Fixed not working direct slide selection
* Bugfix : Javascript library upgraded to Jaipho 0.60.1
* Bugfix : Fixed problem with multiline image descriptions

 
= v1.4.0 - 31.10.2012 - Android support =
* Feature : Support for Android users (before was limited to iOS only)
* Feature : User agent matcher config option to trigger WPJaipho for other devices
* Feature : Paste html code to splashscreen or thumbnails view through admin options
* Feature : For developers: better integration with WordPress debugging facilities (WP_DEBUG and WP_DEBUG_LOG constants, debug.log file)


= v1.3.0 - 28.9.2012 =
* Feature : Added posibility to disable displaying of image title and description.
* Bugfix : Two NextGEN galleries on a single page are now working OK. It was showing the last one.
* Bugfix : Splashcreen duration admin option now can be set to 0. That way you can disable splashscreen completely.


= v1.2.1 - 20.9.2012 =
* Bugfix : suppressed eventual php warnings when calling realpath() function. Prior to this version, realpath() call could trigger php warning when the php safe_mode was on.


= v1.2.0 - 4.9.2012 =
* Feature : basic NextGEN Gallery support - [nggallery] shortcode and only "id" attribute are used
* Feature : thumbnails view support
* Feature : new settings options for chosing Jaipho theme
* Feature : added hooks for extending WPJaipho - jaipho_theme_folder_filter, jaipho_template_api_handler_filter, jaipho_plugin_enabled_filter, jaipho_template_file_filter


= v1.1.1 =
* Bugfix : changed reference to plugin folder from wp-jaipho to wpjaipho


= v1.1.0 =
* Feature : WPJaipho is now using wp_head function. That means that the head information set by other plugins (like All in one SEO) will be preserved on gallery pages too.


= v1.0.0 =
* Feature : Works with Wordpress native gallery (shortcode [gallery])
* Feature : Settings page with configuration options

-------------