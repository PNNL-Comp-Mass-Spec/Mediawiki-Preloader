PRELOADER EXTENSION

See also https://www.mediawiki.org/wiki/Extension:Preloader

## Overview

1. Introduction
2. Requirements
3. Installation
4. Configuration
5. Example
6. Change Log

### 1. Introduction

The Preloader extension allows the creation of boilerplate text which will
be inserted into the edit form when creating new pages. Different boilerplate
text can be specified for different namespaces.

In addition, a new parser tag, `<nopreload>` is introduced, which is used to
denote sections which should not be preloaded, ever; useful for instructions
and advice on the boilerplate pages. This tag has no effect during a regular
parse operation.

Another new parser tag, `<preloadonly>` is introduced, which is used to denote
sections which should not be displayed, but should be preloaded. This is
useful for text or information that should not be displayed with the
boilerplate text, but should be included by default when the boilerplate text
is preloaded.

A new parser function, `{{#preloadsubst:...}}` is also introduced, which is used
to replace a use of `{{subst:}}` that shouldn't be performed when saving the
boilerplate text, but should be performed (if possible) when preloading the
text. This is useful, for example, for creating automatic navigation between
weekly newsletters where the page title follows a given, predictable
convention, and the newsletter format is standardized.

### 2. Requirements

The Preloader extension requires MediaWiki 1.32.0 or later.

### 3. Installation

