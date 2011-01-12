<?php
/*
Plugin Name: NF Livecounter Widget
Plugin URI: http://wordpress.org/extend/plugins/nf-livecounter/
Description: En livecounter widget til din Wordpress blog.
Author: Peter Piilgaard
Version: 1.0.3
Author URI: http://net-freak.dk/
*/

/* TEMPORARY
if ( !is_admin() ) { // instruction to only load if it is not the admin area
   // register your script location, dependencies and version
   wp_register_script('custom_script',
       get_bloginfo('template_directory') . '/js/custom_script.js',
       array('name_of_script_dependencies'),
       '1.0' );
   // enqueue the script
   wp_enqueue_script('custom_script');
}
*/


//Tilføjer Menu til Wordpress admin side
include 'nflc_menu.php';
function nflcWidget()
{
	$option = get_option("widget_nflc");
	$livecounter_timeout = stream_context_create(array(
   	'http' => array(
       	'timeout' => 1
       	)
   	)
); 
$livecoutner_data = @file_get_contents('http://www.livecounter.dk/api/api.php?id='.$option['LcID'].'', 0, $livecounter_timeout);
$livecoutner_data = explode(',', $livecoutner_data);

$online = utf8_encode('Online: <b>'.$livecoutner_data['0'].'</b><br />');
$visits_today = utf8_encode('Besøg i dag: <b>'.$livecoutner_data['1'].'</b><br />');
$pageviews_today = utf8_encode('Sidevisninger i dag: <b>'.$livecoutner_data['2'].'</b><br />');
$visits_week = utf8_encode('Besøg i denne uge: <b>'.$livecoutner_data['3'].'</b><br />');
$pageviews_week = utf8_encode('Sidevisninger i denne uge: <b>'.$livecoutner_data['4'].'</b><br />');
$visits_month = utf8_encode('Besøg i denne måned: <b>'.$livecoutner_data['5'].'</b><br />');
$pageviews_month = utf8_encode('Sidevisninger i denne måned: <b>'.$livecoutner_data['6'].'</b><br />');
$visits_alltime = utf8_encode('Besøg altid: <b>'.$livecoutner_data['7'].'</b><br />');
$pageviews_alltime = utf8_encode('Sidevisninger altid: <b>'.$livecoutner_data['8'].'</b><br />');
if($option['stats_online'] == true) { $showstats .= $online; }
if($option['stats_visits_today'] == true) { $showstats .= $visits_today; }
if($option['stats_pageviews_today'] == true) { $showstats .= $pageviews_today; }
if($option['stats_visits_week'] == true) { $showstats .= $visits_week; }
if($option['stats_pageviews_week'] == true) { $showstats .= $pageviews_week; }
if($option['stats_visits_month'] == true) { $showstats .= $visits_month; }
if($option['stats_pageviews_month'] == true) { $showstats .= $pageviews_month; }
if($option['stats_visits_alltime'] == true) { $showstats .= $visits_alltime; }
if($option['stats_pageviews_alltime'] == true) { $showstats .= $pageviews_alltime; }
if($option['nflc_track_code'] == true && $option['nflc_counter_type'] != NULL) {
	$showstats .= '<br/><script type="text/javascript" src="http://www.livecounter.dk/counter.php?id='.$option['LcID'].'&img='.$option['nflc_counter_type'].'"></script><noscript><a href="http://www.livecounter.dk/" target="_blank"></a></noscript>';
}
echo $showstats;
}
 
function widget_nflc($args) {
  extract($args);
 
  $options = get_option("widget_nflc");
  if (!is_array( $options ))
{
$options = array(
      'title' => 'NF Livecounter.dk Stats'
      );
  }
 
  echo $before_widget;
    echo $before_title;
      echo $options['title'];
    echo $after_title;
 
    //Our Widget Content
    nflcWidget();

  echo $after_widget;
}
 
