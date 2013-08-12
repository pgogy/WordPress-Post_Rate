<?PHP

class post_rating_menu{

	public function __construct() {	
		add_action("add_meta_boxes", array($this,"add_menu") );
		add_action('save_post', array($this,'save_post'));
	}

	function add_menu(){
		
		add_meta_box( 'post_rating', 'Post rating setup', array($this, "post_rating_menu"),"post","normal","high");
		
	}
	
	function post_rating_menu(){
	
		$rating = get_post_meta($_GET['post'], "post_rating");
		$message = get_post_meta($_GET['post'], "post_message");
		$button = get_post_meta($_GET['post'], "post_button");
		$feedback = get_post_meta($_GET['post'], "post_feedback");
		
		?><p>Allow rating for this post	<input name="post_rating_on" type="checkbox" <?PHP if($rating[0]=="true"){ echo " checked "; } ?> /></p><?PHP
		
		?><p>Message before rating box <textarea rows=5 style="width:100%" name="post_message"><?PHP if($message!=""){ echo $message[0]; }else{ echo "Enter feedback instructions here"; } ?></textarea></p><?PHP
		
		?><p>Label on button <textarea rows=5 style="width:100%" name="post_button"><?PHP if($message!=""){ echo $button[0]; }else{ echo "Enter button label here"; } ?></textarea></p><?PHP

		?><p>Feedback post submission <textarea rows=5 style="width:100%" name="post_feedback"><?PHP if($feedback!=""){ echo $feedback[0]; }else{ echo "Enter message to appear after submission"; } ?></textarea></p><?PHP
	
	}
	
	function save_post($post_id){
		
		if($_POST['post_rating_on']=="on"){
			
			update_post_meta($post_id, "post_rating", "true");
			
		}else{
		
			update_post_meta($post_id, "post_rating", "false");
		
		}
		
		update_post_meta($post_id, "post_message", $_POST['post_message']);
		update_post_meta($post_id, "post_button", $_POST['post_button']);
		update_post_meta($post_id, "post_feedback", $_POST['post_feedback']);

	}

}
	
$post_rating_menu = new post_rating_menu();

