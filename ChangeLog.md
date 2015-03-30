# Introduction #

This is the list of changes for each release of the plugin.

## Change log ##

### Version 1.6.0 / august 10 2009 ###
  * new: support for custom taxonomies in widgets and builder
  * new: resizing images support for widget and results
  * new: config options to set the panels user access levels
  * new: preventing duplicate votes from integration
  * new: few more filters for t2 rendering
  * new: filters for modifying sql queries used for results
  * new: few more tags for results and widgets templates
  * edit: many improvements for t2 rendering
  * edit: cleanup of widgets, render and templates code
  * fix: missing voters log on the articles panel
  * fix: invalid review rating results from widget sql query
  * fix: panel with multi rating results filtering problems
  * fix: rendering review stars for widget or results
  * fix: option to control comment reply for integration was not working
  * fix: rendering multi rating stars results for widget or results
  * fix: rating added to rss cut during formation of the feed
  * fix: some minor problems with t2 rendering of comments widget
  * fix: builder javascript problems in wordpress 2.8

### Version 1.5.8 / august 4 2009 ###
  * new: russian translation thanks to studio xl
  * new: ukrainian translation thanks to Oleksandr Natalenko
  * new: additional indexes to all plugin's database tables
  * new: force using cache even if it's not auto detected
  * new: added some new functions for getting and rendering data
  * fix: problem with inclusion of some javascript helper functions
  * fix: dashboard widget with latest votes in wpmu
  * fix: db queries use of users table causing wpmu problems
  * fix: other minor incompatibilities with wordpressmu and buddypress

### Version 1.5.7 / july 26 2009 ###
  * new: function to mirror starrating shortcode
  * new: several new actions for saving votes
  * new: options to allow zero votes from integrated ratings
  * new: rank id template tag for widget and shortcode results
  * fix: minor problems with bayesian sorting for widget and results
  * fix: rendering of bayesian rating results
  * fix: thumbs javascript problem with IE browsers
  * fix: caching prefetch of integration results
  * fix: missing parameters in thumbs integration functions
  * fix: some problems with builder panel and invalid generated function
  * fix: invalid multi rating template displayed on settings panel

### Version 1.5.6 / july 23 2009 ###
  * new: rss integration supports thumbs results
  * new: rss integration supports multi ratings results
  * new: post based deleting multi set results
  * new: jquery ui 1.7 styles and datepicker for better wp 2.8 support
  * edit: major overhaul of the trend recalculations
  * edit: tinymce plugin and builder support thumbs
  * edit: results widget supports thumbs ratings datasource
  * edit: top blog widget supports thumbs ratings results
  * edit: graphics generator expanded to support thumbs sets
  * edit: added extra classes to thumbs loaders
  * fix: several problems with trend recalculations for multi results
  * fix: missing german translation for jquery datepicker
  * fix: display some of the data on multi sets panel
  * fix: column sorting on visitors log panel
  * fix: deleting thumbs data from articles panel
  * fix: deleting thumbs data from visitors log
  * fix: deleting thumbs data from users log

### Version 1.5.5 / july 20 2009 ###
  * new: cache support for multi rating sets
  * new: auto insert comments outside post or page, but still in the post loop
  * new: three more square shaped loader animations
  * new: another default thumbs set called classical
  * new: articles and comments thumbs waiting loader animations
  * new: few more filters for data prepared for widget rendering
  * edit: expanded article and comment results objects with thumbs data
  * edit: improved and cleaned styles structure of main css file
  * edit: improved latest votes dashboard widget
  * edit: clean up of all admin css files
  * edit: expanded info files and list of filters and actions added
  * fix: problems with theme functions used for rendering widgets
  * fix: theme function for rendering comments thumbs
  * fix: generating css with no thumbs or stars sets selected

