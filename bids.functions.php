<?php
/**
 * @package AuctionsFactory
 * @version 1.6.0
 * @copyright www.thefactory.ro
 * @license: commercial
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

 function resize_image($picture,$width,$height,$prefix){
 	//echo $GLOBALS['mosConfig_absolute_path'] . '/components/com_bids/class.imagetransform.php' ;exit;
	  require_once( $GLOBALS['mosConfig_absolute_path'] . '/components/com_bids/class.imagetransform.php' );

	  $imgTrans = new imageTransform();
	  $imgTrans->sourceFile = AUCTION_PICTURES_PATH."$picture";
	  $imgTrans->targetFile = AUCTION_PICTURES_PATH.$prefix."_".$picture;
	  $imgTrans->maintainAspectRatio=true;
	  $imgTrans->resizeToWidth = $width;
      $imgTrans->resizeToHeight = $height;
      $imgTrans->resizeIfGreater=true;
      if($imgTrans->resize()){
		@chmod($imgTrans->targetFile,0755);
		return $imgTrans->targetFile;
	  } else {
		return bid_err_loading_picture;
	  }
 }

function resize_to_filesize($file,$outputfile,$maxfilesize)
{
    require_once( $GLOBALS['mosConfig_absolute_path'] . '/components/com_bids/class.imagetransform.php' );
	@set_time_limit(1000);
	$imgTrans = new imageTransform();
    $imgTrans->sourceFile = $file;
    $imgTrans->targetFile = $outputfile;
    $imgTrans->maintainAspectRatio=true;

    $g=getimagesize($file);
    $siz=filesize($file);
    if (!$siz) return false;
    if ($siz<=$maxfilesize){
        return copy($file,$outputfile);
    }
    $w=$g[0];
    $h=$g[1];

    $maxiterations=20;
    $nriterations=0;
    $tolerance=50000;

    $resize_factor=$maxfilesize/$siz;
    do{
	    $imgTrans->resizeToWidth = $w*$resize_factor;
        $imgTrans->resizeToHeight = $h*$resize_factor;
        $imgTrans->resizeIfGreater=true;


        if($imgTrans->resize()){
        	@chmod($imgTrans->targetFile,0755);
        }

        clearstatcache  ();
        $newfilesize=filesize($outputfile);
        $diff=($maxfilesize-$newfilesize);
        if ($diff>0){
           $resize_factor+=0.05;
        }else{
           $resize_factor-=0.05;
        }
        $nriterations++;

    }while (abs($diff)>$tolerance && $nriterations<=$maxiterations && $resize_factor<1);

	return true;

}

function ImportFromCSV($option,$withids=0)
{
 	global $Itemid,$database,$mosConfig_absolute_path,$mosConfig_live_site,$my,$task;
 	require_once( $mosConfig_absolute_path . '/administrator/includes/pcl/pclzip.lib.php' );
	require_once( $mosConfig_absolute_path . '/administrator/includes/pcl/pclerror.lib.php' );
	$tmpdir 		= uniqid( 'images_' );
	$base_Dir 		= mosPathName( $mosConfig_absolute_path.'/media' );
	$extractdir 	= mosPathName( $base_Dir . $tmpdir );

	define('_TMP_DIR',$mosConfig_absolute_path."/media/");
	$msg="&mosmsg=";

	$auction = new mosBidOffers($database);

	$errors=array();

	if($_FILES['csv']['tmp_name']){
		$csv_fname = $_FILES['csv']['name'];
		$ext=substr($csv_fname, strrpos($csv_fname, '.')+1, strlen($csv_fname));
		 if($ext!="csv"){
		 	$msg .= bid_err_csv_ext_not_allowed;
		 }
	    $upload_path = _TMP_DIR.$csv_fname;

		if(!move_uploaded_file($_FILES['csv']['tmp_name'],$upload_path)){
			$msg .= bid_err_csv_import_error;
		}
		@chmod($upload_path,0777);
		if(!file_exists($upload_path)){
			$msg .= bid_err_file_not_there;
		}

		$handle = fopen($upload_path, "r");

		set_time_limit(0);
		$i=1;
		while (($data = fgetcsv($handle, 30000, "\t")) !== FALSE) {
		 //	var_dump($data);echo"<br>";
		 	$params['picture'] = $data[15];
		 	$params['add_picture'] = $data[16];
		 	$params['auto_accept_bin'] = $data[17];
		 	$params['bid_counts'] = $data[18];
		 	$params['max_price'] = $data[19];

		 	if (is_array( $params )) {
				$txt = array();
				foreach ( $params as $k=>$v) {
					$txt[] = "$k=$v";
				}
				$auction->params = implode( "\n", $txt );
			}
            $auction->id = null;
		 	$auction->title = strip_tags($data[1]);
		 	$auction->shortdescription =  strip_tags($data[2]);
		 	$auction->description = preg_replace('/<script[^>]*?>.*?<\/script>/si','',$data[3]); //de testat

		 	$auction->picture = $data[4];
		 	$auction->link_extern = $data[5];
		 	$auction->initial_price =floatval($data[6]);
	 	    if(is_numeric($data[7]))
		 	    $auction->currency = $data[7];
		 	else
		 	    $auction->SetCurrency($data[7]);
		 	$auction->BIN_price = floatval($data[8]);
		 	$auction->auction_type = $data[9];
	 		switch ($auction->auction_type){
	 			case 'public':
					$auction->auction_type = 1;
				break;
				case 'private':
					$auction->auction_type = 2;
				break;
	 		}
		 	$auction->automatic = $data[10];
		 	$auction->SetPaymentMethod($data[11]);
		 	$auction->shipment_info = $data[12];
		 	$auction->start_date = date("Y-m-d H:i:s",strtotime($data[13]));
		 	$auction->end_date = date("Y-m-d H:i:s",strtotime($data[14]));

		 	$auction->published = $data[20];
		 	$auction->SetCategory($data[21]);
            if ($withids!=0 && intval($data[0])>0)
 			    $auction->userid=$data[0];
		 	else
 			    $auction->userid=$my->id;

 			$err = $auction->check();
		 	if(count($err)<=0) {
		 		$auction->store();

		 		if($_FILES['arch']['tmp_name']){
		 			$upload_arch = $base_Dir.'/'.$_FILES['arch']['name'];
		 			if(!move_uploaded_file($_FILES['arch']['tmp_name'],$upload_arch)){
						$msg .= bid_err_csv_import_error;
					}
					@chmod($upload_arch,0777);

					$archivename 	= $upload_arch;

					@mkdir($extractdir);
					$archivename 	= mosPathName( $archivename, false );
					$zipfile = new PclZip( $archivename );

			 		if(substr(PHP_OS, 0, 3) == 'WIN') {
						define('OS_WINDOWS',1);
					} else {
						define('OS_WINDOWS',0);
					}
					//echo $archivename;
					$ret = $zipfile->extract( PCLZIP_OPT_PATH, $extractdir );
					if($ret == 0) {
						die( 'Unrecoverable error '.$zipfile->errorName(true).'"' );
					}

					$arr_pics = array();
					$k=22;
					$j=31;
					for($m=$k;$m<=$j;$m++){
						if($data[$m])
							$arr_pics[] = $data[$m];
					}


					//insert pics in db<br>
					if($auction->picture){
						if(file_exists($extractdir.$auction->picture)){
							$fname = $auction->picture;
							$ext=extract_file_ext($fname);
							if(!$auction->isAllowedImage($ext)){
					          $msg .= bid_err_not_allowed_ext.': '.$file['name'];
					        }else{
                               if(filesize($extractdir.$auction->picture)>bid_opt_max_picture_size*1024){
                                    $msg.=$auction->picture."- ".bid_err_imagesize_too_big."<br><br>";
                               }else{

            				        $file_name=$auction->id."_".$fname.'.'.$ext;
            						$path= AUCTION_PICTURES_PATH."$file_name";
                                    @unlink($path);
            						if(rename($mosConfig_absolute_path."/media/".$tmpdir."/".$auction->picture, $path)) {

                				    resize_image($file_name,bid_opt_thumb_width,bid_opt_thumb_height,'resize');
                				    resize_image($file_name,bid_opt_medium_width,bid_opt_medium_height,'middle');
            						$auction->picture=$file_name;
                                    $auction->store();
            					    }else{
            						     $msg.=$auction->picture."- ".bid_err_upload_failed."<br><br>";
            					    }
                               }
					        }
						}
					}
				    $nrfiles=0;
					for($m=0;$m<count($arr_pics);$m++){
					    if ($nrfiles>=bid_opt_maxnr_images) continue;
						if(file_exists($extractdir.$arr_pics[$m])) {
                           if(filesize($extractdir.$arr_pics[$m])>bid_opt_max_picture_size*1024){
                                $msg.=$arr_pics[$m]."- ".bid_err_imagesize_too_big."<br><br>";
                                continue;
                           }

							$fname = $arr_pics[$m];
							$ext=extract_file_ext($fname);
					        if(!$auction->isAllowedImage($ext)){
					          $msg .= bid_err_not_allowed_ext.': '.$file['name'];
					          continue;
					        }

					        $file_name=$fname.'.'.$ext;
							$pic=new mosBidPicture($database);
							$pic->id_offer=$auction->id;
							$pic->userid=$my->id;
							$pic->picture=$file_name;
							$pic->modified=date("Y-m-d H:i:s",time());
		          			$pic->store();

							$file_name=$auction->id."_img_$pic->id.$ext";
							$pic->picture=$file_name;
							$pic->store();

							$path= AUCTION_PICTURES_PATH."$file_name";
							if(rename($mosConfig_absolute_path."/media/".$tmpdir."/".$arr_pics[$m], $path)) {
            				    resize_image($file_name,bid_opt_thumb_width,bid_opt_thumb_height,'resize');
            				    resize_image($file_name,bid_opt_medium_width,bid_opt_medium_height,'middle');
		      				}else{
				    			$msg.=$arr_pics[$m]."- ".bid_err_upload_failed."<br><br>";
					       	}
					       	$nrfiles++;
						}
					}

		 		}//arch pics

		 	}else
		 		$errors[] ="$i : ".join('<br>',$err);

		 	$i++;
		 }
		if(file_exists($extractdir)){
		 deldir($extractdir);
		}
		 fclose($handle);
		if(file_exists($base_Dir.$_FILES['csv']['name']))
		 unlink($base_Dir.$_FILES['csv']['name']);
		if(file_exists($base_Dir.$_FILES['csv']['name']))
		 unlink($base_Dir.$_FILES['arch']['name']);


	}

	return $errors;
}
/**
 * @author: psorin
 *
 *
 */
