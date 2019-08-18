<?php
/**
 * Template for the hovercard.
 *
 * @package bporg-breath
 *
 * @since 1.0.0
 */
?>
<script type="text/html" id="tmpl-o2-hovercard">
	<div class="o2hc-container">
		<# if ( data.loader ) { #>
			<img src="{{data.loader}}" class="o2hc-loader">

		<# } else if ( data.error ) { #>
			<p class="error">{{data.errorText}}</p>

		<# } else { #>
			<div class="o2hc-ticket">
				<# if ( ( data.meta.Reporter && data.meta.Reporter.avatar_url ) || ( data.meta.Owner && data.meta.Owner.avatar_url ) ) { #>
					<div class="o2hc-avatar">
						<# if ( data.meta.Reporter ) { #>
							<img src="{{{data.meta.Reporter.avatar_url}}}" class="avatar">
						<# } else { #>
							<img src="{{{data.meta.Owner.avatar_url}}}" class="avatar">
						<# } #>
					</div>
				<# } #>

				<div class="o2hc-description" open="true">
					<div class="o2hc-title">{{data.title}}</div>
					<div class="o2hc-subtitle">
						<a href="{{{data.url}}}">{{data.subtitle}}</a>
					</div>

					<# if ( data.description ) { #>
						<div class="description">{{{data.description}}}</div>
					<# } #>

					<# if ( data.keywords && _.values( data.keywords ).length ) { #>
						<ul class="keywords">
							<li>
								<span class="dashicons dashicons-tag"></span>
								<span class="screen-reader-text"><?php esc_html_e( 'Tags:', 'o2-hovercards' ) ;?></span>
							</li>
							<# _.each( data.keywords, function( link, tag ) { #>
								<li><a href="{{link}}">{{tag}}</a></li>
							<# } ) #>
						</ul>
					<# } #>
				</div>
			</div>
		<# } #>
		<# if ( data.meta ) { #>
			<table class="meta">
				<thead>
					<tr>
						<# _.each( data.meta, function( meta_value, meta_key ) { #>
							<th>{{meta_key}}</th>
						<# } ) #>
					</tr>
				</thead>
				<tbody>
					<tr>
						<# _.each( data.meta, function( meta_value ) { #>
							<# if ( _.isObject( meta_value ) ) { #>
								<td>{{{meta_value.name}}}</td>
							<# } else { #>
								<td>{{{meta_value}}}</td>
							<# } #>
						<# } ) #>
					</tr>
				</tbody>
			</table>
		<# } #>
	</div>
</script>