### Version 1.5.4 / july 16 2009 ###
  * new: dashboard widget with latest votes with filter options
  * new: option to disable thumbs up/down ratings
  * new: show thumbs data and log on the comments panel
  * new: show thumbs data and log on the users panel
  * new: prefetch and cache comment integration results
  * edit: few updates to features and functions info files
  * edit: more improvements to caching and prefetch system
  * fix: loading of main css file for wp older than 2.7
  * fix: rendering of thumbs rating comments text
  * fix: adding default values for categories rules and restrictions
  * fix: users log queries to support thumbs ratings
  * fix: problem with article trends charts rendering
  * fix: missing voted class for blocks that are not active
  * fix: invalid cookies check for articles thumbs ratings
  * fix: cookies not saved or checked for thumbs ratings
  * fix: compression problem with zlib compression already active in php.ini
  * fix: prefetch problem with comments rating not auto inserted
  * fix: extra empty lines on the end of some files
  * fix: several more php notices found on blog pages

### Version 1.5.3 / july 13 2009 ###
  * new: first version of the thumbs up/down ratings
  * new: show thumbs data and log on the articles panel
  * new: shortcode for thumbs up/down ratings
  * new: gfx classes now support thumbs sets and sizes
  * new: main css file expanded with thumbs generation code
  * new: preview for thumbs sets and seection for main css file
  * new: german translation thanks to bjoern buerstinghaus
  * edit: expanded wp query support with thumbs score and votes
  * edit: expanded builder and tinymce plugin with thumbs shortcode and function
  * edit: upgrade of plugin now resets css cache timestamp
  * edit: optimized check for multi rating sets to render
  * fix: cache and prefetch not working for theme functions
  * fix: checking for standard rating vote value not to allow negative values
  * fix: check multi ratings active to exclude more sql queries

### Version 1.5.2 / july 7 2009 ###
  * fix: import from post star rating plugin data
  * fix: import from wp post rating plugin data
  * fix: deleting multi rating data from comments integration
  * fix: saving standard rating through comment integration failes in some cases
  * fix: post prefetch array not always initialized
  * fix: comments prefetch with no post comments

### Version 1.5.1 / july 5 2009 ###
  * new: optimized sql queries for getting posts and caching results
  * new: optimized sql queries for getting comments and caching results
  * new: caching of templates to reduce number of queries
  * new: added minimal votes filter for wp query
  * fix: checking for multi sets on non pages or posts
  * fix: checking for logged data even if it's disabled
  * fix: export problem caused by incompatibility with jquery 1.3

### Version 1.5.0 / july 3 2009 ###
  * new: modifing WP Query to allow sorting posts using reviews, votes and ratings
  * new: plugin now uses category based voting rules and limitations
  * new: css and javascipt files are loaded using wp enqueue functions
  * new: css and javascipt files are transported using server side gzip compression
  * new: attempts to log the invalid set request, if log into file is active
  * edit: changed some defaults settings values for fresh installations
  * edit: no longer shows the error message when the set is missing
  * fix: after database reinstall default templates are missing
  * fix: exporting data returns no results
  * fix: saving incomplete integration multi rating
  * fix: invalid url for creating multi rating set
  * fix: saving multi review with one of the integration widgets deactivated

### Version 1.4.8 / june 26 2009 ###
  * new: added extended multi rating stars template
  * new: rating widget and results can include post excerpt
  * edit: some of the templates expanded with few more tags
  * edit: removed some more obsolete code
  * edit: massive html cleanup on all plugins admin panels
  * fix: adding empty values for multi reviews
  * fix: multi rating results sql had a small category problem
  * fix: missing parameters in builder generated functions results
  * fix: confusing branching in multi review editor code
  * fix: missing default values for multi review function
  * fix: initialization of category during full installation failed
  * fix: minor installation problem with WP 2.6
  * fix: missing visual styles for pre WP 2.7 versions
  * fix: missing script type on all plugins admin panels
  * fix: invalid tags on all plugins admin panels

### Version 1.4.7 / june 25 2009 ###
  * fix: loading of main CSS file on the admin pages
  * fix: integration multi set on categories page get deleted
  * fix: multi review on edit page fails when set is not found
  * fix: inserting multi values fails sometimes

