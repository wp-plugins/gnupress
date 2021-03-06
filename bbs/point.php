<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

//wp-super-cache 사용시 이 페이지는 cache하지 않음
if ( ! defined( 'DONOTCACHEPAGE' ) ) {
    define( 'DONOTCACHEPAGE', true );
}

global $gnupress;

if ( $is_guest ){
    $msg = __('Only members can view.', G5_NAME);
    if( $gnupress->window_open ){
        g5_alert_close($msg);
    } else {
        $this->error_display_print((array) $msg);
        return;
    }
}

$g5['title'] = $member['user_display_name'].' '.__('- point history', G5_NAME);

$list = array();

$sql_common = $wpdb->prepare(" from {$g5['point_table']} where user_id = '%s' ", $member['user_id']);
$sql_order = " order by po_id desc ";

$sql = " select count(*) as cnt {$sql_common} ";
$total_count = $wpdb->get_var($sql);

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * {$sql_common} {$sql_order} limit {$from_record}, {$rows} ";
$rows = $wpdb->get_results($sql, ARRAY_A);

include_once($member_skin_path.'/point.skin.php');
?>