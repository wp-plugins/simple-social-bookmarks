<?php
/*
Plugin Name: Simple Social Bookmarks
Plugin URI: http://www.artiss.co.uk/simple-social-bookmarks
Description: Creates links to social bookmark sites
Version: 1.8
Author: David Artiss
Author URI: http://www.artiss.co.uk
*/
function simple_social_bookmarks($url,$twitter_text,$style,$add_paras) {
    // Use social_bookmarks_parameters function to get additional parameters
    $icon_folder=social_bookmarks_parameters($add_paras,"iconfolder");
    $service=strtolower(social_bookmarks_parameters($add_paras,"service"));
    $default=strtolower(social_bookmarks_parameters($add_paras,"default"));
    if ($default=="") {$default="basic";}
    $nofollow=strtolower(social_bookmarks_parameters($add_paras,"nofollow"));
    if ($nofollow=="yes") {$nofollow=' rel="nofollow"';} else {$nofollow="";}
    $target=strtolower(social_bookmarks_parameters($add_paras,"target"));
    $cache=strtolower(social_bookmarks_parameters($add_paras,"cache"));
    if ($cache!="") {$params="&cache=".$cache;}
    if ($target=="") {$target="_blank";}
    $apikey=social_bookmarks_parameters($add_paras,"apikey");
    if ($apikey!="") {$params.="&apikey=".$apikey;}
    $login=social_bookmarks_parameters($add_paras,"login");
    if ($login!="") {$params.="&login=".$login;}
    $password=social_bookmarks_parameters($add_paras,"password");
    if ($password!="") {$params.="&password=".$password;}
    // Build URL for icons
    if ($icon_folder=="") {$icon_folder=WP_PLUGIN_URL."/simple-social-bookmarks/";
    } else {$icon_folder=get_bloginfo('template_url')."/".$icon_folder."/";}
    // Check URL - if blank use URL of current post/page
    if ($url=="") {$url=get_permalink($post->ID);}
    // If Twitter text isn't specified then a default Twitter text is used
    if (($twitter_text=="")or(strpos($twitter_text,"%url%")===false)) {$twitter_text="I'm currently reading %url%";}
    // Use the Simple URL Shortener plugin to get a short URL
    if (function_exists('simple_url_shortener')) {
        $tinyurl=simple_url_shortener($url,"service=".$service.$params);
    } else {$tinyurl=$url;}
    // Get the post title and blog URL
    $title=urlencode(html_entity_decode(get_the_title($post->ID),ENT_QUOTES,'UTF-8'));
    $email_title=rawurlencode(html_entity_decode(get_the_title($post->ID),ENT_QUOTES,'UTF-8'));
    $blogurl=get_bloginfo('url');
    // Replace the %url% tag in the Twitter text with the TinyURL and %title% with the post title
    $text=str_replace('%title%',html_entity_decode(get_the_title($post->ID),ENT_QUOTES,'UTF-8'),str_replace('%url%',$tinyurl,$twitter_text));
    $twitter_text=urlencode($text);
    $email_text=rawurlencode($text);
    // Now, output the links to the bookmarking sites
    $echoout="";
    $option=strtolower(social_bookmarks_parameters($add_paras,"addtoany"));
    if (($option=="on")or(($default=="on")&&($option!="off"))) {
        $echoout.="<a href=\"http://www.addtoany.com/share_save?linkurl=".$url."&amp;linkname=".$title."\" target=\"".$target."\"".$nofollow."><img src=\"".$icon_folder."addtoany.png\" alt=\"AddToAny!\" title=\"AddToAny!\" style=\"".$style."\"/></a>\n";
    }
    $option=strtolower(social_bookmarks_parameters($add_paras,"delicious"));
    if (($option=="on")or(($default!="off")&&($option!="off"))) {
        $echoout.="<a href=\"http://del.icio.us/post?url=".$url."&amp;title=".$title."\" target=\"".$target."\"".$nofollow."><img src=\"".$icon_folder."delicious.png\" alt=\"Share with Delicious\" title=\"Share with Delicious\" style=\"".$style."\"/></a>\n";
    }
    $option=strtolower(social_bookmarks_parameters($add_paras,"digg"));
    if (($option=="on")or(($default!="off")&&($option!="off"))) {
        $echoout.="<a href=\"http://digg.com/submit?url=".$url."&amp;title=".$title."\" target=\"".$target."\"".$nofollow."><img src=\"".$icon_folder."digg.png\" alt=\"Share with Digg\" title=\"Share with Digg\" style=\"".$style."\"/></a>\n";
    }
    $option=strtolower(social_bookmarks_parameters($add_paras,"email"));
    if (($option=="on")or(($default=="on")&&($option!="off"))) {
        $echoout.="<a href=\"mailto:?subject=".$email_title."&amp;body=".$email_text."\" target=\"".$target."\"".$nofollow."><img src=\"".$icon_folder."email.png\" alt=\"Email\" title=\"Email\" style=\"".$style."\"/></a>\n";
    }
    $option=strtolower(social_bookmarks_parameters($add_paras,"facebook"));
    if (($option=="on")or(($default!="off")&&($option!="off"))) {
        $echoout.="<a href=\"http://www.facebook.com/share.php?u=".$url."&amp;t=".$title."\" target=\"".$target."\"".$nofollow."><img src=\"".$icon_folder."facebook.png\" alt=\"Share with Facebook\" title=\"Share with Facebook\" style=\"".$style."\"/></a>\n";
    }
    $option=strtolower(social_bookmarks_parameters($add_paras,"google"));
    if (($option=="on")or(($default=="on")&&($option!="off"))) {
        $echoout.="<a href=\"http://www.google.com/bookmarks/mark?op=edit&amp;bkmk=".$url."&amp;title=".$title."\" target=\"".$target."\"".$nofollow."><img src=\"".$icon_folder."google.png\" alt=\"Add to Google Bookmarks\" title=\"Add to Google Bookmarks\" style=\"".$style."\"/></a>\n";
    }
    $option=strtolower(social_bookmarks_parameters($add_paras,"googlebuzz"));
    if (($option=="on")or(($default=="on")&&($option!="off"))) {
        $echoout.="<a href=\"http://www.google.com/buzz/post?url=".$url."\" target=\"".$target."\"".$nofollow."><img src=\"".$icon_folder."gbuzz.png\" alt=\"Post to Google Buzz\" title=\"Post to Google Buzz\" style=\"".$style."\"/></a>\n";
    }
    $option=strtolower(social_bookmarks_parameters($add_paras,"linkedin"));
    if (($option=="on")or(($default!="off")&&($option!="off"))) {
        $echoout.="<a href=\"http://www.linkedin.com/shareArticle?mini=true&amp;url=".$url."&amp;title=".$title."&amp;source=".$blogurl."\" target=\"".$target."\"".$nofollow."><img src=\"".$icon_folder."linkedin.png\" alt=\"Share with LinkedIn\" title=\"Share with LinkedIn\" style=\"".$style."\"/></a>\n";
    }
    $option=strtolower(social_bookmarks_parameters($add_paras,"myspace"));
    if (($option=="on")or(($default!="off")&&($option!="off"))) {
        $echoout.="<a href=\"http://www.myspace.com/index.cfm?fuseaction=postto&amp;l=1&amp;u=".$url."&amp;t=".$title."\" target=\"".$target."\"".$nofollow."><img src=\"".$icon_folder."myspace.png\" alt=\"Share with MySpace\" title=\"Share with MySpace\" style=\"".$style."\"/></a>\n";
    }
    $option=strtolower(social_bookmarks_parameters($add_paras,"ping.fm"));
    if (($option=="on")or(($default=="on")&&($option!="off"))) {
        $echoout.="<a href=\"http://ping.fm/ref/?link=".$url."&amp;title=".$title."\" target=\"".$target."\"".$nofollow."><img src=\"".$icon_folder."pingfm.png\" alt=\"Ping this!\" title=\"Ping this!\" style=\"".$style."\"/></a>\n";
    }
    $option=strtolower(social_bookmarks_parameters($add_paras,"reader"));
    if (($option=="on")or(($default=="on")&&($option!="off"))) {
        $echoout.="<a href=\"http://www.google.com/reader/link?url=".$url."&amp;title=".$title."\" target=\"".$target."\"".$nofollow."><img src=\"".$icon_folder."reader.png\" alt=\"Share on Google Reader\" title=\"Share on Google Reader\" style=\"".$style."\"/></a>\n";
    }
    $option=strtolower(social_bookmarks_parameters($add_paras,"reddit"));
    if (($option=="on")or(($default!="off")&&($option!="off"))) {
        $echoout.="<a href=\"http://reddit.com/submit?url=".$url."&amp;title=".$title."\" target=\"".$target."\"".$nofollow."><img src=\"".$icon_folder."reddit.png\" alt=\"Share with reddit\" title=\"Share with reddit\" style=\"".$style."\"/></a>\n";
    }
    $option=strtolower(social_bookmarks_parameters($add_paras,"stumbleupon"));
    if (($option=="on")or(($default!="off")&&($option!="off"))) {
        $echoout.="<a href=\"http://www.stumbleupon.com/submit?url=".$url."&amp;title=".$title."\" target=\"".$target."\"".$nofollow."><img src=\"".$icon_folder."stumbleupon.png\" alt=\"Share with StumbleUpon\" title=\"Share with StumbleUpon\" style=\"".$style."\"/></a>\n";
    }
    $option=strtolower(social_bookmarks_parameters($add_paras,"technorati"));
    if (($option=="on")or(($default=="on")&&($option!="off"))) {
        $echoout.="<a href=\"http://www.technorati.com/faves?add=".$url."\" target=\"".$target."\"".$nofollow."><img src=\"".$icon_folder."technorati.png\" alt=\"Add as a Technorati Favorite\" title=\"Add as a Technorati Favorite\" style=\"".$style."\"/></a>\n";
    }
    $option=strtolower(social_bookmarks_parameters($add_paras,"twitter"));
    if (($option=="on")or(($default!="off")&&($option!="off"))) {
        $echoout.="<a href=\"http://twitter.com/home?status=".$twitter_text."\" target=\"".$target."\"".$nofollow."><img src=\"".$icon_folder."twitter.png\" alt=\"Share with Twitter\" title=\"Share with Twitter\" style=\"".$style."\"/></a>\n";
    }
    $option=strtolower(social_bookmarks_parameters($add_paras,"yahoo"));
    if (($option=="on")or(($default=="on")&&($option!="off"))) {
        $echoout.="<a href=\"http://bookmarks.yahoo.com/myresults/bookmarklet?u=".$url."&amp;t=".$title."\" target=\"".$target."\"".$nofollow."><img src=\"".$icon_folder."yahoo.png\" alt=\"Save to Yahoo! Bookmarks\" title=\"Save to Yahoo! Bookmarks\" style=\"".$style."\"/></a>\n";
    }
    $option=strtolower(social_bookmarks_parameters($add_paras,"yahoobuzz"));
    if (($option=="on")or(($default=="on")&&($option!="off"))) {
        $echoout.="<a href=\"http://buzz.yahoo.com/buzz?targetUrl=".$url."&amp;headline=".$title."\" target=\"".$target."\"".$nofollow."><img src=\"".$icon_folder."ybuzz.png\" alt=\"Buzz up!\" title=\"Buzz up!\" style=\"".$style."\"/></a>\n";
    }
    return $echoout; 
}
// Function to extract parameters from an input string (1.0)
function social_bookmarks_parameters($input,$para) {
    $start=strpos(strtolower($input),$para."=");
    $content="";
    if ($start!==false) {
        $start=$start+strlen($para)+1;
        $end=strpos(strtolower($input),"&",$start);
        if ($end!==false) {$end=$end-1;} else {$end=strlen($input);}
        $content=substr($input,$start,$end-$start+1);
    }
    return $content;
}
?>