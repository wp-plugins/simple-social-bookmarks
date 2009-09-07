<?php
/*
Plugin Name: Simple Social Bookmarks
Plugin URI: http://www.artiss.co.uk/simple-social-bookmarks
Description: Creates links to social bookmark sites
Version: 1.2
Author: David Artiss
Author URI: http://www.artiss.co.uk
*/
function simple_social_bookmarks($url,$twitter_text,$style,$add_paras) {

    // Use social_bookmarks_parameters function to get additional
    // parameters

    $icon_folder=social_bookmarks_parameters($add_paras,"iconfolder");
    $service=strtolower(social_bookmarks_parameters($add_paras,"service"));
    $default=strtolower(social_bookmarks_parameters($add_paras,"default"));
    if ($default=="") {$default="on";}

    // Build URL for icons

    $plugin_url=WP_PLUGIN_URL."/simple-social-bookmarks/";
    if ($icon_folder!="") {$plugin_url=$plugin_url.$icon_folder."/";}

    // Check URL - if blank use URL of current post/page

    if ($url=="") {$url=get_permalink($post->ID);}

    // If Twitter text isn't specified then a default Twitter text is used

    if (($twitter_text=="")or(strpos($twitter_text,"%url%")===false)) {$twitter_text="I'm currently reading %url%";}

    // If another URL shortening service is requested, override the
    // standard TinyURL

    $tinyurl=$url;
    if ($service=="bit.ly") {$tinyurl="http://bit.ly/api?url=";}
    if ($service=="is.gd") {$tinyurl="http://is.gd/api.php?longurl=";}
    if ($service=="snipr") {$tinyurl="http://snipr.com/site/snip?r=simple&amp;link=";}
    if ($service=="su.pr") {$tinyurl="http://su.pr/api?url=";}
    if ($service=="tinyurl") {$tinyurl="http://tinyurl.com/api-create.php?url=";}
    if ($service=="tr.im") {$tinyurl="http://api.tr.im/api/trim_simple?url=";}  

    // Fetch the TinyURL of the current post and other blog information

    if ($tinyurl!=$url) {$tinyurl=file_get_contents($tinyurl.$url);}
    $title=urlencode(html_entity_decode(get_the_title($post->ID)));
    $blogurl=get_bloginfo('url');

    // Replace the %url% tag in the Twitter text with the TinyURL
    // Also, replace any occurances of %title% with the blog title

    $twitter_text=str_replace('%url%',$tinyurl,$twitter_text);
    $twitter_text=str_replace('%title%',$title,$twitter_text);
    $echoout="";

    // Now, output the links to the bookmarking sites

    $option=strtolower(social_bookmarks_parameters($add_paras,"delicious"));
    if (($option=="on")or(($default=="on")&&($option!="off"))) {
        $echoout=$echoout."<a href=\"http://del.icio.us/post?url=".$tinyurl."&amp;title=".$title."\" target=\"_blank\"><img src=\"".$plugin_url."delicious.png\" alt=\"Share with Delicious\" title=\"Share with Delicious\" style=\"".$style."\"/></a>\n";
    }
    $option=strtolower(social_bookmarks_parameters($add_paras,"digg"));
    if (($option=="on")or(($default=="on")&&($option!="off"))) {
        $echoout=$echoout."<a href=\"http://digg.com/submit?url=".$tinyurl."&amp;title=".$title."\" target=\"_blank\"><img src=\"".$plugin_url."digg.png\" alt=\"Share with Digg\" title=\"Share with Digg\" style=\"".$style."\"/></a>\n";
    }
    $option=strtolower(social_bookmarks_parameters($add_paras,"facebook"));
    if (($option=="on")or(($default=="on")&&($option!="off"))) {
        $echoout=$echoout."<a href=\"http://www.facebook.com/share.php?u=".$tinyurl."&amp;t=".$title."\" target=\"_blank\"><img src=\"".$plugin_url."facebook.png\" alt=\"Share with Facebook\" title=\"Share with Facebook\" style=\"".$style."\"/></a>\n";
    }
    $option=strtolower(social_bookmarks_parameters($add_paras,"linkedin"));
    if (($option=="on")or(($default=="on")&&($option!="off"))) {
        $echoout=$echoout."<a href=\"http://www.linkedin.com/shareArticle?mini=true&amp;url=".$tinyurl."&amp;title=".$title."&amp;source=".$blogurl."\" target=\"_blank\"><img src=\"".$plugin_url."linkedin.png\" alt=\"Share with LinkedIn\" title=\"Share with LinkedIn\" style=\"".$style."\"/></a>\n";
    }
    $option=strtolower(social_bookmarks_parameters($add_paras,"myspace"));
    if (($option=="on")or(($default=="on")&&($option!="off"))) {
        $echoout=$echoout."<a href=\"http://www.myspace.com/index.cfm?fuseaction=postto&amp;l=1&amp;u=".$tinyurl."&amp;t=".$title."\" target=\"_blank\"><img src=\"".$plugin_url."myspace.png\" alt=\"Share with MySpace\" title=\"Share with MySpace\" style=\"".$style."\"/></a>\n";
    }
    $option=strtolower(social_bookmarks_parameters($add_paras,"reddit"));
    if (($option=="on")or(($default=="on")&&($option!="off"))) {
        $echoout=$echoout."<a href=\"http://reddit.com/submit?url=".$tinyurl."&amp;title=".$title."\" target=\"_blank\"><img src=\"".$plugin_url."reddit.png\" alt=\"Share with reddit\" title=\"Share with reddit\" style=\"".$style."\"/></a>\n";
    }
    $option=strtolower(social_bookmarks_parameters($add_paras,"stumbleupon"));
    if (($option=="on")or(($default=="on")&&($option!="off"))) {
        $echoout=$echoout."<a href=\"http://www.stumbleupon.com/submit?url=".$tinyurl."&amp;title=".$title."\" target=\"_blank\"><img src=\"".$plugin_url."stumbleupon.png\" alt=\"Share with StumbleUpon\" title=\"Share with StumbleUpon\" style=\"".$style."\"/></a>\n";
    }
    $option=strtolower(social_bookmarks_parameters($add_paras,"twitter"));
    if (($option=="on")or(($default=="on")&&($option!="off"))) {
        $echoout=$echoout."<a href=\"http://twitter.com/home?status=".$twitter_text."\" target=\"_blank\"><img src=\"".$plugin_url."twitter.png\" alt=\"Share with Twitter\" title=\"Share with Twitter\" style=\"".$style."\"/></a>\n";
    }
    return $echoout;
}
function social_bookmarks_parameters($input,$para) {

    $start=strpos($input,$para."=");
    $content="";
    if ($start!==false) {
        $start=$start+strlen($para)+1;
        $end=strpos($input,"&",$start);
        if ($end!==false) {$end=$end-1;} else {$end=strlen($input);}
        $content=substr($input,$start,$end-$start+1);
    }
    return $content;
}
?>