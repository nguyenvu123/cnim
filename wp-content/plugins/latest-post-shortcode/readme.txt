=== Latest Post Shortcode ===
Contributors: Iulia Cazan
Tags: posts shortcode, configurable shortcode with UI, content shortcode, customizable output, tiles from posts, grid from posts, latest post, latest posts, select posts, display posts, taxonomy, category, tag, parent, shortcode, latest, custom, selection, post by category, post by taxonomy, post by tag, post by id, post by parent, last post, tile template, short content, short excerpt, limit content, limit excerpt, pagination, posts pagination, custom slider
Requires at least: not tested
Tested up to: 5.2.2
Stable tag: 9.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Donate Link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=JJA37EHZXWUTJ

== Description ==
The "Latest Post Shortcode" helps you display a list or grid of the posts or pages in a page/sidebar, without having to code or know PHP. You can embed as many shortcodes in a page as you need, each shortcode configured in a different way. The shortcode for displaying the latest posts is [latest-selected-content] and can be generated very easy, the plugin will add a shortcode button for above in the editor area.
The "Latest Post Shortcode" is configurable and allows you to create a dynamic content selection from your posts, pages and custom post types by combining, limiting and filtering what you need. The output parameters are extremely flexible, allowing you to choose the way your selected content will be displayed.

You can write your own "read more" replacement, choose wether to show/hide featured images, you can even sort the items by a number of options, paginate the output (also AJAX pagination).
This plugin should work with any modern theme.

When used with WordPress >= 5.0 + Gutenberg, the plugin shortcode can be configured from any Classic block, using the plugin button.

= Usage example =
`[latest-selected-content perpage="4" showpages="4" display="title,date,excerpt-small" chrlimit="120" url="yes" linktext="Read more" image="thumbnail" elements="3" css="four-columns tall as-overlay light" type="post" status="publish" orderby="dateD" show_extra="ajax_pagination,light_spinner,pagination_all,date_diff,category,hide_uncategorized_category"]`

or simply

`[latest-selected-content limit="4" type="post" display="title,content-small" chrlimit="50" image="full" elements="0" css="two-columns" taxonomy="category" term="samples" orderby="dateA"]`

Starting with version 8.0, the plugin has a new UI and some new cool features. With this version, the output of the shortcode can be configured also as a slider, with responsive and different modes options. In this way, if you previously used the Latest Post Shortcode Extension, this is no longer needed, the plugin handles all by itself.

Starting with version 7.0, the plugin implements new hooks that allows for defining and managing your own custom output, through your theme or your plugins. The new hooks are:
- `lps_filter_tile_patterns` and `lps_filter_display_posts_list` - allows you to add your custom patterns
- `lps_filter_use_custom_tile_markup` - allows you to define your custom tile markup
- `lps_filter_use_custom_section_markup_start` and `lps_filter_use_custom_section_markup_end` - allows you to control the shortcode markup that is shown before and after the tiles block.
Check more hooks details and code sample at https://iuliacazan.ro/latest-post-shortcode/.

= Latest Post Shortcode Slider =
* https://wordpress.org/plugins/latest-post-shortcode-slider-extension/

== Installation ==
* Upload `Latest Post Shortcode` to the `/wp-content/plugins/` directory of your application
* Login as Admin
* Activate the plugin through the 'Plugins' menu in WordPress

== Hooks ==
admin_enqueue_scripts, init, plugins_loaded, media_buttons_context, admin_footer, admin_head, wp_head, lps_filter_tile_patterns, lps_filter_display_posts_list, lps_filter_use_custom_tile_markup, lps_filter_use_custom_section_markup_start, lps_filter_use_custom_section_markup_end, lps_filter_use_custom_shortcode_arguments, lps_filter_use_custom_query_arguments

== Screenshots ==
1. Options to configure the shortcode content type and filters.
2. Output examples as tiles with pagination and as slider.

== Frequently Asked Questions ==
= Where can I find the button for configuring the shortcode =
The button for configuring the shortcode is displayed as an icon or as the LPS button, depending on the mode you use when adding/updating content (the posts, pages, widgets, etc.):
- in the Visual mode of the editor, the button appears in the toolbar as an icon
- in the Text mode of the editor, the button appears in the toolbar as the LPS button

The button for the shortcode configurator can be used:
- when adding/editing posts, pages, text widgets
- in the Classic block for Gutenberg
- for version >= 8.7 the button is also available in Elementor

