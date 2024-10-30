<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://knovator.com/wordpress-plugin-development-services/
 * @since      1.0.0
 *
 * @package    Bulk_Price_Update
 * @subpackage Bulk_Price_Update/admin/partials
 */

global $woocommerce;
$currency = get_woocommerce_currency_symbol();
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="title">
	<h1>Bulk Price Update</h1>
</div>
<div class="content">
	<div class="dashboard">
		<form id="bulk-price-update" action="">
			<table class="styledtable widefat">
				<tbody>
				<tr>
						<th>Please Select Categories:</th>
						<td>
						<?php
						$args = array(
							'taxonomy'    => 'product_cat',
							'orderby'     => 'ASC',
							'multiple'    => true,
							'value_field' => 'slug',
							// 'required'    => true,
						);
						wp_dropdown_categories( $args );
						?>
						</td>
					</tr>
					<tr>
						<th>Percentage:</th>
						<td><input type="number" name="percentage" id="percentage" value="0" required> %</td>
					</tr>	
					<tr>
						<th>Round off Price:</th>
						<td>
							<input type="checkbox" name="check-round-point" id="check-round-point" value="1">
							<label class="lbl_tc" for="check-round-point"><strong>( <?php echo $currency; ?> 5.2 => <?php echo $currency; ?> 5 or <?php echo $currency; ?> 5.9 => <?php echo $currency; ?>6 )</strong></label>
					</td>
					</tr>
					<tr>
						<th>Price Increase:</th>
						<td><input type="radio" name="price_change_type" id="price_change_type" value="increase-percentge" checked="checked">
						<label class="lbl_tc" for="increase-percentge">(Regular price and sale price)</label>
					</td>
					</tr>
					<tr>
						<th>Price Decrease:</th>
						<td><input type="radio" name="price_change_type" id="price_change_type" value="decrease-percentage">
						<label class="lbl_tc" for="decrease-percentge">(Regular price and sale price)</label>
					</td>
					</tr>
				</tbody>	
			</table>
			<?php submit_button( 'Submit', 'button button-primary' ); ?>
		</form>	
		<div id="loader" class="loader-dual-ring hidden overlay"></div>
			<div style="display:none;" id="update_product_results" class="update_product_results">
							<table class="widefat striped styledtable">
								<thead>
									<tr>
										<td>No.</td>
										<td>Thumb</td>
										<td>Product ID</td>
										<td>Product Name</td>
										<td>Product Type</td>
										<td>Old Price <span class="dashicons dashicons-arrow-right-alt"></span>New Price</td>
									</tr>
								</thead>
								<tbody id="update_product_results_body"></tbody>
							</table>
						</div>
	</div>
</div>


