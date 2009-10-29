<?php
/*
Plugin Name: Simple Social Bookmarks
Plugin URI: http://www.artiss.co.uk/simple-social-bookmarks
Description: Creates links to social bookmark sites
Version: 1.3
Author: David Artiss
Author URI: http://www.artiss.co.uk
*/
function simple_social_bookmarks($url,$twitter_text,$style,$add_paras) {

    // Use social_bookmarks_parameters function to get additional parameters
    $icon_folder=social_bookmarks_parameters($add_paras,"iconfolder");
    $service=strtolower(social_bookmarks_parameters($add_paras,"service"));
    $default=strtolower(social_bookmarks_parameters($add_paras,"default"));
    if ($default=="") {$default="on";}
    $nofollow=strtolower(social_bookmarks_parameters($add_paras,"nofollow"));
    if ($nofollow=="yes") {$nofollow=' rel="nofollow"';} else {$nofollow="";}
    $target=strtolower(social_bookmarks_parameters($add_paras,"target"));
    if ($target=="") {$target="_blank";}

    // Build URL for icons
    $plugin_url=WP_PLUGIN_URL."/simple-social-bookmarks/";
    if ($icon_folder=="") {
        $icon_folder=$plugin_url;
    } else {
        $icon_folder=$plugin_url.$icon_folder."/";
    }

    // Check URL - if blank use URL of current post/page
    if ($url=="") {$url=get_permalink($post->ID);}

    // If Twitter text isn't specified then a default Twitter text is used
    if (($twitter_text=="")or(strpos($twitter_text,"%url%")===false)) {$twitter_text="I'm currently reading %url%";}

    // Create cached directory name and filename, based on SHA-1 hash
    $cache_dir=WP_PLUGIN_DIR."/simple-social-bookmarks/cache";
    $cache_filename = sha1($url.$service).".suc";
    @chmod($cache_dir,0777);

    // Attempted to find a cached URL. If exists, use it. Otherwise
    // generate a cache file.
    if (file_exists($cache_dir."/".$cache_filename)===true) {
        $tinyurl = file_get_contents($cache_dir."/".$cache_filename);
    } else {
        // Use the Simple URL Shortener plugin to get a short URL
        if (function_exists('simple_url_shortener')) {
            $tinyurl=simple_url_shortener($url,$service);
        } else {
            $tinyurl=$url;
        }
        if ($tinyurl!=$url) {
            $cache_out = fopen($cache_dir."/".$cache_filename,'w');
            fwrite($cache_out,$tinyurl);
            fclose($cache_out);
        }
    }

    // Get the post title and blog URL
    $title=urlencode(html_entity_decode(get_the_title($post->ID),ENT_QUOTES,'UTF-8'));
    $blogurl=get_bloginfo('url');

    // Replace the %url% tag in the Twitter text with the TinyURL and %title% with the post title
    $twitter_text=urlencode(str_replace('%title%',html_entity_decode(get_the_title($post->ID),ENT_QUOTES,'UTF-8'),str_replace('%url%',$tinyurl,$twitter_text)));

    // Now, output the links to the bookmarking sites
    $echoout="";
    $option=strtolower(social_bookmarks_parameters($add_paras,"addtoany"));
    if ($option=="on") {
        $echoout=$echoout."<a href=\"http://www.addtoany.com/share_save?linkurl=".$url."&amp;linkname=".$title."\" target=\"".$target."\"".$nofollow."><img src=\"".$icon_folder."addtoany.png\" alt=\"AddToAny!\" title=\"AddToAny!\" style=\"".$style."\"/></a>\n";
    }
    $option=strtolower(social_bookmarks_parameters($add_paras,"delicious"));
    if (($option=="on")or(($default=="on")&&($option!="off"))) {
        $echoout=$echoout."<a href=\"http://del.icio.us/post?url=".$url."&amp;title=".$title."\" target=\"".$target."\"".$nofollow."><img src=\"".$icon_folder."delicious.png\" alt=\"Share with Delicious\" title=\"Share with Delicious\" style=\"".$style."\"/></a>\n";
    }
    $option=strtolower(social_bookmarks_parameters($add_paras,"digg"));
    if (($option=="on")or(($default=="on")&&($option!="off"))) {
        $echoout=$echoout."<a href=\"http://digg.com/submit?url=".$url."&amp;title=".$title."\" target=\"".$target."\"".$nofollow."><img src=\"".$icon_folder."digg.png\" alt=\"Share with Digg\" title=\"Share with Digg\" style=\"".$style."\"/></a>\n";
    }
    $option=strtolower(social_bookmarks_parameters($add_paras,"email"));
    if ($option=="on") {
        $echoout=$echoout."<a href=\"mailto:?subject=".$title."&amp;body=".$twitter_text."\" target=\"".$target."\"".$nofollow."><img src=\"".$icon_folder."email.png\" alt=\"Email\" title=\"Email\" style=\"".$style."\"/></a>\n";
    }
    $option=strtolower(social_bookmarks_parameters($add_paras,"facebook"));
    if (($option=="on")or(($default=="on")&&($option!="off"))) {
        $echoout=$echoout."<a href=\"http://www.facebook.com/share.php?u=".$url."&amp;t=".$title."\" target=\"".$target."\"".$nofollow."><img src=\"".$icon_folder."facebook.png\" alt=\"Share with Facebook\" title=\"Share with Facebook\" style=\"".$style."\"/></a>\n";
    }
    $option=strtolower(social_bookmarks_parameters($add_paras,"google"));
    if ($option=="on") {
        $echoout=$echoout."<a href=\"http://www.google.com/bookmarks/mark?op=edit&amp;bkmk=".$url."&amp;title=".$title."\" target=\"".$target."\"".$nofollow."><img src=\"".$icon_folder."google.png\" alt=\"Add to Google Bookmarks\" title=\"Add to Google Bookmarks\" style=\"".$style."\"/></a>\n";
    }
    $option=strtolower(social_bookmarks_parameters($add_paras,"linkedin"));
    if (($option=="on")or(($default=="on")&&($option!="off"))) {
        $echoout=$echoout."<a href=\"http://www.linkedin.com/shareArticle?mini=true&amp;url=".$url."&amp;title=".$title."&amp;source=".$blogurl."\" target=\"".$target."\"".$nofollow."><img src=\"".$icon_folder."linkedin.png\" alt=\"Share with LinkedIn\" title=\"Share with LinkedIn\" style=\"".$style."\"/></a>\n";
    }
    $option=strtolower(social_bookmarks_parameters($add_paras,"myspace"));
    if (($option=="on")or(($default=="on")&&($option!="off"))) {
        $echoout=$echoout."<a href=\"http://www.myspace.com/index.cfm?fuseaction=postto&amp;l=1&amp;u=".$url."&amp;t=".$title."\" target=\"".$target."\"".$nofollow."><img src=\"".$icon_folder."myspace.png\" alt=\"Share with MySpace\" title=\"Share with MySpace\" style=\"".$style."\"/></a>\n";
    }
    $option=strtolower(social_bookmarks_parameters($add_paras,"ping.fm"));
    if ($option=="on") {
        $echoout=$echoout."<a href=\"http://ping.fm/ref/?link=".$url."&amp;title=".$title."\" target=\"".$target."\"".$nofollow."><img src=\"".$icon_folder."pingfm.png\" alt=\"Ping this!\" title=\"Ping this!\" style=\"".$style."\"/></a>\n";
    }    
    $option=strtolower(social_bookmarks_parameters($add_paras,"reader"));
    if ($option=="on") {
        $echoout=$echoout."<a href=\"http://www.google.com/reader/link?url=".$url."&amp;title=".$title."\" target=\"".$target."\"".$nofollow."><img src=\"".$icon_folder."reader.png\" alt=\"Share on Google Reader\" title=\"Share on Google Reader\" style=\"".$style."\"/></a>\n";
    }
    $option=strtolower(social_bookmarks_parameters($add_paras,"reddit"));
    if (($option=="on")or(($default=="on")&&($option!="off"))) {
        $echoout=$echoout."<a href=\"http://reddit.com/submit?url=".$url."&amp;title=".$title."\" target=\"".$target."\"".$nofollow."><img src=\"".$icon_folder."reddit.png\" alt=\"Share with reddit\" title=\"Share with reddit\" style=\"".$style."\"/></a>\n";
    }
    $option=strtolower(social_bookmarks_parameters($add_paras,"stumbleupon"));
    if (($option=="on")or(($default=="on")&&($option!="off"))) {
        $echoout=$echoout."<a href=\"http://www.stumbleupon.com/submit?url=".$url."&amp;title=".$title."\" target=\"".$target."\"".$nofollow."><img src=\"".$icon_folder."stumbleupon.png\" alt=\"Share with StumbleUpon\" title=\"Share with StumbleUpon\" style=\"".$style."\"/></a>\n";
    }
    $option=strtolower(social_bookmarks_parameters($add_paras,"twitter"));
    if (($option=="on")or(($default=="on")&&($option!="off"))) {
        $echoout=$echoout."<a href=\"http://twitter.com/home?status=".$twitter_text."\" target=\"".$target."\"".$nofollow."><img src=\"".$icon_folder."twitter.png\" alt=\"Share with Twitter\" title=\"Share with Twitter\" style=\"".$style."\"/></a>\n";
    }
    return $echoout;
}
// Function to extract parameters from an input string
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