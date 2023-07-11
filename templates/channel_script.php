<script type="text/javascript">

	$(document).ready(function(e) {

	    $("#channel_type").change(function() {
			var type = $("#channel_type").val();
			identifyTypes(type);
		});

		$( window ).load(function() {
		var type=$("#channel_type").val();
			identifyTypes(type);
		});

		function identifyTypes(type){
			if (type == '<?php echo YOUTUBE; ?>')	{
				showYoutube();
			} else if (type == '<?php echo STREAMING; ?>') {
				showChannel();
			} else if (type == '<?php echo EMBEDDED; ?>') {
				showEmbedded();
			} 
		}

		function showChannel(){
			$("#youtube_url_div").hide();
			$("#youtube_url_two_div").hide();
			//Show Chapter Data
			$("#channel_url_div").show();
			$("#channel_url_two_div").show();
			$("#channel_img_div").show();
			$("#channel_user_agent_div").show();
			//Hide Embedded Data
			$("#embedded_url_div").hide();
			$("#embedded_url_two_div").hide();
		}

		function showYoutube() {
			$("#youtube_url_div").show();
			$("#youtube_url_two_div").show();
			//Hide Chapter Data
			$("#channel_url_div").hide();
			$("#channel_url_two_div").hide();
			$("#channel_img_div").hide();
			$("#channel_user_agent_div").hide();
			//Hide Embedded Data
			$("#embedded_url_div").hide();
			$("#embedded_url_two_div").hide();
		}

		function showEmbedded() {
			$("#embedded_url_div").show();
			$("#embedded_url_two_div").show();
            $("#channel_img_div").show();
			//Hide Chapter Data
			$("#channel_url_div").hide();
			$("#channel_url_two_div").hide();
			$("#channel_user_agent_div").hide();
			//Hide Youtube Data
			$("#youtube_url_div").hide();
			$("#youtube_url_two_div").hide();
		}

	});

</script>
