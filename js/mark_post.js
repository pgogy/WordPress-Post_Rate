function mark_post_submit(post_id){ 

	jQuery(document).ready(function($) {
	
		if(post_id!=""){
	
			var data = {
				action: "mark_post_submit",
				id:post_id,
				mark:document.getElementById("markpost_score").value,
				nonce: mark_post.answerNonce
			};
			
			jQuery.post(mark_post.ajaxurl, data, function(response) {
				document.getElementById("mark_post").innerHTML=response;
			});
			
		}

	});
	
}