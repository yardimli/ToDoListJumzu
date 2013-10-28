	
		<div class="footer-links">
			<div id="newfooter">
				<div id="newfooter-content">
					<div class="column">
					<h3>Jumzu</h3>
					<ul>
					<li><a href="index.php">ToDo List Builder</a></li>
					<li><a href="index.php">My Lists</a></li>
					</ul>
					</div>

					<div class="column">
					<h3>Support</h3>
					<ul>
					<li><a href="faq.php">FAQ</a></li>
					<li><a href="contact.php">Contact Us</a></li>
					<li><a href="help.php">User Guide</a></li>
					</ul>
					</div>

					<div class="column">
					<h3>Community</h3>
					<ul>
					<li><a href="http://www.facebook.com/jumzucom/">Facebook</a></li>
					<li><a href="http://twitter.com/jumzucom/">Twitter</a></li>
					</ul>
					</div>

					<div class="column">
					<h3>About Us</h3>
					<ul>
					<li><a href="http://blog.jumzu.com">Blog</a></li>
					<li><a href="about.php">About Us</a></li>
					<li><a href="terms.php">Terms of Use</a></li>
					<li><a href="privacy.php">Privacy</a></li>
					</ul>
					</div>
				
					<div class="column">
					<h3><nobr>&copy; 2012 <a href="http://www.izlenim.com/">izlenim</a></nobr></h3>
					
					</div>
				</div>
			</div>
		</div>

	<div id="footer" style="top:700px; z-index: 10; ">
	</div>
		<!-- ui-dialog -->
		<div id="dialog-confirm" title="Delete this item?" style="display: none">
			<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 40px 0;"></span>The item and it's children will be deleted. Are you sure?</p>
		</div>

		<div id="dialog-notyet" title="Not Yet Supported" style="display: none">
			<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 40px 0;"></span>Sorry, this feature is not yet built.</p>
		</div>
		
		<div id="dialog-list-confirm" title="Delete this entire list?" style="display: none">
			<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 40px 0;"></span>The list and all it's items will be deleted. Are you sure?</p>
		</div>

		<div id="dialog-themes" title="Themes" style="display:none">
			
			<div class="window-content-wrapper" style="padding: 0px; ">
				<div style="z-index: 20; height: auto; position: relative; display: inline-block; width: 100%; " class="window-content">
					<div style="position: relative; display: inline-block; ">

						<div style="padding: 10px; margin-top: 0px; margin-bottom: 5px; display: inline-block;">
						
							<?php
							$mysqlresult = mysql_query("SELECT * FROM sitethemes ORDER BY ID ASC");
							
							$num_rows = mysql_num_rows($mysqlresult);
							$i = 0;
							WHILE ($i<$num_rows) {
							?>
								<div class="theme-cont <?php if (mysql_result($mysqlresult,$i,"ID")==13) { echo " theme-selected "; } ?>"><div id="theme<?php echo mysql_result($mysqlresult,$i,"ID"); ?>" class="theme-img" style="background: url('<?php echo mysql_result($mysqlresult,$i,"BgImageSmall"); ?>') repeat scroll 0% 0% transparent;"></div>
									<div class="theme-text"><?php echo mysql_result($mysqlresult,$i,"ThemeName"); ?></div><?php if (mysql_result($mysqlresult,$i,"Artist")!="") { ?><img class="theme-info" src="images/s-info.png"><div class="theme-details" style="top: 10px; left: 0px; z-index: 1;"><b>Artist:</b><?php echo mysql_result($mysqlresult,$i,"Artist"); ?><br><a target="_blank" href="<?php echo mysql_result($mysqlresult,$i,"Source"); ?>">Image Source</a></div><?php } ?> </div>
							<?php
								$i++;
							}
							?>
							
						</div>
					</div>
				</div>
			</div>		
		
		</div>

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-33668707-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
	</body>
</html>


