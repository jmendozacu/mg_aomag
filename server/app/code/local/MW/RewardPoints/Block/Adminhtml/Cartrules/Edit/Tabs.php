<?php

class MW_RewardPoints_Block_Adminhtml_Cartrules_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('cart_rules_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('rewardpoints')->__('Shopping Cart Earning Rule'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_program_detail', array(
          'label'     => Mage::helper('rewardpoints')->__('Rule Information'),
          'title'     => Mage::helper('rewardpoints')->__('Rule Information'),
          'content'   => $this->getLayout()->createBlock('rewardpoints/adminhtml_cartrules_edit_tab_form')->toHtml(),
          'active'    =>true,
      ));
      $this->addTab('form_conditions', array(
          'label'     => Mage::helper('rewardpoints')->__('Conditions'),
          'title'     => Mage::helper('rewardpoints')->__('Conditions'),
          'content'   => $this->getLayout()->createBlock('rewardpoints/adminhtml_cartrules_edit_tab_conditions')->toHtml(),
      ));
      $this->addTab('form_actions', array(
          'label'     => Mage::helper('rewardpoints')->__('Actions'),
          'title'     => Mage::helper('rewardpoints')->__('Actions'),
          'content'   => $this->getLayout()->createBlock('rewardpoints/adminhtml_cartrules_edit_tab_actions')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}