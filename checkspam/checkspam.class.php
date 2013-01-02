<?php
/**
 * Project: CheckSpam
 * Version: 1.6
 * File: 	checkspam.class.php
 * Author: 	Giuseppe Leone (joseph@masterdrive.it)
 * URL: 	http://www.masterdrive.it
 * Note:	Class that prevents Spam attacks 
 * License:	GPL 2
 * Lastupd:	06 / 17 / 2007
 */

class checkspam {
	/**
	 * Main setting parameters
	 */
	var $session_start = true; // Start or continue sessions
	var $style = array(); // Define the main graphical settings for the non-graphic mode (CSS Style)
	var $path = "./"; // Define the path of the class (as default value have the main document's root)
	
	var $imagetext; // Image/Text verification content
	var $tmp_img; // Temporary Image name
	var $tmp_char; // Temporary character image
	var $graphic; // Set the graphical mode to on, if the GD library is available with PNG support
	var $sess_name; // The name of the session
	var $sess_value; // The value of the session (SECRET CODE)
	var $sess_counter = 0; // Number of session created in the same document
	var $gd_info = array(); // Informations about GD Library
	var $font = array(); // The array font PNG images
	var $font_map = array(); // The Font map
	
	// Logs are stored into this variable
	var $log = "LOG STARTS HERE:\n-----\n";
	
	/**
	 * Constructor
	 */
	function __construct(){
		// Main directory
		$this->path = BIDS_COMPONENT_PATH.DS."/checkspam/";
		// Delete the old images
		$this->free();
		
		// Initialize the default styles for the text mode verification
		$this->style['text-align'] = "center";
		$this->style['font-size'] = "20";
		$this->style['font-family'] = "courier, verdana, arial";
		$this->style['color'] = "#FF0000";
		$this->style['background-color'] = "#F5F5F5";
		$this->style['border-width'] = "2px";
		$this->style['border-style'] = "solid";
		$this->style['border-color'] = "#FFCC00";
		$this->style['width'] = "80px";
		$this->style['line-height'] = "20px";
		
		// Building the map's font
		// The character font (from a to z, ASCII Table)
		$x = 0;
		for ($i = 97; $i <= 122; $i++){
			$this->font_map[chr($i)] = $x;
			//Next character
			$x += 40;
		}
		// The numeric values
		for ($i = 0; $i <= 9; $i++){
			$this->font_map[$i] = $x;
			//Next character
			$x += 40;
		}
	}
	
	/**
	 * Method to delete the old temporary verification Images
	 */
	function free(){
		// Delete the all old temporary images
		if(glob($this->path."images/*.png"))
		foreach(glob($this->path."images/*.png") as $tmp)
			unlink($tmp);
	}
	
	/**
	 * Register a new session Session
	 */
	function register_session(){
		// Register a new session with new value
		$this->sess_name = "checkSpam".session_id().$this->sess_counter;
		$this->sess_value = $this->create_secretcode();
		
		$_SESSION[$this->sess_name] = $this->sess_value;
		$this->write_log("Session: $this->sess_name registered successfully with the value of: $this->sess_value !");
		
		// Increment session counter
		$this->sess_counter++;
	}
	
	/**
	 * Starts generate the secret code
	 */
	function create_secretcode(){
		return substr(md5(rand()),0,5);
	}
	
	/**
	 * Generate the temporary PNG image verfication
	 */
	function create_image(){
		// Create the main image
		$this->imagetext = imagecreatetruecolor(200,60);
		imagefilledrectangle($this->imagetext,0,0,200,60,imagecolorallocate($this->imagetext,rand(120,255),rand(120,255),rand(120,255)));
		
		// Adding some backgrounds distortion objects
		for ($i = 1; $i < 10; $i++) {
			imagefilledrectangle($this->imagetext,rand(0,100),rand(0,30),rand(100,200),rand(30,60),imagecolorallocate($this->imagetext,rand(120,255),rand(120,255),rand(120,255)));
			imagefilledellipse($this->imagetext,rand(0,200),rand(0,60),rand(25,50),rand(25,50),imagecolorallocate($this->imagetext,rand(120,255),rand(120,255),rand(120,255)));
		}
		
		//Load the fonts
		$font_dir = opendir($this->path."font");
		while ( ($font = readdir($font_dir)) !== false ) {
			// Some file controls
			if ( is_file($this->path."font/".$font) and (strtolower(substr($font, strlen($font) - 3, 3)) == "png") ) {
				$this->font[] = imagecreatefrompng($this->path."font/".$font);
				$this->write_log("Loaded PNG Image font: ".$font);
			}
		}
		closedir($font_dir);
		
		// Add the secret code
		$x = 0;	// Distance from each character
		for($i = 0; $i < strlen($this->sess_value); $i++) {
			// Create the temporary character image
			$this->tmp_char = imagecreatetruecolor(40,40);
			
			// Take the character and do some distortion effects
			imagecopy($this->tmp_char,$this->font[rand(0,count($this->font) - 1)],0,0,$this->font_map[$this->sess_value{$i}],0,40,40);
			imagetruecolortopalette($this->tmp_char,false,32); // Convert a true color image character to a palette image with 32 colors
			imagecolortransparent($this->tmp_char,1); // Set transparent for the background color white
			imagecolorset($this->tmp_char,0,rand(0,200),rand(0,200),rand(0,200)); // Random color for the temporary character
			
			// Copy the resized temporary character into the final image
			imagecopyresized($this->imagetext, $this->tmp_char, $x, rand(0,30), 0, 0, rand(20,60), rand(20,60), 40, 40);
			
			// Go to the next character
			$x += 40;
			
			// Destroy temporary character image
			imagedestroy($this->tmp_char);
		}
		
		// Adding noise
		$noise_color = imagecolorallocate($this->imagetext,255,255,255);
		for ($i = 0; $i <= 250; $i++) {
			imagesetpixel($this->imagetext,rand(0,200),rand(0,60),$noise_color);
		}
		
		// Save the temporary image into the path of the class
		$this->tmp_img = $this->path."images/".$this->sess_name.time();
		imagepng($this->imagetext,$this->tmp_img.".png");
	}
	
