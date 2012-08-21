<?php

function bp_system_report_pseudo_cron() {
	$cool = new BP_System_Report( time() ); 
	$cool->bp_system_report_record();
}
add_action( 'bp_system_report_pseudo_cron_hook', 'bp_system_report_pseudo_cron' );

function bp_system_report_admin_add() {
	add_submenu_page( 'options-general.php',__('System Report','bp-system-report'), __('System Report','bp-system-report'), 'manage_options', __FILE__, 'bp_system_report_admin_screen' ); 
	add_action('admin_print_styles-','bp_system_report_css');
}
add_action( 'admin_menu', 'bp_system_report_admin_add', 70 );

function bp_system_report_css() {
	wp_enqueue_style( 'bp-system-report-css' );
}

function bp_system_report_get_info() {
	
	global $wpdb, $bp;
	
	// Total number of groups on the site
	$group_total_count = groups_get_total_group_count();
	
	//Total number of active BuddyPress members on the site
	$member_total_count = bp_total_member_count(); // possibly -  bp_get_total_member_count()
	
	// Total number of WordPress accounts
	$account_total_count = bp_get_total_site_member_count();

}


function bp_system_report_admin_screen() {

/**

	report_dates
	a
	bpsr_a
	a_data
	a_key
	b
	bpsr_b
	b_key

**/
	
	global $wpdb;

	if ( !$report_dates = get_option( 'bp_system_report_log' ) )
		$report_dates = array();
	
	$report_dates = array_reverse($report_dates);
	

	if ( !$a == isset($_POST['bpsr_a']) && $_POST['bpsr_a'] ) {
		$a = time();
		$a_data = new BP_System_Report( $a );
	} 
	else {
		$a_key = 'bp_system_report_' . $a;
		
		if ( !$a_data = get_option( $a_key ) )
			$a_data = "Error";
	}
	
	if ( !$b = isset($_POST['bpsr_b']) && $_POST['bpsr_b'] ) {
		$b = $report_dates[0];
	}
	
	$b_key = 'bp_system_report_' . $b;
	
	if ( !$b_data = get_option( $b_key ) )
		$b_data = "Error";
		
	// Moving admin output to this file	
	require( dirname( __FILE__ ) . '/bp-system-report-admin.php' );
	require( dirname( __FILE__ ) . '/bp-system-report-output.php' );

}

class BP_System_Report {
	var $members;
	var $groups;
	var $blogs;	
	var $date;

	function bp_system_report( $date ) {
	
		if ( !$report_dates = get_option( 'bp_system_report_log' ) )
			$last_report = time();
		else
			$last_report = array_pop( $report_dates );
		
		$counter = 0;
		$divideby = 0;
				
		/* Members */
		$members_array = bp_core_get_users( array( 'per_page' => 10000 ) );
		$m = array();
		$members = $members_array['users'];
		
		$m['total'] = count($members);
		
		$active_counter = 0;
		$friends_counter = 0;
		foreach( $members as $member ) {
	
			/* Active since last report */
			$last = strtotime($member->last_activity);
			$now = time();
			$since = $now - $last_report;

			if ( $now - $last < $since )
				$active_counter++;

			$friends_counter += (int)friends_get_total_friend_count( $member->id );
		}
		
		$m['total_active'] = $active_counter;
		$m['friendships'] = $friends_counter;
		$m['average_friendships'] = $friends_counter/$m['total'];
		$m['percent_active'] = bp_system_report_percentage($counter/$m['total']);
				
		$this->members = $m;		
		
		
		/* Groups */
		$groups_array = groups_get_groups( array( 'per_page' => 10000 ) );
		$m = array();
		$groups = $groups_array['groups'];
		
		$m['total'] = count($groups);
		
		$counter = 0;
		
		$type_counter = array('public' => 0, 'hidden' => 0, 'private' => 0);
		$active_counter = array('public' => 0, 'hidden' => 0, 'private' => 0);
		$member_counter = array('public' => 0, 'hidden' => 0, 'private' => 0);
		
		foreach( $groups as $group ) {
			$member_counter[$group->status] += $group->total_member_count;
			$type_counter[$group->status]++;

			/* Active since last report */
			$last = strtotime($group->last_activity);
			$now = time();
			$since = $now - $last_report;

			if ( $now - $last < $since )
				$active_counter[$group->status]++;

		}
		$m['members'] = $member_counter;
		$m['types'] = $type_counter;
		$m['active'] = $active_counter;
	//	$m['percent_active'] = bp_system_report_percentage($counter/$m['total']);
				
		$this->groups = $m;
		
		
		/* Blogs */
		$blogs_array = bp_blogs_get_blogs( array( 'per_page' => 10000 ) );
		$m = array();
		$blogs = $blogs_array['blogs'];
		
		$m['total'] = count($blogs);
		
		/* Active in last week */
		$counter = 1;
		
		foreach( $blogs as $blog ) {
			$last = strtotime($blog->last_activity);
			$now = time();
			if ( $now - $last < 604800 )
				$counter++;
		}
		$m['total_active'] = $counter;
		
		if ( $counter && $m['total'] = 0) {
		$divideby = 1;
		}
		
		$m['percent_active'] = bp_system_report_percentage($divideby);
				
		$this->blogs = $m;
		
		$this->date = time();
	}
	
	function bp_system_report_record() {
		if ( !get_option( 'bp_system_report_log' ) )
			$log = array();
		else
			$log = get_option( 'bp_system_report_log' );
			
		$time = time();
		$log[] = $time;
		
		if ( !update_option( 'bp_system_report_log', $log ) )
			return false;
		
		$name = 'bp_system_report_' . $time;
		
		if ( !add_option( $name, $this, '', 'no' ) )
			return false;
		
		return true;
	}
}

function bp_system_report_compare( $a, $b ) {
	
	if ( strpos( $a, '%' ) ) {
		$diff = (int)$a - (int)$b;
		
		if ( $diff > 0 )
			$diff = '+' . $diff;
			
		if ( $diff == 0 )
			$diff = '-';
		else
			$diff .= '%';
		
		$pct_diff = '';
	} else {
		$diff = round( $a - $b, 2 );
		if ( $diff > 0 )
			$diff = '+' . $diff;
		
		if ( $b == $a ) {
			$pct_diff = ' / -';
		} else if ( $b == 0 ) {
			$pct_diff = ' / -';
		} else {
			$pct_diff = ' / ' . bp_system_report_percentage( $diff/$b );
		}
		
	}

	echo $diff . $pct_diff;
}

function bp_system_report_percentage( $n ) {
	$n = $n * 100;
	$n .= '%';
	return round($n, 2) . '%';
}

function bp_system_report_format_date( $timestamp ) {
	$today = strftime( "%e %h %G" );
	
	$thedate = strftime( "%e %h %G", $timestamp );
	
	if ( $today == $thedate )
		$date = __( 'Today', 'bp-system-report' );
	else
		$date = $thedate;
	
	return $date . ' ' . strftime( "%R", $timestamp );
}