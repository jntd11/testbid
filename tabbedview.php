<?php
/**
 * @package AuctionsFactory
 * @version 1.6.0
 * @copyright www.thefactory.ro
 * @license: commercial
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

    function smarty_createtab($params, &$smarty)
    {
   		global $mosConfig_live_site;
   		$res="<link id=\"luna-tab-style-sheet\" type=\"text/css\" rel=\"stylesheet\" href=\"" . $mosConfig_live_site. "/includes/js/tabs/tabpane.css\" />";
		/* JaiStartC */
		/* Commented to avoid tab title on the tab */
		$res.="<script type=\"text/javascript\" src=\"". $mosConfig_live_site . "/includes/js/tabs/tabpane_mini.js\"></script>";
		/* JaiEndC */
		return $res;
    }

    function smarty_startpane($params, &$smarty)
    {
        if (!empty($params['id'])){
           $useCookies = (empty($params['usecookies']))?0:$params['usecookies'];

		   $res = "<div class=\"tab-page\" id=\"".$params['id']."\">";
		   $res .= "<script type=\"text/javascript\">\n";
		   $res .= "	var tabPane1 = new WebFXTabPane(document.getElementById( \"".$params['id']."\" ), ".$useCookies." )\n";
    	   $res .= "</script>\n";
        }
        return $res;
    }
    function smarty_endpane($params, &$smarty)
    {
       return "</div>";
    }
    function smarty_starttab($params, &$smarty)
    {
        $paneid=$params['paneid'];
        $tabText=$params['text'];
		/* JaiStartC */
		$hidedetails = $params['hidedetails']; 
		/* JaiEndC */
		$res= "<div class=\"tab-page\" id=\"".$paneid."\">";
		/* JaiStartC */
		/* Not Displayed to avoid tab title on the tab */
		if($hidedetails == "1") {
			$res.="<h2 class=\"tab\" style=\"display:none;\"></h2>";
		}
		else {
			$res.="<h2 class=\"tab\">".$tabText."</h2>";
		}
		/* JaiEndC */
		$res.="<script type=\"text/javascript\">\n";
		$res.= "  tabPane1.addTabPage( document.getElementById( \"".$paneid."\" ) );";
		$res.= "</script>";
        return $res;
    }
    function smarty_endtab($params, &$smarty)
    {
       return "</div>";
    }

?>