<?php // Remove any math from here 
		
/* 
	
	<div class="wrap">
	    <h2><?php _e( 'System Report', 'bp-system-report' ) ?></h2>
	<?php
	
	// Removing form and any work with dates until I have the math and functions working properly
		<form action="admin.php?page=bp-system-report/bp-system-report-functions.php" method="post">
		
			Compare
			<select name="bpsr_b">
				<?php foreach( $report_dates as $date ) : ?>
					<option value="<?php echo $date ?>" <?php echo ($b == $date) ? 'selected="selected"' : '' ?>><?php echo bp_system_report_format_date( $date ); ?></option> 
				<?php endforeach; ?>
			</select>
			with
			<select name="bpsr_a">
					<option value="">Now</option>
				<?php foreach( $report_dates as $date ) : ?>
					<option value="<?php echo $date ?>" <?php echo ($a == $date) ? 'selected="selected"' : '' ?>><?php echo bp_system_report_format_date( $date ); ?></option> 
				<?php endforeach; ?>
			</select>
			
			<input class="button-secondary" name="Submit" type="submit" value="<?php esc_attr_e('Go'); ?>" />
			
		</form> 

		?>
		
		
		
		<table id="bp-sr-table" class="widefat" cellspacing=0>
		
		<thead>
			<tr>
				<th scope="col"></th>
				<th scope="col"></th>
				<th scope="col"><?php echo bp_system_report_format_date( $b ); ?></th>
				<th scope="col"><?php echo (!$_POST['bpsr_a']) ? "Now" :  bp_system_report_format_date( $a ) ?></th>
				<th scope="col"><?php _e( 'Change', 'bp-system-report') ?></th>
			</tr>
			
			<tr class="bp-sr-type-label">
			
				<th scope="row" colspan=5>Members</th>
			
			</tr>
			
			<tr>
				<th scope="row"></th>
				<th scope="row">Total</th>
				
				<td><?php echo $b_data->members['total']; ?></td>
				<td><?php echo $a_data->members['total']; ?></td>
				<td><?php bp_system_report_compare( $a_data->members['total'], $b_data->members['total'] ) ?></td>
			</tr>
		
			<tr>
				<th scope="row"></th>
				<th scope="row"># active</th>
				
				<td><?php echo $b_data->members['total_active']; ?></td>
				<td><?php echo $a_data->members['total_active']; ?></td>
				<td><?php bp_system_report_compare( $a_data->members['total_active'], $b_data->members['total_active'] ) ?></td>
			</tr>
		
			<tr>
				<th scope="row"></th>
				<th scope="row">% active</th>
				
				<td><?php echo $b_data->members['percent_active']; ?></td>
				<td><?php echo $a_data->members['percent_active']; ?></td>
				<td><?php bp_system_report_compare( $a_data->members['percent_active'], $b_data->members['percent_active'] ) ?></td>
			</tr>

			<tr>
				<th scope="row"></th>
				<th scope="row">Total friendships</th>
				
				<td><?php echo $b_data->members['friendships']; ?></td>
				<td><?php echo $a_data->members['friendships']; ?></td>
				<td><?php bp_system_report_compare( $a_data->members['friendships'], $b_data->members['friendships'] ) ?></td>
			</tr>
	
			<tr>
				<th scope="row"></th>
				<th scope="row">Avg friendships per member</th>
				
				<td><?php echo round( $b_data->members['average_friendships'], 2 ); ?></td>
				<td><?php echo round( $a_data->members['average_friendships'], 2 ); ?></td>
				<td><?php bp_system_report_compare( $a_data->members['average_friendships'], $b_data->members['average_friendships'] ) ?></td>
			</tr>
			
			<tr class="bp-sr-type-label">
			
				<th scope="row" colspan=5>Groups</th>
			
			</tr>
			
			<tr>
				<th scope="row">all groups</th>
				<th scope="row">Total</th>
				
				<td><?php echo $b_data->groups['total']; ?></td>
				<td><?php echo $a_data->groups['total']; ?></td>
				<td><?php bp_system_report_compare( $a_data->groups['total'], $b_data->groups['total'] ) ?></td>
			</tr>
		
			<tr>
				<th scope="row">all groups</th>
				<th scope="row"># active</th>
				
				
				<?php 	$a_types = $a_data->groups['active'];
						
						$a_active = 0;
						foreach( $a_types as $t ) {
							$a_active += (int)$t;
						}
						
						$b_types = $b_data->groups['active'];
						
						$b_active = 0;
						foreach( $b_types as $t ) {
							$b_active += (int)$t;
						}
				?>
				
				<td><?php echo $b_active; ?></td>
				<td><?php echo $a_active; ?></td>
				<td><?php bp_system_report_compare( $a_active, $b_active ) ?></td>
			</tr>
		
			<tr>
				<th scope="row">all groups</th>
				<th scope="row">% active</th>
				
				<?php	
						$a_p = bp_system_report_percentage( $a_active/$a_data->groups['total'] );
						$b_p = bp_system_report_percentage( $b_active/$b_data->groups['total'] );
						
				?>
				<td><?php echo $b_p; ?></td>
				<td><?php echo $a_p; ?></td>
				<td><?php bp_system_report_compare( $a_p, $b_p ) ?></td>
			</tr>

					
			<tr>
				<th scope="row"><?php _e( "all groups", 'bp-system-report' ) ?></th>
				<th scope="row">total group memberships</th>
				
				
				<?php 	$a_types = $a_data->groups['members'];
						
						$a_members = 0;
						foreach( $a_types as $t ) {
							$a_members += (int)$t;
						}
						
						$b_types = $b_data->groups['members'];
						
						$b_members = 0;
						foreach( $b_types as $t ) {
							$b_members += (int)$t;
						}
				?>
				
				<td><?php echo $b_members ?></td>
				<td><?php echo $a_members ?></td>
				<td><?php bp_system_report_compare( $a_members, $b_members ) ?></td>
			</tr>
			
			<tr>
				<th scope="row"><?php _e( "all groups", 'bp-system-report' ) ?></th>
				<th scope="row">average group membership</th>
				
				
				<?php	
						$a_p = $a_members/$a_data->groups['total'];
						$b_p = $b_members/$b_data->groups['total'];
						
				?>
				<td><?php echo round( $b_p, 2 ); ?></td>
				<td><?php echo round( $a_p, 2 ); ?></td>
				<td><?php bp_system_report_compare( $a_p, $b_p ) ?></td>
			</tr>
		
		
			<?php $type_array = array( 'public', 'private', 'hidden' ); ?>
			
			<?php foreach( $type_array as $type ) : ?>
			<tr class="padder"></tr>
		
			<tr>
				<th scope="row"><?php _e( "$type groups", 'bp-system-report' ) ?></th>
				<th scope="row">Total</th>
				
				<td><?php echo $b_data->groups['types'][$type]; ?></td>
				<td><?php echo $a_data->groups['types'][$type]; ?></td>
				<td><?php bp_system_report_compare( $a_data->groups['types'][$type], $b_data->groups['types'][$type] ) ?></td>
			</tr>
		
			<tr>
				<th scope="row"><?php _e( "$type groups", 'bp-system-report' ) ?></th>
				<th scope="row">as % of total groups</th>
				
				<td><?php echo bp_system_report_percentage( $b_data->groups['types'][$type]/$b_data->groups['total'] ); ?></td>
				<td><?php echo bp_system_report_percentage( $a_data->groups['types'][$type]/$a_data->groups['total'] ); ?></td>
				<td><?php bp_system_report_compare( bp_system_report_percentage( $a_data->groups['types'][$type]/$a_data->groups['total'] ), bp_system_report_percentage( $b_data->groups['types'][$type]/$b_data->groups['total'] ) ) ?></td>
			</tr>
			
			<tr>
				<th scope="row"><?php _e( "$type groups", 'bp-system-report' ) ?></th>
				<th scope="row"># active</th>
				
				<td><?php echo $b_data->groups['active'][$type] ?></td>
				<td><?php echo $a_data->groups['active'][$type] ?></td>
				<td><?php bp_system_report_compare( $a_data->groups['active'][$type], $b_data->groups['active'][$type] ); ?></td>
			</tr>
			
			<tr>
				<th scope="row"><?php _e( "$type groups", 'bp-system-report' ) ?></th>
				<th scope="row">% active</th>
				
				<td><?php echo bp_system_report_percentage( $b_data->groups['active'][$type], $b_data->groups['types'][$type] ); ?></td>
				<td><?php echo bp_system_report_percentage( $a_data->groups['active'][$type] / $a_data->groups['types'][$type] ); ?></td>
				<td><?php bp_system_report_compare( bp_system_report_percentage( $a_data->groups['active'][$type] / $a_data->groups['types'][$type] ), bp_system_report_percentage( $b_data->groups['active'][$type] / $b_data->groups['types'][$type] ) ) ?></td>
			</tr>
		
			<tr>
				<th scope="row"><?php _e( "$type groups", 'bp-system-report' ) ?></th>
				<th scope="row">total group memberships</th>
				
				<td><?php echo $b_data->groups['members'][$type]; ?></td>
				<td><?php echo $a_data->groups['members'][$type]; ?></td>
				<td><?php bp_system_report_compare( $a_data->groups['members'][$type], $b_data->groups['members'][$type] ) ?></td>
			</tr>
			
			<tr>
				<th scope="row"><?php _e( "$type groups", 'bp-system-report' ) ?></th>
				<th scope="row">average group membership</th>
				
				<td><?php echo round( $b_data->groups['members'][$type] / $b_data->groups['types'][$type], 2 ) ?></td>
				<td><?php echo round ( $a_data->groups['members'][$type] / $a_data->groups['types'][$type], 2 ) ?></td>
				<td><?php bp_system_report_compare( $a_data->groups['members'][$type] / $a_data->groups['types'][$type], $b_data->groups['members'][$type] / $b_data->groups['types'][$type] ) ?></td>
			</tr>
			<?php endforeach; ?>	
		
			
				
		
	
			<tr class="bp-sr-type-label">
			
				<th scope="row" colspan=5>Blogs</th>
			
			</tr>
			
			<tr>
				<th scope="row"></th>
				<th scope="row">Total</th>
				
				<td><?php echo $b_data->blogs['total']; ?></td>
				<td><?php echo $a_data->blogs['total']; ?></td>
				<td><?php bp_system_report_compare( $a_data->blogs['total'], $b_data->blogs['total'] ) ?></td>
			</tr>
		
			<tr>
				<th scope="row"></th>
				<th scope="row"># active</th>
				
				<td><?php echo $b_data->blogs['total_active']; ?></td>
				<td><?php echo $a_data->blogs['total_active']; ?></td>
				<td><?php bp_system_report_compare( $a_data->blogs['total_active'], $b_data->blogs['total_active'] ) ?></td>
			</tr>
		
			<tr>
				<th scope="row"></th>
				<th scope="row">% active</th>
				
				<td><?php echo $b_data->blogs['percent_active']; ?></td>
				<td><?php echo $a_data->blogs['percent_active']; ?></td>
				<td><?php bp_system_report_compare( $a_data->blogs['percent_active'], $b_data->blogs['percent_active'] ) ?></td>
			</tr>
			
		</thead>

		</table>

	</div> */