### Version 1.4.6 / june 21 2009 ###
  * new: enable or disable caching of the main css file:
  * new: categories panel is now functional:
  * new: template and function to render multi rating average integrated in comment:
  * new: additional ajax data validation for better security:
  * edit: deleting votes from votes log panel:
  * edit: few improvements to some functions and shortcodes:
  * edit: changes to some styles and html markup:
  * edit: updated integration functions:
  * edit: expanded info integration functions file:
  * edit: small changes to ajax rating code:
  * fix: missing alt for static stars image:
  * fix: invalid rendering for wp\_gdsr\_show\_multi\_review function:
  * fix: category based multi set auto insertion:
  * fix: multi review on the page edit panel broken:
  * fix: adding default values into articles table:
  * fix: ajax return data for value not integer:
  * fix: page/post edit panel sidebar widget selections broken:
  * fix: missing alt for images used in preinstalled templates:
  * fix: minor problem with ajax url:
  * fix: search and filtering on the articles panel:
  * fix: builder for multi review:
  * fix: one of the builder tabs wasn't displayed:

### Version 1.4.5 / june 17 2009 ###
  * edit: few changes for better page validation support:
  * edit: small improvements to readme file:
  * edit: expanding categories support, still a lot to do:
  * edit: plugin version checker updated for better WP 2.8 compatibility:
  * fix: inserting templates into databse broken:

### Version 1.4.4 / june 14 2009 ###
  * new: Comment integration expanded with reply support to hide rating:
  * new: JavaScript functions for comment integration status and values:
  * edit: T2 classes and functions updated and isolated:
  * edit: more expansions to info files:

### Version 1.4.3 / june 8 2009 ###
  * edit: small changes to the builder panel:
  * fix: different stars sizes between function call and settings after saving vote:
  * fix: few minor problems with multi ratings auto insert:
  * fix: rating block function doesn't pass the average stars values:
  * fix: small bug with builder multi rating function:

### Version 1.4.2 / june 3 2009 ###
  * fix: wrong class call for update check function:

### Version 1.4.1 / june 2 2009 ###
  * new: builder panel for creating function calls and shortcodes:
  * new: few more integration functions:
  * edit: expanded some of the shortcodes:
  * edit: changed few of the integration functions parameter order:
  * edit: reorganized tinymce plugin code for reuse:
  * edit: cleanup of tinymce plugin html:
  * edit: improved tinymce plugin javascript:
  * fix: few multi review regression bugs on the post edit page:
  * fix: integration problem with standard ratings:
  * fix: few minor css problems in tinymce plugin:
  * fix: some integration functions missing descriptions:
  * fix: minor problem with tinymce plugin javascript:
  * fix: bugs in the integration functions descriptions:

### Version 1.4.0 / may 31 2009 ###
  * new: support for cross-domain ajax calls using jquery jsonp:
  * new: templates map as a part of the info pages:
  * new: integration functions to show ratings from comment areas:
  * new: option to disable check for ie6 browsers:
  * new: displaying expanded info about the available update:
  * edit: improved template editor:
  * edit: more improvements for the info files:
  * edit: few more missing strings for translation:
  * fix: minor layout problem with t2 editor:
  * fix: saving multi rating from comment integration block:
  * fix: small problem with inserting additional templates:
  * fix: small xhtml validation problems:
  * fix: few bugs in the integration functions descriptions:

### Version 1.3.4 / may 22 2009 ###
  * new: integration functions with star set changing options:
  * fix: deleting multi rating sets:
  * fix: missing text color for the multi rating block:
  * fix: minor problems with rendering widgets:
  * fix: rendering list of categories in WP2.8:
  * fix: invalid rendering call for widgets for WP2.8:

### Version 1.3.3 / may 19 2009 ###
  * new: customization of spider bot's filter:
  * new: settings for inclusion of stars sets and sizes in css file:
  * new: stars preview now shows the stars set author info:
  * new: some of the rendering functions expanded with custom style for stars:
  * edit: expanded some of the helper custom functions:
  * edit: more updates to read me and info files:
  * edit: file renaming and reordering:
  * edit: partial code refactoring:
  * edit: removed some more old and unused code:
  * edit: changed default values for some of the settings:
  * fix: conflict with multi review and rating ids:
  * fix: rendering submit button for multi block even if the rating is not allowed:
  * fix: invalid css included on post edit panel:
  * fix: saving new empty comment value:

