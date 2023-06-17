<?php
class Kv_Vendor_LoginController extends Mage_Core_Controller_Front_Action
{
	public function indexAction()
    {
       	$this->loadLayout();
	   	$this->renderLayout();
    }

    public function testAction()
    {
        $this->loadLayout();
        $this->getLayout()->createBlock('bannerslider/default')->setTemplate('bannerslider/slider1.phtml')->setBannersliderId('your_bannerslider_id')->toHtml();
        $this->renderLayout();
    }


    public function loginAction()
    {
        try {

            $loginDetails = $this->getRequest()->getPost('login');
            if (!$this->getRequest()->isPost()) {
                throw new Exception("invalid Request", 1);
                
            }
            if (!$loginDetails) {
            Mage::getSingleton('core/session')->addError('Please enter your email address.');
            $this->_redirect('*/*/forgot');
            return;
             }
            $vendorData = Mage::getModel('vendor/vendor')->load($loginDetails['username'],'email');

            if(!$vendorData->getData())
            {
                throw new Exception("No account exists with this email address", 1);
            }

            if($vendorData->password == md5($loginDetails['password']))
            {
                if($vendorData->is_email_varified == 0)
                {
                    $value = Mage::helper('vendor')->getEmailConfirmationUrl($loginDetails['username']);
                    $message = Mage::helper('vendor')->__('This account is not confirmed. <a href="%s">Click here</a> to resend confirmation email.', $value);
                    Mage::getSingleton('core/session')->addError($message);
                    // $vendorData->sendMail($vendorData);
                    $vendorData->sendVerificationEmail($url);
                    echo "please varify yout email addresses";
                    

                }
                else
                {
                    echo "Login successfully,";
                }
            }
            else
            {
                echo "invalid password";
            }


            } catch (Exception $e) {
             Mage::getSingleton('core/session')->addError($e->getMessage());
             $this->_redirect('*/*/');
        }    
    }

    public function createaccountAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

        // public function registerAction()
        // {
            
        // }
    public function editAction()
    {
        $this->_title($this->__('Vendor'))
             ->_title($this->__('Vendors'))
             ->_title($this->__('Edit Vendor'));

        $id = $this->getRequest()->getParam('vendor_id');
        $model = Mage::getModel('vendor/vendor');
        $addressModel = Mage::getModel('vendor/vendor_address');

        if ($id)
        {
            $model->load($id);
            $addressModel->load($id,'vendor_id');
            if (! $model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('vendor')->__('This page no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }
        $this->_title($model->getId() ? $model->getTitle() : $this->__('New Vendor'));
        $this->_title($addressModel->getId() ? $addressModel->getTitle() : $this->__('New Vendor Address'));

        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);

        if (!empty($data)) 
        {
            $model->setData($data);
        }

        Mage::register('vendor_edit',$model);
        Mage::register('address_edit',$addressModel);

        $this->_initAction()
            ->_addBreadcrumb(
                $id ? Mage::helper('vendor')->__('Edit Vendor')
                    : Mage::helper('vendor')->__('New Vendor'),
                $id ? Mage::helper('vendor')->__('Edit Vendor')
                    : Mage::helper('vendor')->__('New Vendor'));

        $this->_addContent($this->getLayout()->createBlock('vendor/adminhtml_vendor_edit'))
                ->_addLeft($this->getLayout()->createBlock('vendor/adminhtml_vendor_edit_tabs'));

        $this->renderLayout();
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function saveAction()
    {
        try {
            echo "<pre>";
            print_r($_POST);
            // die();
            $model = Mage::getModel('vendor/vendor');
            $addressModel = Mage::getModel('vendor/vendor_address');
            $addressData = $this->getRequest()->getPost('address');
            $data = $this->getRequest()->getPost('vendor');
            $vendorId = $this->getRequest()->getParam('id');
            if (!$vendorId)
            {
                $vendorId = $this->getRequest()->getParam('vendor_id');
            }
            if($model->load($vendorId))
            {
                $oldPassword = $model->password;
            }

            $model->setData($data)->setId($vendorId);
            if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL)
            {
                $model->setCreatedTime(now())->setUpdateTime(now());
            } 
            if($vendorId)
            {
                $model->updated_at = now();
            }

            if($model->password == 'auto' )
            {
                $model->password = Mage::helper('core')->getRandomString(8);
                $model->password = md5($model->password);
                $model->sendMail($model);
            }
            if($model->password != $oldPassword)
            {
                $model->password = md5($model->password);
            }
            $model->save();
            if ($model->save())
            {
                if ($vendorId) {
                    $addressModel->load($vendorId,'vendor_id');
                }

                $addressModel->setData(array_merge($addressModel->getData(),$addressData));
                $addressModel->updated_at = now();
                $addressModel->vendor_id = $model->vendor_id;
                $addressModel->save();
            }
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('vendor')->__('Vendor was successfully saved'));
            Mage::getSingleton('adminhtml/session')->setFormData(false);
             
            if ($this->getRequest()->getParam('back')) {
                $this->_redirect('*/*/edit', array('id' => $model->getId()));
                return;
            }
            $this->_redirect('*/*/');
            return;
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            Mage::getSingleton('adminhtml/session')->setFormData($data);
            $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('vendor_id')));
            return;
        }

        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('vendor')->__('Unable to find vendor to save'));
        $this->_redirect('*/*/');
    }

    public function deleteAction()
    {
        if( $this->getRequest()->getParam('vendor_id') > 0 ) {
            try {
                $model = Mage::getModel('vendor/vendor');
                 
                $model->setId($this->getRequest()->getParam('vendor_id'))
                ->delete();
                 
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Vendor was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('vendor_id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction()
    {
        $vendorsIds = $this->getRequest()->getParam('vendor');
        if(!is_array($vendorsIds)) {
             Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select vendor(s).'));
        } else {
            try {
                $vendor = Mage::getModel('vendor/vendor');
                foreach ($vendorsIds as $vendorId) {
                    $vendor->reset()
                        ->load($vendorId)
                        ->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('Total of %d record(s) were deleted.', count($vendorsIds))
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }

        $this->_redirect('*/*/index');
    }

    public function massStatusUpdateAction()
    {
        $vendorsIds = $this->getRequest()->getParam('vendor');
        $status = $this->getRequest()->getParam('status');
        if(!is_array($vendorsIds)) {
             Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select vendor(s).'));
        } else {
            try {
                $vendor = Mage::getModel('vendor/vendor');
                foreach ($vendorsIds as $vendorId) {
                    $vendor->reset()->load($vendorId);
                    $vendor->status = $status;
                    $vendor->save();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('Total of %d record(s) were updated.', count($vendorsIds))
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }

        $this->_redirect('*/*/index');
    }

    public function stateAction()
    {
        $countryId = $this->getRequest()->getPost('country_id');
                // $countryId = 'US';


        $states = Mage::getModel('directory/region')->getResourceCollection()
            ->addCountryFilter($countryId)
            ->load()
            ->toOptionArray();
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(json_encode($states));      
    }
}