<?php $page="join"; ?>
<?php require_once("server-settings.php"); ?>
<?php include("ortak-header.php"); ?>
<?php require_once('fb-connect-code.php'); ?>

<div class="banner2">
<h2><?php echo $index_page_header; ?></h2>
</div>
<div class="content-main " >

	
<br><br>

  <h2><strong><?php echo $Join_Welcome_Text; ?></strong></h2>
  <h3><?php echo $Join_Welcome_Subtitle; ?></h3>
  <a id="facebookButton" onclick="facebookLogin(); return false;" href="#"><font color=white><?php echo $index_page_signin_up_fb_txt; ?></font></a>
 
	<?php echo $Join_No_Facebook; ?>


<br><br>


<script type="text/javascript">
jQuery(document).ready(function(){ 
	setBackgroundValues("no-repeat","#C0DEED","/bg/bg1.png","", "#333333", "#000000", "#333333", "#CCCCCC");
});
</script>

<?php include("fotter.php"); ?>


