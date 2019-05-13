# jxAttrEdit

*Module for backend / admin of OXID eShop for editing attributes of products.*

This module shows all available attributes on one tab page and offers all used values of an attribute as proposal.  

Changes field values are marked with a different color for visualizing which of the attributes are changed.

If the product has variants you can switch to another variant or to the parent without choosing the main tab.

Tested with OXID versions 4.7 - 4.10 and 6.x 

This fork allows multiselection of attribut values which are translated to a comma-separated list in the oxid 6 version.

## Install

```composer require zunderweb/oxid-jxattredit```


### Screenshot ###
![](https://github.com/leofonic/jxAttrEdit/raw/multiselect/editattributes.jpg)


### History ###

* **Release 0.3**
  * Support for multi-language shops added  

* **Release 0.4**
  * Compatibility for 4.7-4.9 implemented
  * Configurable number of columns

* **Release 0.5**
  * Translation changed to UTF-8
  * Language switch added
  * Number of colums as select box
  * Highlighting of basket attributes

* **Release 1.0.0**
  * Migration to OXID 6, multiselect added