To install the Preloader extension, download all files from GitHub ([Download here](https://github.com/PNNL-Comp-Mass-Spec/Mediawiki-Preloader/releases/latest)), and
place them into your extensions directory.

Then edit your LocalSettings.php file and add 'Preloader' to the extension
loading call, as follows, with '...' meaning existing extensions:
```
wfLoadExtensions( array( ..., 'Preloader', ... ) );
```
Installation can be verified through the Special:Version page on your wiki.

### 4. Configuration

Configuration of the boilerplate sources is done via the `$wgPreloaderSource`
configuration variable, which takes the following format:
```
$wgPreloaderSource[ <namespace index> ] = PAGE TITLE;
```
For instance
```
$wgPreloaderSource[ NS_MAIN ] = 'Template:Boilerplate';
$wgPreloaderSource[ NS_HELP ] = 'Template:Boilerplate help';
```
dictates that the boilerplate text for pages in the main namespace should be
loaded from Template:Boilerplate, while pages in the Help namespace will be
preloaded from Template:Boilerplate_help. Other namespaces have no boilerplate
configured.

### 5. Example

Contents of file `Template:WeeklyNews`
```
<nopreload>
<!------------------------------------------
-- Note that this template is used to pre-populate new pages whose name
--  starts with WeeklyNews
--
-- For example, if you create a page named WeeklyNews:200
--  then the text in this template will appear on that page
--
-- This is accomplished via the Preloader extension (http://www.mediawiki.org/wiki/Extension:Preloader)
--  and is configured in file LocalSettings.php
-- To configure things, we first define a namespace with:
--   define("NS_WEEKLYNEWS", 104);
--   $wgExtraNamespaces[104] = "Weekly_News";
-- Next, define the preloader source:
--   $wgPreloaderSource[ NS_WEEKLYNEWS ] = 'Template:WeeklyNews';
--
-- Added features:
--   <preloadonly> tag, for text that should be shown on templated pages, but not displayed when viewing the template
--   {{#preloadsubst:...}} function, for a version of {{subst:...}} that is calculated when the text is preloaded
--------------------------------------------->
</nopreload>

<!-----------------------------------------
-- To edit the template, go to https://myserver.mydomain.com/wiki/Template:WeeklyNews
--------------------------------------------->

<font style="font-size: 97% color="DarkSlateGray">''Week of {{#preloadsubst:#time: F j, Y | @{{#preloadsubst:#expr: ({{#preloadsubst:SUBPAGENAME}} - 158) * 604800 + {{#preloadsubst:#time: U | January 2 2012}} }} }}''</font><br>

== <span style="color: #e36c0a">Group Meetings </span> ==

=== <u>This Week</u> ===
[[Image:MyLogo.jpg|thumb|right|150px]]
<nopreload>
<!-- Logic for Tuesday group meetings that only occurred on the last Tuesday of the month
* '''Tuesday, {{#preloadsubst:#time: F j | @{{#preloadsubst:#expr: ({{#preloadsubst:SUBPAGENAME}} - 158) * 604800 + {{#preloadsubst:#time: U | January 2 2012}} + 86400 }} }} @ 2 pm - {{#preloadsubst:#ifeq: {{#preloadsubst:#time: F | @{{#preloadsubst:#expr: ({{#preloadsubst:SUBPAGENAME}} - 158) * 604800 + {{#preloadsubst:#time: U | January 2 2012}} + 86400 }} }} | {{#preloadsubst:#time: F | @{{#preloadsubst:#expr: ({{#preloadsubst:SUBPAGENAME}} - 158) * 604800 + {{#preloadsubst:#time: U | January 2 2012}} + 86400 * 8 }} }} | No Group Meeting | TBD }}'''
-->
<!-- Logic for weekly Tuesday group meeting
* '''Tuesday, {{#preloadsubst:#time: F j | @{{#preloadsubst:#expr: ({{#preloadsubst:SUBPAGENAME}} - 158) * 604800 + {{#preloadsubst:#time: U | January 2 2012}} + 86400 }} }} @ 2 pm'''
-->
</nopreload>
<!-- Logic for weekly Thursday group meeting -->
* '''Thursday, {{#preloadsubst:#time: F j | @{{#preloadsubst:#expr: ({{#preloadsubst:SUBPAGENAME}} - 158) * 604800 + {{#preloadsubst:#time: U | January 2 2012}} + 86400 * 3 }} }} @ 11 am'''
* Team A
** TBD
* Team B
** TBD

<br>

=== <u>Next Week</u>  ===
<nopreload>
<!-- Logic for weekly Tuesday group meeting
* '''Tuesday, {{#preloadsubst:#time: F j | @{{#preloadsubst:#expr: ({{#preloadsubst:SUBPAGENAME}} - 158) * 604800 + {{#preloadsubst:#time: U | January 2 2012}} + 86400 * 8 }} }} @ 2 pm'''
** Team A: TBD
** Team B: TBD
-->
</nopreload>
* '''Thursday, {{#preloadsubst:#time: F j | @{{#preloadsubst:#expr: ({{#preloadsubst:SUBPAGENAME}} - 158) * 604800 + {{#preloadsubst:#time: U | January 2 2012}} + 86400 * 10 }} }} @ 11 am'''
** Team A: TBD
** Team B: TBD

<br>

<noinclude>
<!-- "noinclude" is used to prevent the display of this navigation on the Main Page. -->
{| border="0" width="100%" style="background: WhiteSmoke;"
|-
| [[Weekly News:{{#preloadsubst:#expr: {{#preloadsubst:SUBPAGENAME}} - 1}}|Previous Week - BS&amp;MS News]]
| style="text-align: right" | [[Weekly News:{{#preloadsubst:#expr: {{#preloadsubst:SUBPAGENAME}} + 1}}|Following Week - BS&amp;MS News]]
|}
</noinclude>

<nopreload>
<noinclude>[[Category:Templates|WeeklyNews]]</noinclude>
</nopreload>
```

### 6. Change Log

##### Version 1.2.3, 2023-01-09, Matthew Monroe
* Fix namespace for an included class

##### Version 1.2.2, 2022-12-20, Matthew Monroe
* Fix usage of deprecated function

##### Version 1.2.1, 2021-06-16, Matthew Monroe
* Update for MediaWiki 1.36
  * Replace methods deprecated in 1.32 with MediaWikiServices calls

##### Version 1.2, 2017-07-31, Bryson Gibbons
* Add `<preloadonly>` tag and `{{#preloadsubst:...}}` parser functions
* Fix usages of long-deprecated functions
* Upgrade to the new extension format

##### Version 1.1.1, 2008-03-13, Rob Church
* Add description message for `[[Special:Version]]`

##### Version 1.1, 2006-12-31, Rob Church
* Trim preloaded text
* Fix newlines in `<nopreload></nopreload>` tags

##### Version 1.0, 2006-12-17, Rob Church
* Initial release