function createXLS($result){

	global $database, $mosConfig_absolute_path;

	ob_clean ();
	ob_start ();

	set_time_limit ( 0 );

	require_once ($mosConfig_absolute_path.'/components/com_bids/Excel/Writer.php');
	global $mosConfig_absolute_path;

	require_once $mosConfig_absolute_path.'/components/com_bids/PEAR.php';

	// Creating a workbook
	$workbook = new Spreadsheet_Excel_Writer();

	$worksheet =& $workbook->addWorksheet('Exported Auctions');

	$BIFF = new Spreadsheet_Excel_Writer_BIFFwriter();

	$format = new Spreadsheet_Excel_Writer_Format($BIFF);
	$format->setBold(1);
	$format->setAlign('center');



	$worksheet->write( 0, 0, "User", $format );
	$worksheet->write( 0, 1, "Title" , $format );
	$worksheet->write( 0, 2, "Short description" );
	$worksheet->write( 0, 3, "Description" );
	$worksheet->write( 0, 4, "Picture" );
	$worksheet->write( 0, 5, "External Link" );
	$worksheet->write( 0, 6, "Initial price" );
	$worksheet->write( 0, 7, "Currency" );
	$worksheet->write( 0, 8, "BIN price" );
	$worksheet->write( 0, 9, "Auction type" );
	$worksheet->write( 0, 10, "Automatic" );
	$worksheet->write( 0, 11, "Payment" );
	$worksheet->write( 0, 12, "Shipment Info" );
	$worksheet->write( 0, 13, "Start date" );
	$worksheet->write( 0, 14, "End date" );
	$worksheet->write( 0, 15, "Param: picture" );
	$worksheet->write( 0, 16, "Param: add_picture" );
	$worksheet->write( 0, 17, "Param: auto_accept_bin" );
	$worksheet->write( 0, 18, "Param: bid_counts" );
	$worksheet->write( 0, 19, "Param: max_price" );
	$worksheet->write( 0, 20, "Published" );
	$worksheet->write( 0, 21, "Category" );

	for($i = 0; $i< count($result); $i++){

		$worksheet->write( $i+1, 0, $result[$i]->username );
		$worksheet->write( $i+1, 1, mosStripslashes($result[$i]->title) );
		$worksheet->write( $i+1, 2, mosStripslashes($result[$i]->shortdescription) );
		$worksheet->write( $i+1, 3, mosStripslashes($result[$i]->description) );
		$worksheet->write( $i+1, 4, $result[$i]->picture );
		$worksheet->write( $i+1, 5, $result[$i]->link_extern );
		$worksheet->write( $i+1, 6, $result[$i]->initial_price );
		$worksheet->write( $i+1, 7, $result[$i]->currency_name );
		$worksheet->write( $i+1, 8, $result[$i]->BIN_price );
 		switch ($result[$i]->auction_type){
 			case '1':
				$worksheet->write( $i+1, 9, 'public' );
			break;
			case '2':
				$worksheet->write( $i+1, 9, 'private' );
			break;
 		}
		$worksheet->write( $i+1, 10, $result[$i]->automatic );
		$worksheet->write( $i+1, 11, $result[$i]->payment_name );
		$worksheet->write( $i+1, 12, $result[$i]->shipment_info );
		$worksheet->write( $i+1, 13, $result[$i]->start_date );
		$worksheet->write( $i+1, 14, $result[$i]->end_date );

		$params = explode( "\n", $result[$i]->params );

		$tmp = explode( "=", $params[0]); // picture param
		$worksheet->write( $i+1, 15, $tmp[1] );

		$tmp = explode( "=", $params[1]); // add_picture
		$worksheet->write( $i+1, 16, $tmp[1] );

		$tmp = explode( "=", $params[2]); // auto_accept_bin
		$worksheet->write( $i+1, 17, $tmp[1] );

		$tmp = explode( "=", $params[3]); // bid_counts
		$worksheet->write( $i+1, 18, $tmp[1] );

		$tmp = explode( "=", $params[4]); // max_price
		$worksheet->write( $i+1, 19, $tmp[1] );


		$worksheet->write( $i+1, 20, $result[$i]->published );
		$worksheet->write( $i+1, 21, $result[$i]->cat );



		$worksheet->setColumn(0,0,9);
		$worksheet->setColumn(1,12,25);
	}
	$workbook->close();

	$attachment = ob_get_contents();

	@ob_end_clean();
	return $attachment;

}
if(!function_exists("deldir")){
function deldir( $dir ) {
	$current_dir = opendir( $dir );
	$old_umask = umask(0);
	while ($entryname = readdir( $current_dir )) {
		if ($entryname != '.' and $entryname != '..') {
			if (is_dir( $dir . $entryname )) {
				deldir( mosPathName( $dir . $entryname ) );
			} else {
                @chmod($dir . $entryname, 0777);
				unlink( $dir . $entryname );
			}
		}
	}
	umask($old_umask);
	closedir( $current_dir );
	return rmdir( $dir );
}
}
 function full_copy( $source, $target )
{
    if ( is_dir( $source ) )
    {
        @mkdir( $target );
        $d = dir( $source );
        while ( FALSE !== ( $entry = $d->read() ) )
        {
            if ( $entry == '.' || $entry == '..' )
            {
                continue;
            }

            $Entry = $source . '/' . $entry;
            if ( is_dir( $Entry ) )
            {
                full_copy( $Entry, $target . '/' . $entry );
                continue;
            }
            copy( $Entry, $target . '/' . $entry );
        }
        $d->close();
    }else
    {
        copy( $source, $target );
    }
}
function get_file_ext($fname)
{
    $ext="";
    if (strrpos($fname, '.'))
      $ext=substr($fname, strrpos($fname, '.')+1, strlen($fname));

    return $ext;
}
function extract_file_ext(&$fname)
{
    $ext="";
    if (strrpos($fname, '.')){
      $ext=substr($fname, strrpos($fname, '.')+1, strlen($fname));
      $fname=substr($fname,0, strrpos($fname, '.'));
    }
    return $ext;
}
function createBackupString($table)
{
    global $database;

    $database->setQuery("select * from $table");
	$rows = $database->loadObjectList();

	foreach($rows as $row)
    {
        $arr = mosObjectToArray($row);
        $fieldlist=array_keys($arr);

        $InsertDump = "INSERT INTO $table (".implode($fieldlist,',').") VALUES (";

        foreach($arr as $key => $value)
        {
            $value = addslashes( $value );
            $value = str_replace( "\n", '\r\n', $value );
            $value = str_replace( "\r", '', $value );
            $InsertDump .= "'$value',";
        }
        $file_tmp .= rtrim($InsertDump,',') . ");\n";
    }

    return $file_tmp;
}
if ( !function_exists('str_clean') ) {
	function str_clean( $string ) {
		$aToReplace = array(" ","/","&","ï","¿","½","!","$","%","@","?","#","(",")","+","*",":",";","'","\"");
		$aReplacements = array("-","-","and","");

		$str_buff = str_replace($aToReplace,$aReplacements,strtolower($string)	);
		return $str_buff;
	}
}
function detectIntegration()
{
    global $database,$cb_fieldmap,$mosConfig_absolute_path;
    //detect cb
    $database->setQuery("select count(*) from #__components where `option`='com_comprofiler'");
    if($database->loadResult()>0){
    	define('CB_DETECT',1);
    	$database->setQuery("select field,cb_field from #__bid_cbfields");
    	$r=$database->loadAssocList();
    	for($i=0;$i<count($r);$i++){
    	    $cb_fieldmap[$r[$i]['field']]=$r[$i]['cb_field'];
    	}
    }else {
        define('CB_DETECT',0);
    }
    //detect cb

    //detect virtuemart
    $database->setQuery("select count(*) from #__components where `option`='com_virtuemart'");
    if($database->loadResult()>0){
    	define('VM_DETECT',1);
    }else {
        define('VM_DETECT',0);
    }
    //detect virtuemart
    if (file_exists($mosConfig_absolute_path . '/administrator/components/com_joomfish/joomfish.php'))
    {
    	define('JOOMFISH_DETECT',1);
    }else {
        define('JOOMFISH_DETECT',0);
    }
    if (class_exists('JConfig')){
        define('JOOMLA_VERSION',5);
    }else{
        define('JOOMLA_VERSION',1);
    }
}
function makeCatTree(){
	global $database;
	$html_tree = array();
	$spacer = "&nbsp;&nbsp;&nbsp;";
	
	$cat = new mosBidCategories($database);	
	$cats = $cat->build_child(0, false);
	
	$nr_pcats = count($cats);
	if($nr_pcats>0){
		foreach($cats as $cIndex => $category){
			$leveledSpacer = "";
			for($i=0; $i<$category["depth"];$i++)
				$leveledSpacer .= $spacer;
 			$html_tree[] = mosHTML::makeOption($category["id"],$leveledSpacer.mosStripslashes($category["catname"]));
		}
	}
	return $html_tree;
}
function xmlGetAttr($el,$attr)
{
    foreach($el as $a1=>$a2){
        if ($a1==$attr){
            return $a2;
        }
    }
    return "";
}
function auctionDatetoIso($date)
{
    if (bid_opt_date_format=='Y-m-d'){
        return $date;
    }

    if (bid_opt_date_format=='Y-d-m'){
        preg_match("/([0-9]+)-([0-9]+)-([0-9]+)/",$date,$matches);
        return $matches[1]."-".$matches[3]."-".$matches[2];
    }
    if (bid_opt_date_format=='m/d/Y') {
        preg_match("/([0-9]+)\/([0-9]+)\/([0-9]+)/",$date,$matches);
        return $matches[3]."-".$matches[1]."-".$matches[2];
    }
    if (bid_opt_date_format=='d/m/Y') {
        preg_match("/([0-9]+)\/([0-9]+)\/([0-9]+)/",$date,$matches);
        return $matches[3]."-".$matches[2]."-".$matches[1];
    }
    return $date;
}