### Version 1.3.2 / may 17 2009 ###
  * new: rating text template expanded with element displayed after voting:
  * new: revised css model for rendering active rating blocks:
  * new: integrate standard post rating into comments:
  * new: deleting data from integrated ratings if comment is deleted:
  * new: custom function to override read only for standard and multi ratings on current post
  * new: documentation with custom functions:
  * edit: optimized main rating css file:
  * edit: all rating blocks updated to use new css style model:
  * edit: t2 templates list now paged:
  * edit: added option to make standard rating block read only:
  * edit: rewritten integration javascript code:
  * edit: rewritten comment integration code:
  * edit: multi rating function with read\_only parameter:
  * edit: updated read me file to support new html info files:
  * edit: updated stars set format file with version:
  * edit: star rating set 2.0:
  * edit: plain stars set 2.0:
  * edit: few more missing strings for translation:
  * fix: default comments rating block template:
  * fix: invalid javascript for multi review block:
  * fix: small problem with tinymce plugin default values:
  * fix: missing some parts of rendering multi editor block:
  * fix: minor problem with adding data to votes log table:
  * fix: rare problem with preparation of the multi sets rendering:

### Version 1.3.1 / may 10 2009 ###
  * new: added two more stars sizes, 16px and 24px:
  * edit: extensive code cleanup eliminating editor warnings:
  * edit: optimization of rating css file:
  * edit: expanded some of the rendering functions:
  * edit: max number of elements in multi set increased to 20:
  * edit: removed more of the obsolete unused code:
  * fix: rare problem with external css file in firefox:
  * fix: invalid results in the rating widgets:
  * fix: small problem in widget results sql query with all categories selected:
  * fix: multi rating block shows button even if the button is disabled:
  * fix: widget data sorting problem with grouping active:
  * fix: t2 rendering for multi ratings invalid average rating value:
  * fix: multi ratings block doesn't contain template info:
  * fix: multi ratings vote doesn't receive template used for rendering:
  * fix: rare problem with invalid post type in comment rating:
  * fix: calculating remaining time parts:

### Version 1.3.0 / may 7 2009 ###
  * new: improved sorting of data for t2 rendering:
  * new: few more extra templates:
  * new: multi ratings partial support for own restriction rules:
  * new: import t2 templates from alternative location:
  * new: export own t2 templates into csv file:
  * new: cleanup of the debug file:
  * edit: removed use of json\_encode function:
  * edit: expanded static stars rendering with id and type:
  * edit: more settings for rendering multi review blocks:
  * edit: expanded t2 template for multi ratings with new tag elements:
  * edit: small update to gdragon base classes:
  * edit: better optimization of multi rating database queries:
  * edit: improved database tables defaults:
  * edit: many minor rendering tweaks:
  * fix: rare case of invalid sorting of widget data:
  * fix: tinymce plugin javascript missing semicolons:
  * fix: multi review restirctions in the rating widget:
  * fix: comments widget comment text contains html tags:
  * fix: multi rating autoinsert generates warning with no auto insert sets:
  * fix: borders, paddings and margins around images in static multi ratings:
  * fix: adding new template sets default flag wrong:
  * fix: displaying multi review count for sets:
  * fix: saving some of the settings:
  * fix: few more minor t2 bugs:

### Version 1.2.3 / april 30 2009 ###
  * edit: ajax voting comment returns new t2 rendered text:
  * edit: removed some more old code:
  * edit: more t2 templates improvements:
  * edit: many imporvements and changes to custom functions:
  * edit: multi review editor using t2 rendering:
  * edit: small improvements to t2 panel:
  * edit: few more tinymce plugin improvements:
  * edit: old templates panel and settings removed:
  * edit: updated plugin requirements:
  * fix: number of minor bugs and typos:
  * fix: recalculating multi rating data into objects:
  * fix: small bugs with multi rating css and javascript:
  * fix: ajax voting rating text result:

### Version: 1.2.2 / april 26 2009 ###
  * new: extra comments widget template
  * new: updating default templates
  * new: rating results rendering using t2
  * new: t2 templates for reviews
  * edit: updated readme file
  * edit: few improvements to t2 base classes
  * edit: few improvements to tinymce plugin
  * fix: missing files for comments widget
  * fix: small problem with rendering of static stars using divs
  * fix: t2 widget rendering call with invalid default type
  * fix: invalid calls for some of the custom functions
  * fix: loading default template if no templete specified

