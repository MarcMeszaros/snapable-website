<form action="<?php echo home_url( '/' ); ?>" class="search-form clearfix">
	<fieldset>
		<input type="text" class="search-form-input text" name="s" onfocus="if (this.value == '<?php _e('Search...','okay'); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e('Search...','okay'); ?>';}" value="<?php _e('Search...','okay'); ?>"/>
		<input type="image" src="<?php echo get_template_directory_uri(); ?>/images/search-icon.png" value="Go" class="submit" />
	</fieldset>
</form>