== Changelog ==
= 9.3 =
* Tested up to 5.2.2.
* Added `$args` argument for the custom tile markup filter for allowing access from other scripts to the shortcode configuration
* Added sticky posts filter: only sticky posts, no sticky posts, no restriction in terms of sticky option
* Added one more taxonomy filter and their terms input for more targeted filtering of posts
* Added the option for line break, that will clear the content below by adding a line break after the shortcode.

= 9.2.1 =
* Added the caption extra option to be exposed in the tile.
* Updated the mime type extra option to allow for selecting a position inside the tile for it.
* Tested up to 5.2.
* Tested with Elementor 2.5.15.

= 9.2 =
* Fix no link for tiles using as-overlay class
* Added new option to hookup the media link and media lightbox (integrate with Easy FancyBox and FooBox Image Lightbox)

= 9.1 =
* Fix attachment multiple status filters
* Added extra options for showing the mime type for attachments as text or/and as CSS class for the tile wrapper

= 9.0 =
* Tested up to 5.1.1
* Added the attachment tiles options
* Added date range and dynamic range filtering for items

= 8.7 =
* Tested up to 5.1
* Added configurable title wrap element
* Added raw content option
* Added four new tile patterns with more targeted links
* Added support for Elementor, the Latest Post Shortcode functionality can be used from Elementor, as a basic element.

= 8.61 =
* Fix resize when used with Gutenberg

= 8.6 =
* Tested up to 5.0.1
* Added 4 columns styles support
* Added styles to support tiles with image as the background and the content as the overlay text
* Added overlay (dark by default, but supports the `light` option)
* Fixed carousel placeholder
* Better translation

= 8.5 =
* Fix the multiple terms filter
* Change the date difference function to use the timezone from the settings

= 8.4 =
* Tested up to 4.9.8
* Implement the "load more" feature to switch the AJAX pagination into a load more button with customizable text
* Added AJAX spinner option for light and dark colors (you can still disable the spinner)
* Added date option as date difference, so that the date to read 30 minutes ago, or 2 das ago, etc.
* Added new tile pattern that allows to display date, title, excerpt, and content
* Fix the issue when first page from pagination was not showing as selected by default
* Allow for sliders without image (not recommended as it comes with more limitations like fixed height)

= 8.3 =
* Tested up to 4.9.7
* Added exclude by tags and exclude by categories in the UI
* Added plugin translations

= 8.2 =
* Pagination update to use a more intuitive display + auto exclude
* Option to display all elements of the pagination (to display the pagination elements all the time, including the disabled elements like: go to first, previous, next, and last page, even if these are disabled)
* Workaround for Gravity Forms compatibility
* SEO improvement

= 8.1 =
* Added missing assets from the recent release

= 8.0 =
* Tested up to 4.9.6
* New UI
* Added exclude content by post IDs option
* Added exclude content by author IDs option
* Added placeholder that allows to define an image to be used for the posts that do not have a featured image, so that the lists/grid looks nicer
* Slider output options

= 7.4 =
* Tested up to 4.8.2
* Add the posts filter option by author IDs
* Update the date arguments
* Added filters for shortcode arguments and shortcode query arguments.

= 7.3 =
* Added the option to show the author, categories and tags before or after specific tile elements.

= 7.2 =
* Added the option to exclude dynamic content already exposed by the shortcodes embedded above in the current page content.

= 7.1 =
* Extra options to display author and taxonomies
* Allow to order items by random

= 7.0 =
* New shortcode config UI
* Introduce hooks for allowing the definition of custom output

= 6.4 =
* Tested up to 4.8
* Three columns style fix

= 6.3 =
* Tested up to 4.5.2
* Fix parents list
* Replace thickbox style

= 6.2 =
* Add support for post status filter
* Add support for exclude tags by slugs. The new argument sample: exclude_tags="slug1,slug2"

= 6.1 =
* Add suppress filters false
* Apply filters before displaying the post image

= 6.0 =
* Add support for the Latest Post Shortcode Slider extension

= 5.4 =
* Add the plugin link
* Separate the content and excerpt filters
* Tested up to 4.3.1

= 5.3 =
* Add the 'open in a new window' option for the links

= 5.2 =
* Implement changes to render full posts content (including the extra shortcodes)