function nflc_control()
{
  $options = get_option("widget_nflc");
  if (!is_array( $options ))
{
$options = array(
      'title' => 'NF Livecounter Stats',
	  'LcID' => ''
      );
  }
 
  if ($_POST['nflc-Submit'])
  {
    $options['title'] = htmlspecialchars($_POST['nflc-WidgetTitle']);
	$options['LcID'] = htmlspecialchars($_POST['nflc-LcID']);
	
	$options['stats_online'] = htmlspecialchars($_POST['stats_online']);
	$options['stats_visits_today'] = htmlspecialchars($_POST['stats_visits_today']);
	$options['stats_pageviews_today'] = htmlspecialchars($_POST['stats_pageviews_today']);
	$options['stats_visits_week'] = htmlspecialchars($_POST['stats_visits_week']);
	$options['stats_pageviews_week'] = htmlspecialchars($_POST['stats_pageviews_week']);
	$options['stats_visits_month'] = htmlspecialchars($_POST['stats_visits_month']);
	$options['stats_pageviews_month'] = htmlspecialchars($_POST['stats_pageviews_month']);
	$options['stats_visits_alltime'] = htmlspecialchars($_POST['stats_visits_alltime']);
	$options['stats_pageviews_alltime'] = htmlspecialchars($_POST['stats_pageviews_alltime']);
	$options['nflc_track_code'] = htmlspecialchars($_POST['nflc_track_code']);
	$options['nflc_counter_type'] = htmlspecialchars($_POST['nflc_counter_type']);
    update_option("widget_nflc", $options);
  }
 
?>
  <p>
  	<h3>Standard info</h3>
    <label for="nflc-WidgetTitle">Widget title: </label>
    <input type="text" id="nflc-WidgetTitle" name="nflc-WidgetTitle" value="<?php echo $options['title'];?>" /><br>
    <label for="nflc-LcID">Livecounter ID: </label>
    <input type="text" id="nflc-LcID" name="nflc-LcID" value="<?php echo $options['LcID'];?>" />
    <h3>V&aelig;lg statistikker</h3>
    <input type="checkbox" id="stats_online" name="stats_online" <?php if($options['stats_online'] == true) { echo 'checked'; } ?> /> : Antal online<br>
    <input type="checkbox" id="stats_visits_today" name="stats_visits_today" <?php if($options['stats_visits_today'] == true) { echo 'checked'; } ?> /> : Bes&oslash;g i dag<br>
    <input type="checkbox" id="stats_pageviews_today" name="stats_pageviews_today" <?php if($options['stats_pageviews_today'] == true) { echo 'checked'; } ?> /> : Sidevisninger i dag<br>
    <input type="checkbox" id="stats_visits_week" name="stats_visits_week" <?php if($options['stats_visits_week'] == true) { echo 'checked'; } ?> /> : Bes&oslash;g denne uge<br>
    <input type="checkbox" id="stats_pageviews_week" name="stats_pageviews_week" <?php if($options['stats_pageviews_week'] == true) { echo 'checked'; } ?> /> : Sidevisninger denne uge<br>
    <input type="checkbox" id="stats_visits_month" name="stats_visits_month" <?php if($options['stats_visits_month'] == true) { echo 'checked'; } ?> /> : Bes&oslash;g denne m&aring;ned<br>
    <input type="checkbox" id="stats_pageviews_month" name="stats_pageviews_month" <?php if($options['stats_pageviews_month'] == true) { echo 'checked'; } ?> /> : Sidevisninger denne m&aring;ned<br>
    <input type="checkbox" id="stats_visits_alltime" name="stats_visits_alltime" <?php if($options['stats_visits_alltime'] == true) { echo 'checked'; } ?> /> : Bes&oslash;g altid<br>
    <input type="checkbox" id="stats_pageviews_alltime" name="stats_pageviews_alltime" <?php if($options['stats_pageviews_alltime'] == true) { echo 'checked'; } ?> /> : Sidevisninger altid<br>
    <h3>Livecounter tracking kode</h3>
    Denne funktion skal kun bruges hvis du ikke i forvejen har indsat livecounter koden p&aring; din hjemmeside.<br><br>
    <input type="checkbox" id="nflc_track_code" name="nflc_track_code" <?php if($options['nflc_track_code'] == true) { echo 'checked'; } ?> /> : Inds&aelig;t Tracking kode automatisk?<br>
    <select id="nflc_counter_type" name="nflc_counter_type">
    	<option value="1" <?php if($options['nflc_counter_type'] == '1') { echo 'selected'; } ?>>1</option>
        <option value="2" <?php if($options['nflc_counter_type'] == '2') { echo 'selected'; } ?>>2</option>
        <option value="3" <?php if($options['nflc_counter_type'] == '3') { echo 'selected'; } ?>>3</option>
        <option value="4" <?php if($options['nflc_counter_type'] == '4') { echo 'selected'; } ?>>4</option>
        <option value="0" <?php if($options['nflc_counter_type'] == '0') { echo 'selected'; } ?>>Usynlig</option>
    </select> : V&aelig;lg billede<br>
    <ol style="margin:3px; padding:3px; padding-left:20px;">
    	<li><img src="http://www.livecounter.dk/images/counters/1.gif"></li>
        <li><img src="http://www.livecounter.dk/images/counters/2.gif"></li>
        <li><img src="http://www.livecounter.dk/images/counters/3.gif"></li>
        <li><img src="http://www.livecounter.dk/images/counters/4.gif"></li>
    </ol>
   
    <input type="hidden" id="nflc-Submit" name="nflc-Submit" value="1" />
    <?php if ( current_user_can('manage_options') ) { echo '<a href="admin.php?page=NF-Livecounter">Options</a>'; }; ?>
  </p>

<?php
}
function nflc_init()
{
  register_sidebar_widget(__('NF Livecounter Stats'), 'widget_nflc');
  register_widget_control(   'NF Livecounter Stats', 'nflc_control', 300, 200 ); 
}
add_action("plugins_loaded", "nflc_init");

