<?php require_once("server-settings.php"); ?>
<?php include("ortak-header.php"); ?>

<script type="text/javascript">
$(document).ready(function(){ 
	content_height = $('#content').height();
	if (content_height<700) {content_height = 700;}

	newHeight = content_height;
	$('#content').css('height',newHeight+"px" );

	newHeightGlow = content_height-208;
	$('#glow-mid').css('height',newHeightGlow+"px" );

	newTop = $('#content').height();
	$('#footer').css('top',newTop+"px" );
	$('#footer').css('height',($(window).height()-$('#content').height()-60) +"px" );
	$('#newfooter').css('z-index',"100" );
	

	$('.glow').css('top',$('#content').offset().top-115 );
	$('.glow').css('left',$('#content').offset().left-128 );
	$('.glow').show();
	
});

$(window).resize(function() {
	content_height = $('#content').height();
	if (content_height<700) {content_height = 700;}

	newHeight = content_height;
	$('#content').css('height',newHeight+"px" );

	newHeightGlow = content_height-208;
	$('#glow-mid').css('height',newHeightGlow+"px" );

	newTop = $('#content').height();
	$('#footer').css('top',newTop+"px" );
	$('#footer').css('height',($(window).height()-$('#content').height()-60) +"px" );
	$('#newfooter').css('z-index',"100" );
	

	$('.glow').css('top',$('#content').offset().top-115 );
	$('.glow').css('left',$('#content').offset().left-128 );
	$('.glow').show();
});
</script>


	<div class="glow" style="display:none">
		<div class="glow-top">&nbsp;</div>
		<div id="glow-mid" class="glow-mid" style="height: 456px;">&nbsp;</div>
		<div class="glow-bottom">&nbsp;</div>
	</div>


	<div class="page ">
		<div id="main" class="main" >
			<div id="content" style="z-index:20; position:relative;">

				<div class="title-bar index-title-bg">
					<div>
						<span id="todo_list_title">CONTACT US</span>
					</div>
				</div>


				<div style="padding:10px">
					FAQ For Jumzu
				</div>


				
			</div>
		</div>
	</div>

<?php include("fotter.php"); ?>