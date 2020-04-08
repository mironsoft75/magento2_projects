<?php

namespace MageArray\News\Controller\Comment;

use MageArray\News\Controller\Comment;
use Magento\Framework\App\Area;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\Store;

/**
 * Class Save
 * @package MageArray\News\Controller\Comment
 */
class Save extends Comment
{
    /**
     * @return $this
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('newspost_id');
        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();
        $postModel = $this->_newspostFactory->create();
        $postModel->load($id);
        if ($data && $data['comment']) {
            $model = $this->_newscommentFactory->create();
            $autoConfirm = $this->_dataHelper
                ->getStoreConfig('magearray_news/comments/autoapprove');
            $loginApprove = $this->_dataHelper
                ->getStoreConfig('magearray_news/comments/loginapprove');
            $sendEmail = $this->_dataHelper
                ->getStoreConfig('magearray_news/comments/emailoncomment');
            $adminEmail = $this->_dataHelper
                ->getStoreConfig('magearray_news/comments/admin_email');
            $useCaptcha = $this->_dataHelper
                ->getStoreConfig('magearray_news/comments/use_captcha');
            $googleSecretKey = $this->_dataHelper
                ->getStoreConfig('magearray_news/comments/google_secret_key');
            $captchaFlag = false;
            if ($useCaptcha == 1) {
                $captchaFlag = true;
                if (isset($data['g-recaptcha-response'])) {
                    $gUrl = "https://www.google.com/recaptcha/api/siteverify?";
                    $response = $data['g-recaptcha-response'];
                    $remoteIp = $this->_remoteAddress->getRemoteAddress();
                    $gParams = "secret=" . $googleSecretKey .
                        "&response=" . $response .
                        "&remoteip=" . $remoteIp . "";
                    $googleResponse = @file_get_contents($gUrl . $gParams);
                    $response = json_decode($googleResponse, true);
                    if ($response['success'] != false) {
                        $captchaFlag = false;
                    }
                }
            }
            if ($captchaFlag == true) {
                $this->messageManager->addError(
                    __('Incorrect CAPTCHA.')
                );
                return $resultRedirect->setUrl(
                    $this->_redirect->getRefererUrl()
                );
            }
            $loggedIn = $this->_customerdata->isLoggedIn();
            if ($autoConfirm == 1 || ($loggedIn == 1 && $loginApprove == 1)) {
                $approveStatus = 1;
            } else {
                $approveStatus = 2;
            }

            $data['commented_date'] = date('Y-m-d');
            $data['comment_status'] = $approveStatus;
            $model->setData($data);
            try {
                $model->save();
                if ($sendEmail == 1) {
                    $post['name'] = $data['sender_name'];
                    $post['email'] = $data['sender_email'];
                    $post['comment'] = $data['comment'];
                    $post['title'] = $postModel->getTitle();
                    $this->_inlineTranslation->suspend();
                    $postObject = new DataObject();
                    $postObject->setData($post);
                    $sender = [
                        'name' => $this->_escaper
                            ->escapeHtml($post['name']),
                        'email' => $this->_escaper
                            ->escapeHtml($post['email']),
                    ];
                    $transport = $this->_transportBuilder
                        ->setTemplateIdentifier(
                            'magearray_news_comments_email_template'
                        )
                        ->setTemplateOptions(
                            [
                                'area' => Area::AREA_FRONTEND,
                                'store' => Store::DEFAULT_STORE_ID,
                            ]
                        )
                        ->setTemplateVars(['data' => $postObject])
                        ->setFrom($sender)
                        ->addTo($adminEmail, 'Admin')
                        ->getTransport();

                    $transport->sendMessage();
                    $this->inlineTranslation->resume();
                }
                $this->messageManager
                    ->addSuccess(
                        __('The Comment has been posted.')
                    );
            } catch (
            LocalizedException $e
            ) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager
                    ->addException(
                        $e,
                        __('Something went wrong while saving the comment.')
                    );
            }
        }
        return $resultRedirect->setUrl($this->_redirect->getRefererUrl());
    }
}
