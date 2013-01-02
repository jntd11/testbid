<?php
function get_file_extension($path, $leading_dot = false) {
	$filename = basename($path);
	$dot_offset = (boolean) $leading_dot ? 0 : 1;

	if( ($pos = strrpos($filename, '.')) !== false ) {
		return substr($filename, $pos + $dot_offset, strlen($filename));
	} // if

	return '';
} // get_file_extension
function smarty_infobullet($params, &$smarty)
{
    $res="";
    if(!empty($params['text'])) {
        $res=mosToolTip($params['text']);
    }
    return $res;
}
function smarty_printdate($params, &$smarty)
{
    $res="";
    if(!empty($params['date'])) {
        $dateformat=bid_opt_date_format;
        if ($params['use_hour']) $dateformat.=" H:i";

        $res=date($dateformat,strtotime($params['date']));
    }
    return $res;

}
function smarty_set_css($params, &$smarty)
{
    global $mosConfig_live_site;
    $res="<link href=\"$mosConfig_live_site/components/com_bids/css/".bid_css."\" rel=\"stylesheet\" type=\"text/css\">\n";
	if (file_exists(BIDS_COMPONENT_PATH."/templates/bid_template.css")) {
        $res.="<link href=\"$mosConfig_live_site/components/com_bids/templates/bid_template.css\" rel=\"stylesheet\" type=\"text/css\">";
	}
    return $res;
}
?>