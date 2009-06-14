<?php

/***************************************************************
*  Copyright notice
*
*  (c) 2009 Toni Wenzel <toni.wenzel@exsportance.de>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

require_once(PATH_tslib.'class.tslib_pibase.php');


/**
 * Plugin 'selectfirstcategory' for the 'commerce_selectfirstcategory' extension.
 *
 * @author      Toni Wenzel <toni.wenzel@exsportance.de>
 * @package    TYPO3
 * @subpackage    tx_commerce_selectfirstcategory
 */

class tx_commerce_selectfirstcategory_pi1 extends tslib_pibase {
	
	var $prefixId = "tx_commerce_selectfirstcategory";        // Same as class name
    var $scriptRelPath = "pi1/class.tx_commerce_selectfirstcategory_pi1.php";    // Path to this script relative to the extension dir.
    var $extKey = "commerce_selectfirstcategory";    // The extension key.
	var $pi_checkCHash = true;

	/**
     * Main method 
     *
     * @param    string        $content: The content of the PlugIn
     * @param    array        $conf: The PlugIn Configuration
     * @return    The content that should be displayed on the website
     */
    function main($content,$conf) 
	{
		debug($content,'content');
		
		debug($this->cObj->currentRecord,'curentrecord');
		 /*if (strstr($this->cObj->currentRecord,"tt_content")) {
$l = t3lib_div::getIndpEnv('TYPO3_SITE_URL').$this->pi_getPageLink($this->internal["currentRow"]["page"]);

		if((($GLOBALS['TSFE']->loginUser ? 'in' : 'out') == $this->cObj->data['loginstatusredirect_status']) && is_numeric($this->cObj->data['loginstatusredirect_pid'])){
            header('Location: '.t3lib_div::locationHeaderUrl($GLOBALS['TSFE']->absRefPrefix.$this->cObj->getTypoLink_URL($this->cObj->data['loginstatusredirect_pid'])));
        }*/

		return 'Hello World!<HR>
            Here is the TypoScript passed to the method:'.
                    t3lib_div::view_array($conf);

		
		return $content;
	}


}

if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/commerce_selectfirstcategory/pi1/class.tx_commerce_selectfirstcategory_pi1.php"])    {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/commerce_selectfirstcategory/pi1/class.tx_commerce_selectfirstcategory_pi1.php"]);
}


?>