### Version: 1.2.1 / april 25 2009 ###
  * new: post comments widget
  * new: t2 rendering of rss rating block
  * new: t2 rendering of widgets
  * new: t2 templates for widgets and alternating row class
  * new: t2 default templates inserted into widget
  * new: inserting extra templates for each template type
  * edit: many more widgets changes
  * edit: few more wp28 improvements
  * edit: some more settings panel reorganization
  * edit: removed old rendering functions
  * edit: results sorting improved
  * fix: adding new multi rating sets
  * fix: few small widget rendering problems
  * fix: star rating widget small rendering echo bug
  * fix: removed obsolete code for ie6 png fix
  * fix: invalid widget sort column order
  * fix: small PHP4 incompatibility

### Version: 1.2.0 / april 22 2009 ###
  * new: widgets fully support wordpress 2.8
  * new: t2 rendering of standard rating block
  * new: t2 rendering of comment rating block
  * new: t2 system supports setting of default templates for each template type
  * new: multi ratings auto insertion
  * new: star rating widget support for multi rating data
  * new: star rating widget support for multiple categories
  * new: star rating widget support for subcategories
  * new: star rating widget limit articles voted for in the last number of days
  * new: redesigned plugin front panel
  * new: five new animated loaders
  * new: auto insertion of rating block on top of the article
  * new: wp filter for widget data prepare
  * new: saving last voted timestamp for articles, comments and multi rating results
  * new: referer protection for graphics generator to offsite prevent leeching
  * edit: shortcode and functions expanded for t2 standard rating block
  * edit: widgets code moved to separrate file and class
  * edit: css changes for better wp 2.8 compatibility
  * edit: few more minor chnages for better wp 2.8 compatibility
  * edit: removed obsolete articles and comments options
  * edit: removed options for hiding parts of settings tab
  * edit: trend calculations updated to support multi ratings
  * edit: for wp newer than 2.7 tables don't have width set
  * edit: even more code reorganization and cleanup
  * edit: changed loading of javascript code for widgets panel
  * edit: updated readme and added twitter url
  * edit: removed obsolete css styles
  * edit: number of small all over changes
  * fix: duplicated results in sidebard widget
  * fix: sql query for widget with ranged dates
  * fix: multi rating calculation division by zero
  * fix: full db tables reinstall doesn't insert templates
  * fix: multi rating average breaks for empty rating request
  * fix: multi rating average breaks with no multi sets
  * fix: shortcode form review and rating tabs crossed
  * fix: rendering with t2 template setting missing
  * fix: few minor css problems
  * fix: number of strings missing for translation
  * fix: few small typos

### Version: 1.1.9.1 / april 16 2009 ###
  * new: widget template tags for stars and image
  * new: function to render multi rating average rating
  * edit: more multi rating optimizations
  * edit: star rating widget cleanup
  * edit: widget publish date now has all dates option
  * edit: removed some obsolete functions from main class
  * fix: saving posts with no multi rating sets defined
  * fix: reinstalling multi ratings db tables
  * fix: generating stars for rating results table

### Version: 1.1.9 / april 15 2009 ###
  * new: added average rating columns for multi rating results
  * new: recalculating average multi ratings for posts
  * new: recalculating average multi ratings trends for posts
  * new: tool to recalculate multi ratings
  * new: t2 templates system expanded with co-dependent templates
  * new: t2 templates for rating and comment rating blocks
  * new: t2 template for element word votes
  * new: jquery datepicker polish translation
  * edit: expanded functions for multi review integration
  * edit: improved multi review average calculations
  * edit: improved insertion of default templates into database
  * edit: multi rating ajax call improved
  * edit: database tables expanded for multi ratings
  * edit: few css and javascript layout improvements
  * fix: critical error in saving multi rating votes
  * fix: some problems with database table upgrading
  * fix: some of the custom functions missing elements
  * fix: various small bugs

