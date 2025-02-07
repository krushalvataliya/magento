<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @banner    Mage
 * @package     Mage_Adminhtml
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (http://www.magento.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Customer edit block
 *
 * @banner   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Kv_Banner_Block_Adminhtml_Bannergroup_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{

    public function __construct()
    {

        $this->_objectId   = 'group_id';
        $this->_blockGroup = 'banner';
        $this->_controller = 'adminhtml_bannergroup';
        $this->_headerText = Mage::helper('banner')->__('Manage banner group');

        parent::__construct();
        // if ($this->_isAllowedAction('save')) {

            $this->_updateButton('save', 'label', Mage::helper('banner')->__('Save banner group'));
            $this->_addButton('saveandcontinue', array(
            'label' => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save',
        ), -100);

        $this->_formScripts[] = " function saveAndContinueEdit(){
                    editForm.submit($('edit_form').action+'back/edit/');
                }
            ";
        // } else {
        //     $this->_removeButton('save');
        // }

        if ($this->_isAllowedAction('delete')) {
            $this->_updateButton('delete', 'label', Mage::helper('group')->__('Delete banner'));
        } else {
            $this->_removeButton('delete');
        }
    }

    /**
     * Retrieve text for header element depending on loaded page
     *
     * @return string
     */
    public function getHeaderText()
    {
        // if (Mage::registry('banner_edit')->getId()) {
            return Mage::helper('banner')->__("Edit banner group");
        // }
        // else {
        //     return Mage::helper('banner')->__('New banner');
        // }
    }

    /**
     * Check permission for passed action
     *
     * @param string $action
     * @return bool
     */
    protected function _isAllowedAction($action)
    {
        return Mage::getSingleton('admin/session')->isAllowed('banner/adminhtml_bannergroup/' . $action);
    }

    /**
     * Getter of url for "Save and Continue" button
     * tab_id will be replaced by desired by JS later
     *
     * @return string
     */
    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('*/*/save', array(
            '_current'   => true,
            'back'       => 'edit',
            'active_tab' => '{{tab_id}}'
        ));
    }

    /**
     * Prepare layout
     *
     * @return Mage_Core_Block_Abstract
     */
    // protected function _prepareLayout()
    // {
    //     $tabsBlock = $this->getLayout()->getBlock('cms_page_edit_tabs');
    //     if ($tabsBlock) {
    //         $tabsBlockJsObject = $tabsBlock->getJsObjectName();
    //         $tabsBlockPrefix   = $tabsBlock->getId() . '_';
    //     } else {
    //         $tabsBlockJsObject = 'page_tabsJsTabs';
    //         $tabsBlockPrefix   = 'page_tabs_';
    //     }

    //     $this->_formScripts[] = "
    //         function toggleEditor() {
    //             if (tinyMCE.getInstanceById('page_content') == null) {
    //                 tinyMCE.execCommand('mceAddControl', false, 'page_content');
    //             } else {
    //                 tinyMCE.execCommand('mceRemoveControl', false, 'page_content');
    //             }
    //         }

    //         function saveAndContinueEdit(urlTemplate) {
    //             var tabsIdValue = " . $tabsBlockJsObject . ".activeTab.id;
    //             var tabsBlockPrefix = '" . $tabsBlockPrefix . "';
    //             if (tabsIdValue.startsWith(tabsBlockPrefix)) {
    //                 tabsIdValue = tabsIdValue.substr(tabsBlockPrefix.length)
    //             }
    //             var template = new Template(urlTemplate, /(^|.|\\r|\\n)({{(\w+)}})/);
    //             var url = template.evaluate({tab_id:tabsIdValue});
    //             editForm.submit(url);
    //         }
    //     ";
    //     return parent::_prepareLayout();
    // }
}
