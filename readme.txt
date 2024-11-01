=== WP Pic Tagger ===
Contributors: druesome
Donate link: http://www.alleba.com/blog/
Tags: images, annotation, tagging, pictures
Requires at least: 3.0
Tested up to: 3.0.1
Stable tag: 0.1

Tag, caption, annotate pictures and images on your Wordpress blog.

== Description ==

This plugin allows you to tag people and objects in your images by selecting a region of the image and add names, URLs and descriptions.  Implements the jQuery-Notes plugin by Lukas Rydygel (http://jquery-notes.rydygel.de/) and the jQuery-autocomplete plugin by Dylan Verheul (http://code.google.com/p/jquery-autocomplete/).

See the [WP Pic Tagger homepage](http://www.alleba.com/blog/2010/09/29/wordpress-plugin-wp-pic-tagger/) for more complete and detailed information.

== Installation ==

1. Unzip the archive 'wp-pic-tagger.zip' to a local folder on your computer.
2. Upload the folder 'wp-pic-tagger' and its contents to your blog's plugin folder (root/wp-content/plugins) using FTP.
3. Login to your Wordpress admin panel and browse to the Plugins section.
4. Activate the WP Pic Tagger plugin.

== Usage ==

= Enabling Tags on an Image =

To tag an image, you need to assign the class "wp-tag-people-abc" or "wp-tag-objects-xyz" to your images through your blog's HTML editor.  'abc' and 'xyz' must be unique strings of text to identify an image.  Simply use the 'Tag People' or 'Tag Objects' buttons found in the editor to easily insert these tags.  

To tag people, click the 'Tag People' button.  This type of tag allows you to insert names of people and their website or blog URLs.  The URL field may be left empty.

If you wish to tag objects, click the 'Tag Objects' button.  This tag allows you to add only textual notes or descriptions.

Some samples:

<img src="images/myfriends01.jpg" alt="My Friends" class="wp-tag-people-friends01" />

If you have more than one image in your post, they must be assigned different classes:

<img src="images/mydog02.jpg" alt="My Dog" class="wp-tag-objects-dog02" />

<img src="images/myfriends03.jpg" alt="My Friends" class="wp-tag-people-friends03" />

Do not assign more than one class to an image, such as this:

<img src="images/myfriends04.jpg" alt="My Friends" class="alignnone size-medium wp-image-777" class="wp-tag-people-friends04" />

Doing so will disable the tagging feature.

= Tagging the Images =

After writing your post, publish it or save it as draft.  View or preview the blog post.  You will notice that each of your images assigned with the required classes are loading tags.  To begin tagging, click the button with the plus (+) sign found below the image.

Click on the image.  Drag and resize the tag box to your desired location and size.  To save the tag, click on the button with the check mark to save it.

To edit or delete a tag, right-click on the tag to make the necessary changes.

== Frequently Asked Questions ==

= Why does it take a lifetime to load the tags? =

As much as I have tried to avoid conflict with other Javascript libraries, this one has issues working together with Scriptalicios and Moo Tools.  When such happens, the plugin may stop working.

Another cause could be that the plugin could not find your wp-config.php file, which is required for it to work.

Malformed <img> tags could also be the culprit.

= Why do the tags appear to be larger than they should? =

Again, this has to do with conflicting Javascript libraries.  It has known issues with many Lightbox plugins, such as Lightbox2 (www.huddletogether.com/projects/lightbox2).  This bug most often appears in Internet Explorer and Opera.

= Why can't I edit or delete tags? =

This is a known issue in Opera, wherein the script could not recognize the right-click mouse action.

= Why can't my friends add their own tags? =

Only users with admin access are allowed to add/edit/delete tags.

= How can I change the appearance of the tag box? =

You are free to play around with the CSS file included in the plugin package. (wp-pictagger/css/style.css)

== License ==

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation.