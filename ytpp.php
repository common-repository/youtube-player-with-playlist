<?php
/*
Plugin Name: youtube player with playlist
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: Display youtube player with playlist
Version: 1.0
Author: Muhammad Yasir khan
Author URI: http://zykhan.wordpress.com
License: Under GPL2

         Copyright 2012 zykhan (email : sir_yasir@hotmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
  register_activation_hook(__FILE__, 'ytpp_install');
  function ytpp_install() {
  add_option("pwidth", '488', '', 'yes');
  add_option("pheight", '360', '', 'yes');
  }
  add_action('admin_menu','ytplaylist');
  function ytplaylist(){
  add_options_page('Youtube Player option','Youtube Player Options','manage_options','displaying_playlist_options','play_list_options_page');
  }
  function play_list_options_page(){

    ?>
    <div class="wrap">
    <?php screen_icon() ?>
           <p><h1> YouTube Player with Playlist Plugin</h1></p></br>
           <p><h4> Player Settings</h4></p>

          <form action="options.php" method="post" >
          <?php wp_nonce_field('update-options'); ?>
          <?php wp_nonce_field('submit','save_changes');
          ?>
          <p>Player Width:<input type="text" name="pwidth" id="pwidth" value="<?php echo get_option('pwidth') ?>"></p>
	  <p>Player Height:<input type="text" name="pheight" value="<?php echo get_option('pheight') ?>"></p>
	  <p>
           <input type="hidden" name="action" value="update" />
           <input type="hidden" name="page_options" value="pwidth,pheight" />
          <input name="submit" type="submit" value="Save Changes">
          </p>
          </form>
    </div>
  <?php

           if(wp_verify_nonce($_POST['save_changes'],'submit')){
                      }
  }
        add_action('wp_head', 'pss');
            function pss(){
             wp_register_style('pss_css', plugins_url('style.css',__FILE__ ));
             wp_enqueue_style('pss_css');
             }
        add_shortcode('yt_playlist','playershow');
			function playershow($atts){
          extract(shortcode_atts(array('vdid'=>'','novd'=>''),$atts));
        
         // $po=get_option('phw');
            $pwidth=get_option('pwidth');
            $pheight=get_option('pheight');
$pl='<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/swfobject/2/swfobject.js"></script>
<script type="text/javascript">
  var ytplayer_playlist=[];
  var ytplayer_playitem = 0;
  swfobject.addLoadEvent(ytplayer_render_player);
  swfobject.addLoadEvent(ytplayer_render_playlist);
  function ytplayer_render_player()
  {
  swfobject.embedSWF
    (\'http://www.youtube.com/v/\'+ytplayer_playlist[ytplayer_playitem]+\'&enablejsapi=1&rel=0&fs=1&version=3\',
      \'ytplayer_div1\',
      \''.$pwidth.'\',
      \''.$pheight.'\',
      \'10\',
      null,
      null,
      {allowScriptAccess:\'always\',allowFullScreen:\'true\'},
      {id:\'ytplayer_object\'});
  }
  function ytplayer_render_playlist()
  {
    for(var i=0;i<ytplayer_playlist.length;i++)
    {
      var img=document.createElement("img");
      img.src="http://img.youtube.com/vi/" + ytplayer_playlist[i] + "/default.jpg";
      var a=document.createElement("a");
      a.href="#ytplayer";

      a.onclick=(function(j)
        {
          return function()
          {
            ytplayer_playitem=j;
            ytplayer_playlazy(1000);
          };
        })(i);
      a.appendChild(img);
      document.getElementById("ytplayer_div2").appendChild(a);
    }
  }
  function ytplayer_playlazy(delay)
  {
    if (typeof ytplayer_playlazy.timeoutid != \'undefined\')
    {
      window.clearTimeout(ytplayer_playlazy.timeoutid);
    }
    ytplayer_playlazy.timeoutid=window.setTimeout(ytplayer_play, delay);
  }
  function ytplayer_play()
  {
    var o=document.getElementById(\'ytplayer_object\');
    if (o)
    {
      o.loadVideoById(ytplayer_playlist[ytplayer_playitem]);
    }
  }

  function onYouTubePlayerReady(playerid)
  {
    var o=document.getElementById(\'ytplayer_object\');
    if (o)
    {
      o.addEventListener("onStateChange", "ytplayerOnStateChange");
      o.addEventListener("onError", "ytplayerOnError");
    }
  }

  function ytplayerOnStateChange(state)
  {
    if (state==0)
    {
      ytplayer_playitem += 1;
      ytplayer_playitem %= ytplayer_playlist.length;
      ytplayer_playlazy(5000);
    }
  }

  function ytplayerOnError(error)
  {
    if (error)
    {
      ytplayer_playitem += 1;
      ytplayer_playitem %= ytplayer_playlist.length;
      ytplayer_playlazy(5000);
    }
  }
     var numb=\''.$novd.'\';
     var varr=\''.$vdid.'\';
     var varr1=varr.split(\',\');
     //document.write(varr1);
       for (x=0; x<numb; x++) {
       ytplayer_playlist.push(varr1[x]);
       }
  </script>';
$l1='<a name="ytplayer"></a>';
$l2='<div id="ytplayer_div1">You need Flash player 10+ and JavaScript enabled to view this video.</div>';
$l3='<div id="ytplayer_div2"></div>';
             //return $vdid;
return "<div class=\"main_box\">".$l1.$pl.$l2."<div style=\"float:none;width:488px;overflow-x:scroll;\">".$l3."</div>"."</div>";
}
?>