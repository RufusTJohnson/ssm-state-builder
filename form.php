<style>

input[type="text"],input[type="password"],input[type="file"],select {

	min-width: 250px;

}



textarea {

	min-width: 300px;

	min-height: 200px;

}

.food-by-state td{
	vertical-align: middle;
	color: red;
}

.ssm_error_table.widefat td {
	vertical-align: top;
}

.ssm_error_table.widefat td.ssm_error_type  {
	vertical-align: top;
	color: #F00;
}
.ssm_error_table {
	/* background-color: #F00; */
}

.ssm_error_type {
	color: #F00;
}
.ssm_error_type {
	color: #F00;
}

.ssm_even_row{
	
}

.ssm_odd_row{
	
}

</style>

<?php

/**
 * Simple add pages or posts.
 *
 * @category      Wordpress Plugins
 * @package       Plugins
 * @author        Sam Mela
 * @copyright     Yes, Open source
 * @version       v 1.1
 */

if (!defined('ABSPATH'))

die("Aren't you supposed to come here via WP-Admin?");



// We need DB connection
global $wpdb;
?>



<br />

<h3>Create a page with links to states</h3>

<form id="form1" name="form1" method="post" action=""
	onsubmit="return confirm('Are you sure?')">
<table class="widefat">
  <thead>
		<tr>
			<th class="manage-column" style="width: 250px;">Option</th>
			<th colspan="2" class="manage-column">Setting</th>
		</tr>
  </thead>
  <tbody>
	<tr class="alternate iedit">
	 <td>Name of new page:</td>
	 <td colspan="2"><input name="NewPageName" type="text" /></td>
	</tr>
	<tr class="iedit">
		<td>Replace page if already exists<small>?</small></td>
			<td colspan="2"><input type="checkbox" name="Replace_If_Exists" id="Replace_If_Exists" /></td>
	</tr>
	<tr class="alternate iedit">
			<td>Format of Link:</td>
			<td colspan="2"><input name="LinkFormat" type="text" /></td>
	</tr>

		<tr class="iedit">

			<td valign="top">Author of post/page:</td>

			<td colspan="2"><select name="author_id">

			<?php

			$user_query = "SELECT ID, user_login, display_name, user_email FROM $wpdb->users ORDER BY ID ASC";

			$users = $wpdb->get_results($user_query);

			foreach ($users AS $row) {

				echo '<option value="'.$row->ID.'">'.$row->display_name. '</option>';

			}

			?>

			</select></td>

		</tr>

	</tbody>

</table>

<input type="submit" name="submitbutton" value="Add" 	class="button-primary">

</form>

<h3>How to use?</h3>

<p class="updated">* Create a name for a new page containing links to states<br />

* Type the title of each post or page on a seperate line in the textarea<br />

<strong>Optional:</strong><br />

* If it is a page, select the parent page (Default: none)</p>