= 5.1 =
* Add the order option (by date, title, menu order)
* Add the ajax pagination option. As the shortcode pagination relies on the wp native pagination, when using more shortcodes with pagination on the same page, the navigation will affect all shortcodes, hence, by activating the ajax pagination, each shortcode pagination will act independent

= 5.0 =
* Add the extra display of post tags of the posts
* Add filter to allow the text widget to render the content of a shortcode

= 4.2 =
* Introduce the post date in the output. The general settings will apply to the date and time format.

= 4.1 =
* Add changes to the javascript to avoid the content check for resize when lightbox resources are not available (compatibility with other plugins)

= 4.0 =
* Add Pagination Position (default to top only) so that the pagination can be displayed below the results, or above and below the results
* Add Dynamic Tag option so that you can show the posts that have one of the current page tags (current page is the page where the shortcode is embedded), without the need to specify a particular tag. This is useful to display something like "similar posts" or "on the same topic", etc.

= 3.1 =
* Populate the "Use Image" dropdown dynamically from the list of image sizes registered in the application
* Add global tile a class to differentiate when the link is applied to the entire tile content or to just the "read more" text

= 3.0 =
* Add No Pagination / Paginate Results option that allows to paginate the posts selection
* Add Records Per Page option
* Add Offset
* Add Hide / Show Pagination Navigation that allows to hide or show the pagination
* Reload Tile Pattern selection when a shortcode is selected before clicking the plugin button (reload shotcode settings in the content selection lightbox)

= 2.0 =
* Allow for different tile pattern (the html tags order in the tile: post title, image, text and read more message)
* Add visual tile pattern selector
* Add short excerpt and short content options
* Add chars limit to the excerpt or content for the tile
* Add custom "read more" message option
* Allow for the post link to wrap the entire tile or just the "read more" message if this is set

= 1.0 =
* Plugin prototype

== Upgrade Notice ==
Chars limit, custom "read more" option and different tile patterns in the new version! You should upgrade, it's free!
Donation and reviews are welcomed and will help me to continue future development.

== License ==
This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

== Version history ==
9.3 - Tested up to 5.2.2., added `$args` argument for the custom tile markup filter, added sticky posts filter, added one more taxonomy filter, added the option for line break after the shortcode
9.2.1 - Added caption extra option, selectable mime type position, tested with Elementor 2.5.15, tested up to 5.2.
9.2 - Fix no link for tiles using as-overlay class, new option to hookup the media link and media lightbox (integrate with Easy FancyBox and FooBox Image Lightbox)
9.1 - Fix attachment multiple status filters, added extra options for showing the mime type for attachments as text or/and as CSS class for the tile wrapper
9.0 - Tested up to 5.1.1, added the attachment tiles, added date range and dynamic range filtering for items
8.7 - Tested up to 5.1, title wrap, new tile patterns, raw content, support for Elementor
8.61 - Fix resize when used with Gutenberg
8.6 - Tested up to 5.0.1, support for four columns, support for tiles overlay, better translation
8.5 - Fix multiple terms filter, date difference update
8.4 - Tested up to 4.9.8, load more option, AJAX spinners, date difference option, excerpt and content pattern, slider without images
8.3 - Tested up to 4.9.7, exclude by tags and by categories, plugin translations
8.2 - Pagination update, SEO improvement, Gravity Forms compatibility
8.1 - Added missing assets
8.0 - New UI, new content filters, placeholder, output as slider options, tested up to 4.9.6.
7.4 - Filters for shortcode arguments and shortcode query, filter by author, tested up to 4.8.2.
7.3 - Added the option to show the author, categories and tags before or after specific tile elements.
7.2 - Exclude dynamic content already exposed in the current page.
7.1 - Extra options to display author and taxonomies, and order by random
7.0 - Introduce hooks for allowing the definition of custom output and the new UI
6.4 - Three columns style fix, tested up to 4.8
6.3 - Fix parents list, tested up to 4.4.2
6.2 - Add status support and exclude by tags support
6.1 - Apply more filters
6.0 - Add support for the Latest Post Shortcode Slider extension
5.4 - Separate the content and excerpt filters
5.3 - Open links in a new window
5.2 - Render full post content
5.1 - Posts order and ajax pagination
5.0 - Extra tags display and text widget filter
4.2 - Post date option
4.1 - Compatibility update
4.0 - Pagination position and dynamic tag
3.1 - Dynamic image dropdown option
3.0 - Pagination options
2.0 - Visual pattern selector and more features
1.0 - Initial version.
