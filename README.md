PRELOADER EXTENSION

	Version 1.2
	© 2006 Rob Church

== Overview ==

1. Introduction
2. Requirements
3. Installation
4. Configuration
5. Feedback
6. Change Log

== 1. Introduction ==

The Preloader extension allows the creation of boilerplate text which will
be inserted into the edit form when creating new pages. Different boilerplate
text can be specified for different namespaces.

In addition, a new parser tag, <nopreload> is introduced, which is used to
denote sections which should not be preloaded, ever; useful for instructions
and advice on the boilerplate pages. This tag has no effect during a regular
parse operation.

Another new parser tag, <preloadonly> is introduced, which is used to denote
sections which should not be displayed, but should be preloaded. This is
useful for text or information that should not be displayed with the
boilerplate text, but should be included by default when the boilerplate text
is preloaded.

A new parser function, {{#preloadsubst:...}} is also introduced, which is used
to replace a use of {{subst:}} that shouldn't be performed when saving the
boilerplate text, but should be performed (if possible) when preloading the
text. This is useful, for example, for creating automatic navigation between
weekly newsletters where the page title follows a given, predictable
convention, and the newsletter format is standardized.

== 2. Requirements ==

The Preloader extension requires MediaWiki 1.25.0 or later.

== 3. Installation ==

To install the Preloader extension, download all files from Subversion, and
place them into your extensions directory.

Then edit your LocalSettings.php file and add 'Preloader' to the extension
loading call, as follows, with '...' meaning existing extensions:

	wfLoadExtensions( array( ..., 'Preloader', ... ) );

Installation can be verified through the Special:Version page on your wiki.

== 4. Configuration ==

Configuration of the boilerplate sources is done via the $wgPreloaderSource
configuration variable, which takes the following format:

	$wgPreloaderSource[ <namespace index> ] = PAGE TITLE;

For instance

	$wgPreloaderSource[ NS_MAIN ] = 'Template:Boilerplate';
	$wgPreloaderSource[ NS_HELP ] = 'Template:Boilerplate help';

dictates that the boilerplate text for pages in the main namespace should be
loaded from Template:Boilerplate, while pages in the Help namespace will be
preloaded from Template:Boilerplate_help. Other namespaces have no boilerplate
configured.

== 5. Change Log ==

17/12/2006
Version 1.0
	* Initial release

31/12/2006
Version 1.1
	* Trim preloaded text
	* Fix newlines in <nopreload></nopreload> tags

13/03/2008
Version 1.1.1
	* Add description message for [[Special:Version]]

31/07/2017
Version 1.2
	* Add <preloadonly> tag and {{#preloadsubst:...}} parser functions
	* Fix usages of long-deprecated functions
	* Upgrade to the new extension format