### Version: 1.1.8 / march 26 2009 ###
  * new: each and all sets can be used for multi review
  * new: setting to embed all the css needed into the page
  * new: allowing both user and visitor votes from same ip
  * new: cleaning of invalid and obsolete multi ratings data
  * new: setting additional styles for ie6 browsers
  * new: auto scan for stars sets on update
  * new: tag definition for templates
  * del: removed non-ajax voting code
  * del: removed ie6 png fix support
  * edit: extanded multi review shortcode with set property
  * edit: cleanup now removes revisions data from database
  * edit: templates t2 system beta version
  * edit: optimization in checking votes
  * edit: updated missing translation strings
  * edit: some of the default settings changed
  * edit: readme file and links
  * edit: partially removed old non ajax rating code
  * fix: invalid replacemnet of the comment and post rating text after voting
  * fix: w3c validation error caused by invalid cdata sequence
  * fix: saving review data for revision as articles
  * fix: multi review rating with no set selected
  * fix: setup panel missing confirmations
  * fix: warning about missing multi rating set
  * fix: several small problems

### Version: 1.1.7 / march 10 2009 ###
  * new: more multi set related custom functions
  * new: default templates inserted into database
  * edit: saving review editor results
  * edit: updated and cleaned up database installation
  * edit: few changes to t2 system
  * edit: updated missing translation strings
  * fix: custom review editor function
  * fix: templates t2 system bugs

### Version: 1.1.6 / march 2 2009 ###
  * new: database table for templates
  * new: aggregated comments for post
  * new: templates t2 system alpha version
  * new: tool for editing rating.css file
  * new: aggregated comments rating settings
  * new: alternative method for rendering static stars
  * new: plain stars set
  * new: cache auto cleanup implemented
  * edit: reorganized folders and files (again)
  * edit: setup panel redesigned
  * edit: expanded gfx generating handler
  * edit: listed requirements in readme file
  * edit: improved tinymce plugin dialog
  * fix: mysql4 varchar limits converted to text columns
  * fix: saving new multi set and review setting
  * fix: display rating data with element that has no title
  * fix: invalid css for comment review stars
  * fix: edit post multi review bug in ie7 browser

### Version: 1.1.5 / february 22 2009 ###
  * new: multi ratings review
  * new: multi ratings review box on edit page
  * new: shortcode for multi review
  * edit: default rating.css file updated
  * edit: various small improvements
  * fix: processing new multi set
  * fix: generating styles for multi sets
  * fix: editing multi sets
  * fix: upgrade of multi ratings database table

### Version: 1.1.4 / february 16 2009 ###
  * new: star rating default set
  * new: polish translation
  * new: linking javascript rating code instead of embeding it
  * new: multi rating parameter read\_only
  * new: loading of extra css file
  * new: multi rating vote without button
  * new: custom multi submit button text
  * new: additional features settings tab
  * edit: more changes to plugin front page
  * edit: updated heading for all admin pages
  * edit: tinymce plugin improvements
  * edit: updated icons used in admin panels
  * edit: updatad powered by button
  * edit: better stars sets optmization
  * fix: insert new record into multi\_data table
  * fix: w3c validation problem with javascript
  * fix: singular/plural votes returned by ajax vote call

### Version: 1.1.3 / february 11 2009 ###
  * edit: updated plugin front page
  * edit: cache notice moved inside the cache tab
  * edit: more strict important css styles
  * edit: multi set name and description limited in length
  * fix: multi rating block rendering when multi rating is disabled
  * fix: check if wp-content is writable
  * fix: settings page problem with multi ratings disabled
  * fix: loader preview backgorund color
  * fix: deleting multi set after adding new one

### Version: 1.1.2 / february 9 2009 ###
  * new: encoding for character set
  * new: multi sets statistics page per post view
  * new: multi set support for custom encoding
  * edit: php multi ratings function expanded with post\_id
  * edit: normalized multi ratings calculations
  * edit: multi rating will not render for feeds
  * edit: small changes to plugin admin front page
  * fix: mkdir warning problem with wp-content not writable
  * fix: error with including iepngfix
  * fix: error with saving new multi set

### Version: 1.1.1 / february 6 2009 ###
  * new: check safe mode before checking cache folders
  * edit: database alter added for multi ratings data table
  * edit: default feed replaced with new feedburner feed
  * fix: create new record in the database for multi ratings
  * fix: saving multi rating text template
  * fix: cache mkdir warning
  * fix: votes count returend after voting

