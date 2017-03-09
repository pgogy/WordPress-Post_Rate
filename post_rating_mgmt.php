<?PHP

class post_rating_mgmt{

	public function __construct() {	
		add_action("admin_menu", array($this,"menus"));
		add_action("admin_init", array($this,"settings_init"));
		if ( is_admin() ) {
			add_action('admin_enqueue_scripts', array($this, 'manage_col_js'));
		}
		
	}
	
	function manage_col_js() {
		wp_enqueue_script( 'post_rating_sort', plugins_url('/js/jquery.tablesorter.min.js', __FILE__), array(), '1.0.0', true );
	}
	
	function menus(){
		add_submenu_page("edit.php", __("Post ratings"), __("Post ratings"), "manage_options", "post_ratings", array($this,"ratings") );
		add_submenu_page("options-general.php", __("Post rating settings"), __("Post rating settings"), "manage_options", "post_rating_settings", array($this,"settings") );
	}

	function ratings(){
		
		?>
			<h1><?PHP echo __("Post ratings"); ?></h1>
			<table id="myTable" class="tablesorter"> 
				<thead> 
					<tr> 
						<th><?PHP echo __("ID"); ?></th> 
						<th><?PHP echo __("Title"); ?></th> 
						<th><?PHP echo __("Total rating"); ?></th> 
						<th><?PHP echo __("Number of ratings"); ?></th> 
						<th><?PHP echo __("Average"); ?></th>  
					</tr> 
				</thead> 
				<tbody>
			<?PHP
							
				$posts = get_posts(array("per_page"=>-1));
				foreach($posts as $post){
					$score = 0;
					$ratings = 0;
					echo "<tr><td>" . $post->ID . "</td><td>" . $post->post_title . "</td>";
					$custom_fields = get_post_custom($post->ID);
					$my_custom_field = $custom_fields['post_rating_score'];
					$output = "";
					if($my_custom_field){
						foreach ( $my_custom_field as $key => $value ){
							$score += $value;
							$ratings++;
						}
					}
					
					$blogusers = get_users();
					foreach ( $blogusers as $user ) {
						$rate = get_post_meta($post->ID, "post_rate_user_" . $user->ID, true);
						$score += $rate;
						if($rate!=""){
							$ratings++;
						}
					}
					echo "<td>" . $score . "</td><td>" . $ratings . "</td><td>";
					if($ratings==0){
						$ratings = 1;
					}
					echo ($score/$ratings) . "</td></tr>";
				}
			
			?>
				</tbody>
			</table> 
		<script type="text/javascript" language="javascript">
			jQuery(document).ready(function() 
				{ 
					jQuery("#myTable").tablesorter(); 
				} 
			);
		</script><?PHP
	}
	
	function settings(){
		?><form method="POST" action="options.php">
		<?php 
			settings_fields("post_rating_settings");	
			do_settings_sections("options-general.php?page=post_rating_settings"); 	//pass slug name of page
			submit_button();
		?>
		</form><?PHP
	}
	
	function settings_init(){
		add_settings_section(
			'post_rating_settings',
			__('Post Ratings Settings'),
			array($this,'intro_function'),
			'options-general.php?page=post_rating_settings'
		);
		
		add_settings_field(
			'post_rating_all',
			__('Set rating for all posts'),
			array($this,'all_function'),
			'options-general.php?page=post_rating_settings',
			'post_rating_settings'
		);
		
		add_settings_field(
			'post_rating_message',
			__('Message before ratings'),
			array($this,'message_function'),
			'options-general.php?page=post_rating_settings',
			'post_rating_settings'
		);
		
		add_settings_field(
			'post_rating_label',
			__('Label on button'),
			array($this,'label_function'),
			'options-general.php?page=post_rating_settings',
			'post_rating_settings'
		);
		
		add_settings_field(
			'post_rating_feedback',
			__('Feedback to send'),
			array($this,'feedback_function'),
			'options-general.php?page=post_rating_settings',
			'post_rating_settings'
		);
		
		register_setting( 'post_rating_settings', 'post_rating_all' );
		register_setting( 'post_rating_settings', 'post_rating_message' );
		register_setting( 'post_rating_settings', 'post_rating_label' );
		register_setting( 'post_rating_settings', 'post_rating_feedback' );
	}
	
	function intro_function() {
		?><h4><?PHP echo __("Site-wide settings for Post ratings"); ?></h4>
		</p><?PHP echo __("Use the space below to set"); ?> <strong><?PHP echo __("site-wide"); ?></strong> <?PHP echo __("settings for Post ratings"); ?></p><?PHP
	}	
	
	function all_function() {
		
		$value = get_option("post_rating_all");
		$on = "";
		$off = "";
		
		if($value=="on"){
			$on = "checked";
		}else if($value=="off"){	
			$off = "checked";
		}
		
		echo "<p>" . __("Turn on rating for all posts?") . "</p>";
		echo '<input name="post_rating_all" id="post_rating_all" type="radio" ' . $on . ' value="on"> Yes <br />';
		echo '<input name="post_rating_all" id="post_rating_all" type="radio" ' . $off . ' value="off"> No';
		
	}
	
	function message_function() {
		
		$value = get_option("post_rating_message");
		
		echo "<p>" . __("Message for all posts") . "</p>";
		echo '<textarea rows="10" style="width:100%" name="post_rating_message" id="post_rating_message">' . $value . '</textarea>';
		
	}
	
	function label_function() {
		
		$value = get_option("post_rating_label");
		
		echo "<p>" . __("Button label for all posts") . "</p>";
		echo '<textarea rows="10" style="width:100%" name="post_rating_label" id="post_rating_label">' . $value . '</textarea>';
		
	}
	
	function feedback_function() {
		
		$value = get_option("post_rating_feedback");
		
		echo "<p>" . __("Feedback for all posts") . "</p>";
		echo '<textarea rows="10" style="width:100%" name="post_rating_feedback" id="post_rating_feedback">' . $value . '</textarea>';
		
	}
	
}
	
$post_rating_mgmt = new post_rating_mgmt();

