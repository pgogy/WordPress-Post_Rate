<?PHP

class post_rate_post_display {
	
	public function __construct() {	
		add_filter( 'the_content', array($this, "the_content")  );
	}
	
	function the_content($content) {

		global $post;
		
		echo $content;

		if($post->post_type=="post"){

			?><div id='mark_post'>
				<form method="POST" action="javascript:mark_post_submit('<?PHP echo $post->ID; ?>')">
					<p>Give this post a grade / mark out of ten, or provide a comment below</p>
					<input type="text" id="markpost_score"/>
					<input type="submit" value="Grade" />
				</form>
			</div><?PHP

		}
			
	}
	
}
	
$post_rating_post_display = new post_rate_post_display();
