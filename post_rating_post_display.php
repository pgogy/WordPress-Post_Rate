<?PHP

class post_rating_post_display {
	
	public function __construct() {	
		add_filter( 'the_content', array($this, "the_content")  );
	}
	
	function the_content($content) {

		global $post, $current_user;
		get_currentuserinfo();
		
		echo $content;
		
		$rating = get_post_meta($post->ID, "post_rating");
		$message = get_post_meta($post->ID, "post_message");
		$button = get_post_meta($post->ID, "post_button");

		if($rating[0]=="true" || get_option("post_rating_all") == "on"){

			if($post->post_type=="post"){
			
				if(isset($message[0])){
				
					$message = $message[0];
				
				}else{
				
					$message = get_option("post_rating_message");
					
					if($message=="" || $message === FALSE){
					
						$message = __("Give this post a grade / mark out of ten, or provide a comment below");
					
					}
			
				}
				
				if(isset($button[0])){
				
					$button = $button[0];
				
				}else{
				
					$button = get_option("post_rating_label");
					
					if($button=="" || $button === FALSE){
					
						$button = __("Rate Post");
					
					}
				
				}

				?><div id='mark_post'>
					<form method="POST" action="javascript:mark_post_submit('<?PHP echo $post->ID; ?>')">
						<p><?PHP echo $message; ?></p>
						<input type="text" id="markpost_score"/>
						<input type="submit" value="<?PHP echo $button; ?>" />
					</form>
				</div><?PHP
				
				$roles = array_keys($current_user->caps);
				
				if($current_user->ID == $post->post_author || in_array("administrator", $roles)){
					
					$custom_fields = get_post_custom($post->ID);
					$my_custom_field = $custom_fields['post_rating_score'];
					$output = "";
					if($my_custom_field){
						foreach ( $my_custom_field as $key => $value ){
							$output .= __("Anonymous rated as") . " " . $value . ",";
						}
					}
					
					$blogusers = get_users();
					foreach ( $blogusers as $user ) {
						$rate = get_post_meta($post->ID, "post_rate_user_" . $user->ID, true);
						if($rate==""){
							$rate = __("hasn't rated");
						}else{
							$rate = __("rated as") . " " . $rate;
						}
						$output .= esc_html( $user->display_name ) . " " . $rate . ",";
					}
					
					if($output!=""){
					
						echo "<div id='feedback'><p>" . __("Feedback so far") . "</p><p>" . substr($output,0,-1) . "</p></div>";
					
					}
					
				}
				
			}
		
		}
			
	}
	
}
	
$post_rating_post_display = new post_rating_post_display();
