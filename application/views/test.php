

<style type="text/css">
	@import url("<?php echo site_url('/css/ghostdown.css');?>");
</style>

	<div class="features">
		
		<section class="editor">
			<div class="outer">
				<div class="editorwrap">
					<section class="entry-markdown">
						<header class="floatingheader">
							&nbsp;&nbsp; Markdown 
						</header>
						<section class="entry-markdown-content">
							<textarea id="entry-markdown"><?php echo file_get_contents(APPPATH.'/webroot/markdown.text');?></textarea>
						</section>
					</section>
					<section class="entry-preview active">
						<header class="floatingheader">
						  &nbsp;&nbsp; Preview
						</header>
						<section class="entry-preview-content">
							<div class="rendered-markdown"></div>
						</section>
					</section>
				</div>
			</div>
		</section>
	</div>

<script type="text/javascript" src="<?php echo site_url('/js/ghostdown/codemirror.js');?>"></script>

<script type="text/javascript" src="<?php echo site_url('/js/ghostdown/jquery.ui.widget.js');?>"></script>

<script type="text/javascript" src="<?php echo site_url('/js/ghostdown/jquery.file.upload.js');?>"></script>
<script type="text/javascript" src="<?php echo site_url('/js/ghostdown/jquery.ui.upload.js');?>"></script>

<script type="text/javascript" src="<?php echo site_url('/js/ghostdown/showdown.js');?>"></script>
<script type="text/javascript" src="<?php echo site_url('/js/ghostdown/showdown.ghostdown.js');?>"></script>

<script type="text/javascript" src="<?php echo site_url('/js/ghostdown/ghostdown.js');?>"></script>
