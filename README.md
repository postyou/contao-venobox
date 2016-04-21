contao-venobox
=====================
Venobox Gallerie Erweiterung für Contao

usage:

"require": {

        "postyou/venobox": "dev-master"
},


"repositories": [

        {
        
            "type": "vcs",
            "url": "https://github.com/postyou/contao-venobox"
        },
        {
        
            "type": "vcs",
            "url": "https://github.com/postyou/VenoBox"
        }
        
],
   
Usage with page2ajax: (display contentElements, Articles or Pages in a VenoBox but not as an IFrame)

In the Venobox settings choose page2ajax (make shure you have it installed)
In the URL input you can use the following insertTags:
 - {{article_url::1}} (to display an Article in the venoBox)
 - {{link_url::2}} (to display a page in the venoBox)
 
To manually Link to an Element do:

1. choose ajax in Venobox settings
2. as URL take  /system/modules/page2ajax/assets/ajax.php
3. Add parameters as explained in the [readme](https://github.com/postyou/contao-page2ajax/blob/master/README.md) of page2ajax

Url-field for example: /system/modules/page2ajax/assets/ajax.php?g=1&id=64&action=art (to load article with id 64)

AutoOpen Venobox:

add Parameter to URL `venoboxOpen=uniqueIDofTheBox`
(get the ID of the specific box form the backend Page)
