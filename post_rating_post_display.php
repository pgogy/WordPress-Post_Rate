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

		if($rating[0]=="true"){

			if($post->post_type=="post"){
			
				if(isset($message[0])){
				
					$message = $message[0];
				
				}else{
				
					$message = "Give this post a grade / mark out of ten, or provide a comment below";
			
				}
				
				if(isset($button[0])){
				
					$button = $button[0];
				
				}else{
				
					$button = "Rate Post";
			
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
					  foreach ( $my_custom_field as $key => $value )
						$output .= $value . ",";
					
					if($output!=""){
					
						echo "<div id='feedback'><p>Feedback so far</p><p>" . substr($output,0,-1) . "</p></div>";
					
					}
					
				}
				
			}
		
		}
			
	}
	
}
	
$post_rating_post_display = new post_rating_post_display();
