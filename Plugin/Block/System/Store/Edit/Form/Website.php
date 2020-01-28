<?php

namespace Ppm\Fulfillment\Plugin\Block\System\Store\Edit\Form;

class Website extends \Magento\Backend\Block\System\Store\Edit\Form\Website
{
  /**
   * Get form HTML
   *
   * @return string
   */
  public function aroundGetFormHtml(
    \Magento\Backend\Block\System\Store\Edit\Form\Website $subject,
    \Closure $proceed
  )
  {
    $form = $subject->getForm();
    if (is_object($form)) {

      // From \Magento\Backend\Block\System\Store\Edit\Form\Website :
      $websiteModel = $this->_coreRegistry->registry("store_data");
      $postData = $this->_coreRegistry->registry("store_post_data");
      if ($postData) {
        $websiteModel->setData($postData["website"]);
      }

      // Fieldset name from \Magento\Backend\Block\System\Store\Edit\Form\Website
      $fieldset = $form->getElement("website_fieldset");
      $fieldset->addField(
        "ppm_api_key",
        "text",
        [
          "name" => "website[ppm_api_key]", // From \Magento\Backend\Block\System\Store\Edit\Form\Website
          "label" => __("PPM API Key"),
          "value" => $websiteModel->getData("ppm_api_key"),
          "title" => __("PPM API Key"),
          "required" => false,
        ]
      );
      $fieldset->addField(
        "ppm_owner_code",
        "text",
        [
          "name" => "website[ppm_owner_code]", // From \Magento\Backend\Block\System\Store\Edit\Form\Website
          "label" => __("PPM Owner Code"),
          "value" => $websiteModel->getData("ppm_owner_code"),
          "title" => __("PPM Owner Code"),
          "required" => false,
        ]
      );
      $fieldset->addField(
        "ppm_api_url",
        "text",
        [
          "name" => "website[ppm_api_url]", // From \Magento\Backend\Block\System\Store\Edit\Form\Website
          "label" => __("PPM API URL"),
          "value" => $websiteModel->getData("ppm_api_url"),
          "title" => __("PPM API URL"),
          "required" => false,
        ]
      );

      $subject->setForm($form);
    }

    return $proceed();
  }
}
