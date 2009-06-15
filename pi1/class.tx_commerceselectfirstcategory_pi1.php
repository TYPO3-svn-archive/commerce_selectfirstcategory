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
require_once(t3lib_extmgm::extPath('commerce').'lib/class.tx_commerce_navigation.php');
require_once(t3lib_extmgm::extPath('commerce').'lib/class.tx_commerce_category.php');

/**
 * Plugin 'commerce_selectfirstcategory' for the 'commerce_selectfirstcategory' extension.
 *
 * @author	Toni Wenzel <toni.wenzel@exsportance.de>
 * @package	TYPO3
 * @subpackage	tx_commerceselectfirstcategory
 */
class tx_commerceselectfirstcategory_pi1 extends tslib_pibase {
	var $prefixId      = 'tx_commerceselectfirstcategory_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_commerceselectfirstcategory_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'commerce_selectfirstcategory';	// The extension key.
	var $pi_checkCHash = true;
	
	var $navigation; // commerce navigation object
	var $checkSubCategories = false;	
	
	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function main($content, $conf)	
	{	
		// store configuration
		$this->conf = $conf;		
	
		$this->pi_setPiVarDefaults();
	
		// check the config
		$this->checkConfig();
			
		// nur für die aktivierte Produkt-Seite
		if ($this->isEnabled())
		{														
			// check category
			$newCategory = $this->checkCurrentCategory();
			
			// page must be relocated
			if ($newCategory > 0)
			{				
				// get new url
				$url = $this->getUrl($newCategory);
				
				// relocate
				header('Location: '.$url);	
				
				exit;			
			}
		}
		
		return $content;
	}
	
	/**
	 * Creates the url to new category
	 * @return 
	 * @param int $catUid the category id
	 */
	function getUrl($catUid)
	{		
		$str = $this->pi_linkTP('',Array('tx_commerce_pi1'=> Array('catUid' => $catUid)),true,0);		
		
		$str = $this->cObj->lastTypoLinkUrl;		
		
		$result = t3lib_div::locationHeaderUrl($GLOBALS['TSFE']->absRefPrefix.$str);
		
		return $result;
	}
	
	/**
	 * 
	 * @return integer	Returns a category Id if page muste be relocated
	 */
	function checkCurrentCategory()
	{
		$result = 0;
		
		// get url params
		$gpVars = t3lib_div::GParrayMerged('tx_commerce_pi1');
		
		// extract current category
		$catUid = intval($gpVars['catUid']);

		//if no category is provided get root category
		if ($catUid == 0)
		{
			if ($GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_commerce_pi1.']['catUid'])
			{
				$catUid = $this->getFirstCategory(false);
				
				// set result to relocate
				$result = $catUid;
			}
		}
		
		// check if sub categories exists
		if ($catUid != 0 && $this->checkSubCategories)
		{			
			$category = $this->getFirstCategory(true,$catUid);
			
			if ($catUid != $category)
				$result = $category;
		}
		
		return $result;
	}
	
	/**
	 * Returns the first Subcategory
	 * @return integer
	 */
	function getFirstCategory($recusive = false, $parent = 0)
	{
		// get root category
		if ($parent == 0)
			$parent = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_commerce_pi1.']['catUid'];
	
		$result = $parent;
			
		// create category object
		$CategoryObject= t3lib_div::makeInstance('tx_commerce_category');
		
		// init
		$CategoryObject->init($parent,$GLOBALS['TSFE']->sys_language_uid);
		
		// load data
		$CategoryObject->load_data();
		
		// get childs
		$childs = $CategoryObject->getCategoryUids();		
		
		// check if childs exists
		if (count($childs) > 0)
		{
			$result = $childs[0];

			// check if sub categories exists
			if ($result != 0 && $recusive && $this->checkSubCategories)
			{
				$result = $this->getFirstCategory(true, $result);
			}
		}
		
		return $result;
	}
	
	/**
	 * Checks if plugin is enabled for current page
	 */
	function isEnabled()
	{
		$currentUid= $GLOBALS['TSFE']->id;
		$enabledIds = array();
		
		if ($this->conf['productPage'])
		{
			$enabledIds = explode(',',$this->conf['productPage']);
		}
		elseif($GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_commerce_pi1.']['overridePid'])	
		{
			$enabledIds = array($GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_commerce_pi1.']['overridePid']);
		}	
		
		// if showUid exists in url, this extension should not be perform
		$gpVars = t3lib_div::GPvar('tx_commerce_pi1');		
		
		if ($gpVars && is_array($gpVars))
		{
			if (array_key_exists('showUid', $gpVars))
				return false;
		}
		
		// check if current id exists in array
		return in_array($currentUid, $enabledIds);
	}
	
	function checkConfig()
	{
		// check if subcategories is enabled
		if ($this->conf['checkSubCategories'])
			$this->checkSubCategories = $this->conf['checkSubCategories'] == 1;
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/commerce_selectfirstcategory/pi1/class.tx_commerceselectfirstcategory_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/commerce_selectfirstcategory/pi1/class.tx_commerceselectfirstcategory_pi1.php']);
}

?>