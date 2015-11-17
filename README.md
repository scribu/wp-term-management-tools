# Term Management Tools 
Contributors: scribu  
Tags: admin, category, tag, term, taxonomy, hierarchy, organize, manage  
Requires at least: 3.2  
Tested up to: 4.3  
Stable tag: 1.1.4  
License: GPLv2 or later  
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows you to merge terms, set term parents in bulk, and swap term taxonomies.

## Description 

If you need to reorganize your tags and categories, this plugin will make it easier for you. It adds two new options to the Bulk Actions dropdown on term management pages:

* Merge - combine two or more terms into one
* Set parent - set the parent for one or more terms (for hierarchical taxonomies)
* Change taxonomy - convert terms from one taxonomy to another

It works with tags, categories and [custom taxonomies](http://codex.wordpress.org/Custom_Taxonomies).

### Usage 

1. Go to `WP-Admin -> Posts -> Categories`.
2. Find the Bulk Actions dropdown.
3. Reorganize away.

### No support

I, scribu, will not be offering support for this plugin anymore (either free or paid).

Fixes are submitted by other contributors, on [Github](https://github.com/scribu/wp-term-management-tools).

## Installation 

You can either install it automatically from the WordPress admin, or do it manually:

1. Unzip the "term-management-tools" archive and put the folder into your plugins folder (/wp-content/plugins/).
1. Activate the plugin from the Plugins menu.

## Screenshots 

1. Merge
2. Set parent

## Changelog 

### 1.1.4
* improved taxonomy cache cleaning. props Mustafa Uysal
* added 'term_management_tools_term_changed_taxonomy' action hook. props Daniel Bachhuber
* fixed redirection for taxonomies attached to custom post types. props Thomas Bartels
* added Japanese translation. props mt8

### 1.1.3
* preserve term hierarchy when switching taxonomies. props Chris Caller

### 1.1.2 
* added 'term_management_tools_term_merged' action hook. props Amit Gupta

### 1.1.1 
* fixed error notices
* added Persian translation

### 1.1 
* added 'Change taxonomy' action

### 1.0 
* initial release
* [more info](http://scribu.net/wordpress/term-management-tools/tmt-1-0.html)

