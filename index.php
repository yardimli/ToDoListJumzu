<?php require_once("server-settings.php"); ?>
<?php include("ortak-header.php"); ?>
<script type="text/javascript" src="todolist.js"></script>

	<div class="glow" style="display:none">
		<div class="glow-top">&nbsp;</div>
		<div id="glow-mid" class="glow-mid" style="height: 456px;">&nbsp;</div>
		<div class="glow-bottom">&nbsp;</div>
	</div>


	<Div ID="savingX" style="z-index:240; position:fixed; display:none; top:10px; right:10px; width:100px;  height:20px; padding:10px; background-color:#666666; opacity:0.6; font-size:14px; font-weight:bold; color:white;">SAVING...</Div>

	<div class="page ">
		<div id="main" class="main" >
			<div id="content" style="z-index:20; position:relative;">

				<div class="title-bar index-title-bg">
					<div>
						<span id="todo_list_title"></span>
					</div>
				</div>


				<div id="right-panel" style="width:200px; padding-right:0px">
					<div id="tools-wrapper" style="top: 0px;">

						<div id="acordion_menu_1">
							<div class="panel-bar index-grad6" style="-moz-user-select: none; cursor: pointer;">
							<img align="left" class="controls-tool_box" src="images/blank.gif" alt=""><span class="panel-bar-span">List Tools</span>
							</div>

							<div id="acordion_menu_1_child" >
								<div style="height: 220px; top: 0px; left: 0px; width: 200px; background: none repeat scroll 0% 0% rgb(245, 245, 245);" class="panel-content panel-content-open">
									<div class="panel-content-inner">
									<ol class="tools" id="toolbox" style="margin-top:0px">
										<li id="controls-checkbox" class="drags">
										<div><img align="left" class="controls-checkbox" src="images/blank.gif" alt=""><span>To Do Item</span><img align="right" title="" alt="" src="images/blank.gif" class="info  toolbar-info_grey" style="display: none;"></div>
										</li>
										<li id="control_image" class="drags">
										<div><img align="left" class="controls-image" src="images/blank.gif" alt=""><span>Image</span><img align="right" title="" alt="" src="images/blank.gif" class="info  toolbar-info_grey" style="display: none;"></div>
										</li>
										<li id="control_datetime" class="drags">
										<div><img align="left" class="controls-datetime" src="images/blank.gif" alt=""><span>DateTime</span><img align="right" title="" alt="" src="images/blank.gif" class="info  toolbar-info_grey" style="display: none;"></div>
										</li>
										<li id="control_text" class="drags">
										<div><img align="left" class="controls-text" src="images/blank.gif" alt=""><span>Free Text</span> (HTML)<img align="right" title="" alt="" src="images/blank.gif" class="info  toolbar-info_grey" style="display: none;"></div>
										</li>
										<li id="control_fileupload" class="drags">
										<div><img align="left" class="controls-upload" src="images/blank.gif" alt=""><span>File Upload</span><img align="right" title="" alt="" src="images/blank.gif" class="info  toolbar-info_grey" style="display: none;"></div>
										</li>
										<li id="control_button" class="drags">
										<div><img align="left" class="controls-button" src="images/blank.gif" alt=""><span>Tags</span><img align="right" title="" alt="" src="images/blank.gif" class="info  toolbar-info_grey" style="display: none;"></div>
										</li>
										<li id="controls-survey_tools" class="drags">
										<div><img align="left" class="controls-survey_tools" src="images/blank.gif" alt=""><span>Color</span><img align="right" title="" alt="" src="images/blank.gif" class="info  toolbar-info_grey" style="display: none;"></div>
										</li>
										<li id="controls-slider" class="drags">
										<div><img align="left" class="controls-slider" src="images/blank.gif" alt=""><span>Precentage</span><img align="right" title="" alt="" src="images/blank.gif" class="info  toolbar-info_grey" style="display: none;"></div>
										</li>
									</ol>
									</div>
								</div>
							</div>
						</div>

						<div id="acordion_menu_2">
							<div class="panel-bar index-grad6" style="-moz-user-select: none; cursor: pointer;">
							<img align="left" class="controls-quick_tools" src="images/blank.gif" alt=""><span class="panel-bar-span">My Lists</span>
							</div>

							
							<div id="acordion_menu_2_child" >
								<div class="panel-content" style="height: 400px; top: 0px; left: 0px; width: 200px; overflow:auto; background: none repeat scroll 0% 0% rgb(245, 245, 245); ">
									<div class="panel-content-inner">
									<ul class="tools" id="user-todo-lists" style="">
									</ul>
									</div>
								</div>
							</div>
						</div>
					</div> 
				</div> 


				<div class="form-all" id="stage" style="height:600px">
				</div>
			</div>
		</div>
	</div>

<?php include("fotter.php"); ?>