<?php
/**
 * Widget Preview Template (Title)
 *
 * @package immonex\KickstartElementor
 */

?>
<#
let contents = <?php echo $template_data['demo_content']; ?>;
#>
<{{{ settings.html_tag }}} class="inx-e-heading">{{{ contents.title }}}</{{{ settings.html_tag }}}>