// 1.5.9
//this function checks if a category has children; it returns the children's number
function Bids_has_children($id){

	global $database;

	$database->setQuery("select count(*) from #__bid_categories where parent='".$id."'");
	$count = $database->loadResult();
	return $count;
}

function get_CustomPricesList($type){
	global $database;
	$_sql = " SELECT ag.id,ag.catname, ag.id as catid,cp.price ". 
	" FROM #__bid_custom_prices as cp LEFT JOIN #__bid_categories AS ag ON cp.category = ag.id ".
	" WHERE price_type='$type'";
	$database->setQuery($_sql);
	$list = $database->loadObjectList();
	//echo $database->_sql;
	return $list;
}

function makeCatTreeFiltered($custom_filter){
	global $database;
	$html_tree = array();
	$spacer = "&nbsp;&nbsp;&nbsp;";
	
	$cat = new mosBidCategories($database);	
	$cats = $cat->build_child(0, false,$custom_filter);
	
	$nr_pcats = count($cats);
	if($nr_pcats>0){
		foreach($cats as $cIndex => $category){
			$leveledSpacer = "";
			for($i=0; $i<$category["depth"];$i++)
				$leveledSpacer .= $spacer;
 			$html_tree[] = mosHTML::makeOption($category["id"],$leveledSpacer.mosStripslashes($category["catname"]));
		}
	}
	return $html_tree;
}


?>