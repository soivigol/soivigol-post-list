=== List of post and other contents ===
Contributors:      soivigol,destaca
Tags:              block
Requires at least: 5.3.2
Tested up to:      5.8.2
Stable tag:        0.2.3
Requires PHP:      7.0.0
License:           GPL-2.0-or-later
License URI:       https://www.gnu.org/licenses/gpl-2.0.html

List of posts and other Custom Post Type with pagination, possibility to filter by category, number of posts per page, number of columns and multiples costumization options.

== Description ==

List of post and other contents is a block that shows you a list of your posts or other Cutom Post Types of your WordPress website with multiple customization options.
It is the ideal block to create blog pages or display in a list the posts of a Custom Post Type where you can easily customize the rest of the content of the page without losing the functionality offered by being able to list your posts with the page that offers WordPress own.
It can also be used to display the latest posts of your blog that are usually displayed on the home page or at other points.
In order to customize this block to your liking, it offers the following customization options:
- You can choose from the type of content that is registered on your website.
- In the case of posts, you can choose a specific category.
- You can customize the layout, choosing the number of entries, number of columns and if you want pagination. Pagination will only work well with one block per page.
- You can also customize the background and text colors.
- Set the border and border-radius of the box.
- Choose the format in which the featured image will be displayed.
- You can set the color, label and font size of the title.
- Set whether you want the excerpt to be displayed, the size of the excerpt in characters, whether you want the link to the individual entry to be made in the whole box or only in a link where you can customize the text.

In addition, this plugin provides 5 hooks so you can insert different customizations inside the loop. A very interesting option if you are creating a Custom Post Type list and need to display different Custom Field options. The hooks are the following:
- spt_after_bottom_loop
- spt_before_title_loop
- spt_after_title_loop
- spt_after_content_loop
- spt_after_button_loop

== Installation ==

How to install the plugin.

1. Upload the plugin folder to the `/wp-content/plugins/` directory or install the plugin through the WordPress plugin panel.

2. Activate the plugin in the "Plugins" WordPress section.

3. Use for free the customization section.

== Screenshots ==

1. Select plugin.
2. Custom options.

== Changelog ==

= 0.1.0 =
* Release
-   First version.

= 0.1.3 =
* Release
-   Fixed bugs.

= 0.1.4 =
* Release
-   Adding filter by custom post type.

= 0.1.5 =
* Release
- Fix button read more with Astra

= 0.2.0 =
* Release
- NEW features and design improvements

= 0.2.3 =
* Fix
- Fix errors if the attributes are empty.