### Version: 1.1.0 / february 5 2009 ###
  * new: post and page multi rating
  * new: function for manula insertion of multi rating code
  * new: integrate post rating into rss feed posts
  * new: cache support and cache cleanup tool
  * new: auto creating of extra folders
  * new: rss integration settings
  * new: rss post rendering template
  * new: generate rating images
  * new: gfx get type methods
  * new: powered by button
  * new: ajax url now made using siteurl option
  * new: integrate powered by button in rss feed posts
  * new: gfx set get path method
  * new: multi ratings table layout
  * edit: improved db install procedure
  * edit: more optimizations to loading of javascript and css rating code
  * edit: ajax vote now returns value always even for duplicate votes
  * edit: improved saving vote functions
  * edit: reorganized templates panel
  * edit: main class optimizations
  * edit: skip rendering loaders if voting is not allowed
  * edit: renaming outside access files
  * edit: renaming some of the css classes
  * edit: small improvements to rating block rendering
  * edit: wp 2.7 plugin menu icon
  * edit: tinymce plugin icon
  * edit: various small improvements
  * fix: stars missing for pages comments rating
  * fix: rendering of loaders for post and comment rating blocks
  * fix: small problem with backgound property of rating stars
  * fix: few more missing translation strings
  * fix: some settings not saving properly
  * fix: generating rating images
  * fix: generating ajax reponse rating for comments
  * fix: small and rare css bug

### Version: 1.0.9 / january 13 2008 ###
  * new: expanded post edit widget
  * new: multi ratings settings tab
  * new: multi ratings database tables
  * new: multi sets weight property for each element
  * new: latest jquery ui
  * new: italian datepicker translation
  * new: generate full rating image methods
  * new: supports both upper and lower case shortcodes
  * new: shortcodes expansion added to tinymce plugin
  * edit: added new official website url
  * edit: updated inline debug rendering
  * edit: rewriten main css file (again) produce 20%-30% smaller file
  * edit: jquery theme for tabs and datepicker
  * edit: small changes to settings tabs
  * edit: removed some obsolete functions
  * edit: few more small changes
  * edit: updated gdragon lib classes
  * edit: reorganized css files
  * edit: some more code cleanup
  * edit: improved tinymce plugin
  * edit: improved translation strings
  * fix: rating loader position
  * fix: admin settings page problem with IE
  * fix: moderation check for saving votes
  * fix: small incompatibility with wordpress 2.5.x
  * fix: hiding comments options when comments rating is disabled

### Version: 1.0.8 / december 29 2008 ###
  * new: parameter minimum votes required for widget and shortcode
  * edit: improved readme file
  * edit: various small changes
  * edit: prepering widget data improved
  * edit: all custom functions now with phpdoc comments
  * fix: invalid initialization of settings after reset or remove settings
  * fix: template panel editing of time restrictions
  * fix: database install invalid folder name

### Version: 1.0.7 / december 24 2008 ###
  * new: rewritten database installation and upgrade
  * new: function for manual rendering of blog rating widget
  * new: database upgrade tool
  * new: default color palette for charts
  * edit: more database calls in debug file
  * edit: small change in rendering blog rating widget
  * edit: updated translation strings
  * edit: improved user votes log
  * edit: debug and install classes improvements
  * fix: problems caused by incorrect database upgrade
  * fix: invalid voting rules voting problem
  * fix: saving rules and rating from post edit page
  * fix: default debug file becauase of open\_basedir restrictions
  * fix: tinymce invalid translation string rendering
  * fix: problem with changing timer and post rating values in post edit


### Version: 1.0.6 / december 14 2008 ###
  * new: christmas style stars
  * new: added italian translation
  * new: added dashboard widget
  * new: chart for dashboard with votes distribution
  * new: import from star rating for review plugin
  * new: plugin custom actions support
  * new: auto insert rating for comments for pages
  * new: option to disable newsfeed update on the front panel
  * new: functions for getting rating objects
  * new: included pchart class for charting support
  * new: wordpress 2.7 navigation icon
  * edit: post edit box wordpress 2.7 support
  * edit: improvements to tools panel
  * edit: debug check vote methods
  * edit: additional code comments
  * edit: small css changes for wordpress 2.7
  * fix: small visual problems
  * fix: blog widget control problem
  * fix: editing comment review style loading