	/**
	 * Build new image/text verification
	 */
	function create_imagetext(){
		// Check if the graphic mode is enabled or the GD Library Extension is available
		if($this->graphic && $this->check_gdlibrary()){
			// Call the function to create the temporary image verfication
			$this->create_image();
			$this->imagetext = "<img src=\"".$this->tmp_img.".png\" border=\"0\" alt=\"secret code\" title=\"secret code\" />";
			$this->write_log("Starts create IMAGE verification !");
		}else{
			// Text mode
			$this->imagetext = "<div style=\"";
			foreach($this->style as $key => $value){
				$this->imagetext .= "$key:$value; ";
			}
			$this->imagetext .= "\">$this->sess_value</div>";
			$this->write_log("Starts create TEXT verification !");
		}
	}
	
	/**
	 * Check if the GD library is available
	 * We can extends this methods to permit  many other  types of image format, such as JPEG, GIF and much more...
	 */
	function check_gdlibrary(){
		if(function_exists('gd_info')){
			$this->gd_info = gd_info();
			if($this->gd_info['PNG Support']){
				$this->write_log("GD Library Extension are available, version: ".$this->gd_info['GD Version']." with PNG Support !");
				return true;
			}else{
				$this->write_log("GD Library Extension are available, version: ".$this->gd_info['GD Version']." without PNG Support !");
				return false;
			}
		}else{
			$this->write_log("GD Library Extension are not available !");
			return false;
		}
	}
	
	/**
	 * Private method to write to the log
	 * @param string $message		Message to write to the log
	 */
	function write_log($message){
		if(strlen($message) > 0)
			$this->log .= date("[m d Y - H:i]")." $message\n";
	}
	
	/**
	 * Starts or continues Session
	 */
	function init_session(){
		if(!session_id() && $this->session_start){
			session_start();
			$this->write_log("Sessions started !");
		}else{
			$this->write_log("Sessions is already started !");
		}
	}
	
	/**
	 * Execute the complete procedure to create an anti-spam control
	 * @param char $type	(0 = Text | 1 = Image)	The type of verification
	 */
	function exec_checkspam($type = 1){
		// Set the type of verification
		$this->graphic = ($type == 1) ? true : false;
		// Try to init or continue a session
		$this->register_session();
		// Starts building new code image/text verification
		$this->create_imagetext();
	}
	
	/**
	 * Method to print out the Image/Text verification
	 */
	function print_imagetext(){
		echo $this->imagetext;
	}
	
	/**
	 * Method to print out the Input Form
	 * @param string $params	Some paramters for the input form
	 */
	function print_input($params = "name=\"secretcode\""){
		// Check if exist an image / text code verification to input
		if($this->sess_counter != 0){
			echo "<input type=\"hidden\" name=\"checkspam\" value=\"$this->sess_name\" />";
			echo "<input type=\"text\" $params maxlength=\"".strlen($this->sess_value)."\" />";
			$this->write_log("Input form printed out for: $this->sess_name !");
		}else{
			$this->write_log("Image or text does not exist for the verification of the code for: $this->sess_name !");
		}
	}
	
	/**
	 * Method to verify the code sent by the form
	 * @param string $code		The code sent by the form
	 */
	function verify($code){
		// Try to retrieve information about the input
		if(isset($_POST["checkspam"])){
			// Work with POST values
			$this->sess_value = $_SESSION[$_POST["checkspam"]];
		}else{
			if(isset($_GET["checkspam"])){
				// Work with GET values
				$this->sess_value = $_SESSION[$_GET["checkspam"]];
			}else{
				// No data to check
				return false;
			}
		}
		// Check the code
		return ($code == $this->sess_value) ? true : false;
	}
	
	/**
	 * Method to print out the log
	 */
	function print_log(){
		//Print out the log var content
		echo "<pre style=\"color:#CCCCCC;background-color:#000000;padding:3px;\">$this->log</pre>";
	}
}
?>
