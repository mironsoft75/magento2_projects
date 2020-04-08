<?php

namespace MageArray\News\Controller\Adminhtml\Newscat;

use MageArray\News\Controller\Adminhtml\Newscat;

/**
 * Class Save
 * @package MageArray\News\Controller\Adminhtml\Newscat
 */
class Save extends Newscat
{
    /**
     *
     */
    public function execute()
    {
        $formPostValues = $this->getRequest()->getPostValue();
        if (isset($formPostValues['newscat'])) {
            $newscatData = $formPostValues['newscat'];
            if (isset($newscatData['cat_store_id'])) {
                $newscatData['cat_store_id'] = implode(
                    ",",
                    $newscatData['cat_store_id']
                );
            } else {
                $newscatData['cat_store_id'] = 0;
            }
            $newscatId = isset(
                $newscatData['cat_id']
            ) ? $newscatData['cat_id'] : null;
            $model = $this->_newscatFactory->create();

            $model->load($newscatId);

            if ($newscatData['cat_url_key']) {
                $newscatData['cat_url_key'] = preg_replace(
                    '/^-+|-+$/',
                    '',
                    strtolower(
                        preg_replace(
                            '/[^a-zA-Z0-9]+/',
                            '-',
                            $newscatData['cat_url_key']
                        )
                    )
                );
            } else {
                $newscatData['cat_url_key'] = preg_replace(
                    '/^-+|-+$/',
                    '',
                    strtolower(
                        preg_replace(
                            '/[^a-zA-Z0-9]+/',
                            '-',
                            $newscatData['cat_name']
                        )
                    )
                );
            }
            $modelUrl = $this->_newscatFactory->create();
            if ($newscatId) {
                $modelURL = $modelUrl->getCollection()
                    ->addFieldToFilter('cat_id', ['neq' => $newscatId]);
            } else {
                $modelURL = $modelUrl->getCollection();
            }
            $count = 0;
            foreach ($modelURL->getData() as $url) {
                if ($url['cat_url_key'] == $newscatData['cat_url_key']) {
                    $count++;
                    if ($count = 1) {
                        $random = rand(1, 99);
                        $newscatData['cat_url_key'] .= '-' . $random;
                        break;
                    }
                }
            }
            $model->setData($newscatData);
            try {
                $model->save();
                $this->messageManager->addSuccess(
                    __('The category has been saved.')
                );
                $this->_getSession()->setFormData(false);

                if ($this->getRequest()->getParam('back') === 'edit') {
                    $this->_redirect(
                        '*/*/edit',
                        ['cat_id' => $model->getCatId(), '_current' => true]
                    );
                    return;
                } elseif ($this->getRequest()->getParam('back') === "new") {
                    $this->_redirect(
                        '*/*/new',
                        ['_current' => true]
                    );
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $this->messageManager
                    ->addException(
                        $e,
                        __('Something went wrong while saving the Category.')
                    );
            }

            $this->_getSession()->setFormData($formPostValues);
            $this->_redirect('*/*/edit', ['cat_id' => $newscatId]);
            return;
        }
        $this->_redirect('*/*/');
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(
            'MageArray_News::newscat'
        );
    }
}
