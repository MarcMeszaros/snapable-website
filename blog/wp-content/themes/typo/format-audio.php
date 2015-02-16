<div class="post-content">
	<div class="post-audio">
		<script type="text/javascript">
	     /* <![CDATA[  */   
	
	    var J = jQuery.noConflict();
	
	    J(document).ready(function(){
	
	        J("#jquery_jplayer_<?php the_ID();?>").jPlayer({
	            ready: function () {
	                J(this).jPlayer("setMedia", {
	                    m4a: "<?php echo get_post_meta($post->ID, 'm4a', true) ?>",
	                    oga: "<?php echo get_post_meta($post->ID, 'oga', true) ?>",
	                    mp3: "<?php echo get_post_meta($post->ID, 'mp3', true) ?>"
	                });
	            },
	            ended: function (event) {
	                J(this).jPlayer("play");
	            },
	            cssSelectorAncestor: "#jp_interface_<?php the_ID();?>",
	            swfPath: "<?php echo get_template_directory_uri(); ?>/includes/jplayer/js",
	            supplied: "m4a, oga, mp3"
	        });
	    });
	    /* ]]> */
	    </script>
		
		<div class="post-audio-text">
			<?php the_content(); ?>
		</div>
		
		<!-- audio player -->
		<div id="jquery_jplayer_<?php the_ID();?>" class="audio-file jp-jplayer"></div>
		
		<div class="jp-audio-container">
			<div class="jp-audio">
				<div class="jp-type-single">
					<div id="jp_interface_<?php the_ID();?>" class="jp-interface">
						<ul class="jp-controls">
							<li><a href="#" class="jp-play" tabindex="1"><?php _e('play','okay'); ?></a></li>
							<li><a href="#" class="jp-pause" tabindex="1"><?php _e('pause','okay'); ?></a></li>
							<li><a href="#" class="jp-mute" tabindex="1"><?php _e('mute','okay'); ?></a></li>
							<li><a href="#" class="jp-unmute" tabindex="1"><?php _e('Share','okay'); ?></a></li>
						</ul>
						<div class="jp-progress-container">
							<div class="jp-progress">
								<div class="jp-seek-bar">
									<div class="jp-play-bar"></div>
								</div>
							</div>
						</div>
						<div class="jp-volume-bar-container">
							<div class="jp-volume-bar">
								<div class="jp-volume-bar-value"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
	</div><!-- post audio -->
</div><!-- post content -->