### Version: 1.0.5 / december 7 2008 ###
  * new: rating css files joined into one
  * new: ajax voting nonce security protection
  * new: please wait message customization with preview
  * new: various vote waiting animations
  * new: basic support for qtranslate
  * new: global debug into file support
  * new: filter post and comments voters log by vote value
  * new: filter user vote log by vote value
  * new: saving debug info for articles and comment votes
  * new: setup page confirmations
  * new: debug settings tab
  * new: percent value for blog rating wiget
  * edit: code cleanup of the helper classes
  * edit: updated readme text file
  * edit: added version to inline debug info
  * edit: removed obsolete preview code and settings
  * fix: bottom and hidden rating text layout
  * fix: default widget template and word vote/votes
  * fix: widget shortcode rendering with no valid results
  * fix: problem with anonymous comment authors
  * fix: blog rating widget no votes value
  * fix: rare saving comment vote bug
  * fix: moderation control panel
  * fix: scan dir for php4 not closing the directory resource

### Version: 1.0.4 / november 28 2008 ###
  * new: tools for database cleanup
  * new: debug info included with each rating block
  * new: categories panel new column to view category archive
  * new: articles panel new column to view post or page
  * new: articles panel shows invalid rated post and comments in yellow
  * new: sorting for voters log panel
  * new: masked ip filtering
  * new: range ip filtering
  * edit: rewriten and optimized save votes method
  * edit: improvements to articles and users panels
  * edit: cleanup tools expanded to include cleanup message
  * fix: rare saving votes bug
  * fix: another bug voting cookie check
  * fix: comments votes panel totals column
  * fix: deleting votes from voters log minor bug
  * fix: rating css files validity bug
  * fix: rendering invalid rating value

### Version: 1.0.3 / november 23 2008 ###
  * new: banned ip's db table
  * new: ip's managment panel
  * new: ip filtering settings
  * new: tool for global date based lock
  * new: tool for global rules change
  * new: wordpress 2.7 styles for front page
  * new: main plugin class documented with phpdoc
  * edit: improved speed and reduced mysql load
  * edit: changed bot detection
  * edit: all includes use full path
  * edit: user ip log panel
  * edit: users panel improvements
  * edit: small css changes
  * fix: saving comment review with empty value
  * fix: invalid unique ip count for users
  * fix: voting cookie check
  * fix: few invalid css classes
  * fix: various small bugs

### Version: 1.0.2 / november 12 2008 ###
  * new: comment review rating
  * new: edit comment review rating
  * new: managment for settings for categories
  * new: users and visitors based rating info
  * new: multi rating editor
  * new: more functions to use in the theme
  * edit: more changes to admin panels and settings
  * edit: improved saving and handling settings
  * edit: improved loading of css and js needed
  * edit: improvement of all standalone functions
  * fix: wordpress 2.7 improvements
  * fix: few more typo errors
  * fix: saving votes into log tables
  * fix: invalid vote rule check for comments

### Version: 1.0.1 / november 5 2008 ###
  * new: top ratings blog summary widget
  * new: tools panel
  * new: option to control saving user agent data
  * new: options to hide even more options on settings pages
  * edit: improved voting restrictions with ip and cookie
  * edit: all files encoding changed to utf8
  * edit: all files changed to windows ending
  * fix: invalid default rating for new posts
  * fix: full deletion of votes
  * fix: auto insert comments rating
  * fix: various small bugs

### Version: 1.0.0 / october 31 2008 ###
  * new: pumpkin stars set
  * new: full plugin uninstall
  * new: rendering time testrictions
  * new: front page completed
  * new: voters log panel
  * edit: more code cleanup
  * edit: expanded rating text template
  * edit: translation files updated
  * fix: time restriction recalculations
  * fix: rendering rating header html
  * fix: default shortcode settings
  * fix: page loading renaming regressions
  * fix: few more typo errors
  * fix: default starrating template
  * fix: some small errors