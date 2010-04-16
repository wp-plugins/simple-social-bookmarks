<?php
/*
Plugin Name: Simple Social Bookmarks
Plugin URI: http://www.artiss.co.uk/simple-social-bookmarks
Description: Display links to social bookmark sites
Version: 2.0
Author: David Artiss
Author URI: http://www.artiss.co.uk
*/
function simple_social_bookmarks($url,$twitter_text,$style,$add_paras) {
    // Get parameters
    $icon_folder=social_bookmarks_parameters($add_paras,"iconfolder");
    $service=strtolower(social_bookmarks_parameters($add_paras,"service"));
    $default=strtolower(social_bookmarks_parameters($add_paras,"default"));
    $nofollow=strtolower(social_bookmarks_parameters($add_paras,"nofollow"));
    $target=strtolower(social_bookmarks_parameters($add_paras,"target"));
    // Set default values
    if ($default=="") {$default="basic";}
    if ($nofollow=="yes") {$nofollow=' rel="nofollow"';} else {$nofollow="";}
    if ($target=="") {$target="_blank";}
    if ($icon_folder=="") {
        $icon_folder=WP_PLUGIN_URL."/simple-social-bookmarks/default/";
    } else {
        $icon_folder=get_bloginfo('template_url')."/".$icon_folder."/";
    }
    if ($url=="") {$url=get_permalink($post->ID);}
    if (($twitter_text=="")or(strpos($twitter_text,"%url%")===false)) {$twitter_text="I'm currently reading %url%";}
    // Use the Simple URL Shortener plugin to get a short URL
    if (function_exists('simple_url_shortener')) {
        // Get Parameters specific to URL shortener
        $cache=strtolower(social_bookmarks_parameters($add_paras,"cache"));
        $apikey=social_bookmarks_parameters($add_paras,"apikey");
        $login=social_bookmarks_parameters($add_paras,"login");
        $password=social_bookmarks_parameters($add_paras,"password");
        // Built a parameter list to pass
        if ($cache!="") {$params="&cache=".$cache;}
        if ($apikey!="") {$params.="&apikey=".$apikey;}
        if ($login!="") {$params.="&login=".$login;}
        if ($password!="") {$params.="&password=".$password;}
        // Call URL shortener function with supplied parameters
        $tinyurl=simple_url_shortener($url,"service=".$service.$params);
    } else {
        $tinyurl=$url;
    }
    // Get the post title and blog URL and modify the Twitter text
    $title=urlencode(html_entity_decode(get_the_title($post->ID),ENT_QUOTES,'UTF-8'));
    $email_title=rawurlencode(html_entity_decode(get_the_title($post->ID),ENT_QUOTES,'UTF-8'));
    $text=str_replace('%title%',html_entity_decode(get_the_title($post->ID),ENT_QUOTES,'UTF-8'),str_replace('%url%',$tinyurl,$twitter_text));
    $twitter_text=urlencode($text);
    $email_text=rawurlencode($text);
    // Now, output the links to the bookmarking sites
    $social_sites=setup_social_sites();
    $echoout="";
    foreach ($social_sites as $social_data) {
        $option=strtolower(social_bookmarks_parameters($add_paras,$social_data[0]));
        $switch=false;
        if ((($social_data[4]=="")&&($default=="on"))or(($social_data[4]=="Y")&&($default!="off"))) {$switch=true;}
        if (($option=="on")or(($switch===true)&&($option!="off"))) {
            $echoout.="<a href=\"".$social_data[3]."\" target=\"".$target."\"".$nofollow."><img src=\"".$icon_folder.$social_data[2].".png\" alt=\"".$social_data[1]."\" title=\"".$social_data[1]."\" style=\"".$style."\"/></a>\n";
        }
    }
    $echoout=str_replace('[url]',$url,$echoout);
    $echoout=str_replace('[title]',$title,$echoout);
    $echoout=str_replace('[plugin]',urlencode("Simple Social Bookmarks"),$echoout);
    $echoout=str_replace('[source]',get_bloginfo('url'),$echoout);
    $echoout=str_replace('[twitter]',$twitter_text,$echoout);
    $echoout=str_replace('[email_title]',$email_title,$echoout);
    $echoout=str_replace('[email_text]',$email_text,$echoout);
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
// Function to define array containing bookmarking service details
function setup_social_sites() {
    $social_sites=array(array('addthis','Bookmark and Share','addthis','http://addthis.com/bookmark.php?url=[url]&amp;title=[title]',''),
                        array('addtoany','AddToAny!','addtoany','http://www.addtoany.com/share_save?linkurl=[url]&amp;linkname=[title]',''),
                        array('bebo','Share this on Bebo','bebo','http://www.bebo.com/c/share?Url=[url]&amp;Title=[title]',''),
                        array('bitacoras','Submit this to Bitacoras','bitacoras','http://bitacoras.com/anotaciones/[url]',''),
                        array('blinklist','Share this on Blinklist','blinklist','http://www.blinklist.com/index.php?Action=Blink/addblink.php&amp;Url=[url]&amp;Title=[title]',''),
                        array('blogger','Blog this on Blogger','blogger','http://www.blogger.com/blog_this.pyra?t&amp;u=[url]&amp;n=[title]&amp;pli=1',''),
                        array('bobrdobr','Share this on BobrDobr','bobrdobr','http://bobrdobr.ru/addext.html?url=[url]&amp;title=[title]',''),
                        array('current','Post this to Current','current','http://current.com/clipper.htm?url=[url]&amp;title=[title]',''),
                        array('delicious','Share this on del.icio.us','delicious','http://del.icio.us/post?url=[url]&amp;title=[title]','Y'),
                        array('digg','Digg this!','digg','http://digg.com/submit?url=[url]&amp;title=[title]','Y'),
                        array('email','Email this','email','mailto:?subject=[email_title]&amp;body=[email_text]','Y'),
                        array('evernote','Clip this to Evernote','evernote','http://www.evernote.com/clip.action?url=[url]&amp;title=[title]',''),
                        array('facebook','Share this on Facebook','facebook','http://www.facebook.com/sharer.php?u=[url]','Y'),
                        array('fark','FarkIt!','fark','http://cgi.fark.com/cgi/fark/farkit.pl?u=[url]&amp;h=[title]',''),
                        array('friendfeed','Share this on FriendFeed','ffeed','http://www.friendfeed.com/share?title=[title]&amp;link=[url]',''),
                        array('google','Add this to Google Bookmarks','google','http://www.google.com/bookmarks/mark?op=add&amp;bkmk=[url]&amp;title=[title]',''),
                        array('googlebuzz','Post to Google Buzz','gbuzz','http://www.google.com/buzz/post?url=[url]',''),
                        array('hatena','Bookmarks this on Hatena Bookmarks','hatena','http://b.hatena.ne.jp/add?mode=confirm&amp;url=[url]&amp;title=[title]',''),
                        array('identica','Post this to Identica','identica','http://identi.ca//index.php?action=newnotice&amp;status_textarea=[twitter text]',''),
                        array('jumptags','Submit this link to JumpTags','jumptags','http://www.jumptags.com/add/?url=[url]&amp;title=[title]',''),
                        array('linkedin','Share this on LinkedIn','linkedin','http://www.linkedin.com/shareArticle?mini=true&amp;url=[url]&amp;title=[title]&amp;source=[source]','Y'),
                        array('mixx','Share this on Mixx','mixx','http://www.mixx.com/submit?page_url=[url]&amp;title=[title]',''),
                        array('mrwong','Add this to Mister Wong','mrwong','http://www.mister-wong.com/addurl/?bm_url=[url]&amp;bm_description=[title]&amp;plugin=[plugin]',''),
                        array('myplace','Add this to MyPlace','myplace','http://moemesto.ru/post.php?url=[url]&amp;title=[title]',''),
                        array('myspace','Post this to MySpace','myspace','http://www.myspace.com/index.cfm?fuseaction=postto&amp;l=1&amp;u=[url]&amp;t=[title]',''),
                        array('netvibes','Submit this to Netvibes','netvibes','http://www.netvibes.com/share?title=[title]&amp;url=[url]',''),
                        array('newsvine','Seed this on Newsvine','newsvine','http://www.newsvine.com/_tools/seed&amp;save?u=[url]&amp;h=[title]',''),
                        array('ning','Add this to Ning','ning','http://bookmarks.ning.com/addItem.php?url=[url]&amp;T=[title]',''),
                        array('orkut','Promote this on Orkut','orkut','http://promote.orkut.com/preview?nt=orkut.com&amp;tt=[title]&amp;du=[url]',''),
                        array('pdf','Save Page as PDF','pdf','http://savepageaspdf.pdfonline.com/pdfonline/pdfonline.asp?cURL=[url]',''),
                        array('ping.fm','Ping this on Ping.fm','pingfm','http://ping.fm/ref/?link=[url]&amp;title=[title]',''),
                        array('plurk','Share this on Plurk','plurk','http://www.plurk.com/m?content=[title]+-+[url]&amp;qualifier=shares',''),
                        array('posterous','Post this to Posterous','posterous','http://posterous.com/share?linkto=[url]&amp;title=[title]',''),
                        array('print','Send this page to Print Friendly','print','http://www.printfriendly.com/print?url=[url]','Y'),
                        array('propeller','Submit this story to Propeller','propeller','http://www.propeller.com/submit/?url=[url]',''),
                        array('reader','Share on Google Reader','reader','http://www.google.com/reader/link?url=[url]&amp;title=[title]',''),
                        array('readitlater','Read It Later','later','https://readitlaterlist.com/edit?url=[url]&amp;title=[title]',''),
                        array('reddit','Share this on Reddit','reddit','http://reddit.com/submit?url=[url]&amp;title=[title]','Y'),
                        array('slashdot','Submit this to SlashDot','slashdot','http://slashdot.org/bookmark.pl?url=[url]&amp;title=[title]',''),
                        array('squidoo','Add to a lense on Squidoo','squidoo','http://www.squidoo.com/lensmaster/bookmark?[url]',''),
                        array('stumbleupon','Share it on StumbleUpon','stumbleupon','http://www.stumbleupon.com/submit?url=[url]&amp;title=[title]','Y'),
                        array('technorati','Share this on Technorati','technorati','http://technorati.com/faves?add=[url]&amp;title=[title]',''),
                        array('tumblr','Share this on Tumblr','tumblr','http://www.tumblr.com/share?v=3&amp;u=[url]&amp;t=[title]',''),
                        array('twitter','Tweet This!','twitter','http://twitter.com/home?status=[twitter]','Y'),
                        array('yahoo','Save to Yahoo! Bookmarks','yahoo','http://bookmarks.yahoo.com/myresults/bookmarklet?u=[url]&amp;t=[title]',''),
                        array('yahoobuzz','Buzz up!','ybuzz','http://buzz.yahoo.com/buzz?targetUrl=[url]&amp;headline=[title]',''));
    return $social_sites;
}
?>