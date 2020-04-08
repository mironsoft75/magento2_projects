<?php

namespace MageArray\News\Controller\Adminhtml\Newspost;

use MageArray\News\Controller\Adminhtml\Newspost;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Save
 * @package MageArray\News\Controller\Adminhtml\Newspost
 */
class Save extends Newspost
{

    /**
     *
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $model = $this->_newspostFactory->create();
            if (isset($data['store_id'])) {
                $data['store_id'] = implode(",", $data['store_id']);
            } else {
                $data['store_id'] = 0;
            }
            if (isset($data['category'])) {
                $data['category'] = implode(",", $data['category']);
            }

            $id = $this->getRequest()->getParam('newspost_id');
            if ($id) {
                $model->load($id);
            }
            if (isset($data['image'])) {
                $imageData = $data['image'];
                unset($data['image']);
            } else {
                $imageData = [];
            }

            if (isset($data['image_thumb'])) {
                $imageThumbData = $data['image_thumb'];
                unset($data['image_thumb']);
            } else {
                $imageThumbData = [];
            }

            try {
                if ($data['url_key']) {
                    $data['url_key'] = preg_replace(
                        '/^-+|-+$/',
                        '',
                        strtolower(
                            preg_replace(
                                '/[^a-zA-Z0-9]+/',
                                '-',
                                $data['url_key']
                            )
                        )
                    );
                } else {
                    $data['url_key'] = preg_replace(
                        '/^-+|-+$/',
                        '',
                        strtolower(
                            preg_replace(
                                '/[^a-zA-Z0-9]+/',
                                '-',
                                $data['title']
                            )
                        )
                    );
                }
                $modelUrl = $this->_newspostFactory->create();
                if ($id) {
                    $modelURL = $modelUrl->getCollection()
                        ->addFieldToFilter('newspost_id', ['neq' => $id]);
                    $idToAdd = $id;
                } else {
                    $modelURL = $modelUrl->getCollection();
                    $getLastId = $modelURL;
                    $getLastIdData = $getLastId->getLastItem();
                    $lastId = $getLastIdData->getId();
                    $idToAdd = $lastId + 1;
                }
                foreach ($modelURL->getData() as $url) {
                    if ($url['url_key'] == $data['url_key']) {
                        $data['url_key'] = $data['url_key'] . '-' . $idToAdd;
                    }
                }

                $imageFile = $this->_dataHelper->uploadImage('image');
                if ($imageFile) {
                    $data['image'] = $imageFile;
                } else {
                    $data['image'] = $model->getData('image');
                }
                if (isset($imageData['delete'])) {
                    $this->_dataHelper->removeImage($imageData['value']);
                    $data['image'] = '';
                }

                $imageThumbFile = $this->_dataHelper->uploadImage('image_thumb');
                if ($imageThumbFile) {
                    $data['image_thumb'] = $imageThumbFile;
                } else {
                    $data['image_thumb'] = $model->getData('image_thumb');
                }
                if (isset($imageThumbData['delete'])) {
                    $this->_dataHelper->removeImage($imageThumbData['value']);
                    $data['image_thumb'] = '';
                }
				
				if(isset($data['publish_date']))
				{
					$data['publish_date'] = date('Y-m-d', strtotime($data['publish_date']));
				}
				
                $model->setData($data);
                if (isset($data['preview']) && $data['preview']) {
                    $revision = $model->saveAsRevision();
                    $this->saveProducts($revision, $data);
                    //@todo: Improve
                    $url = $this->_storeManager->getStore()->getBaseUrl();

                    $url .= 'news/view/index/id/' . $revision->getId() . '/';
                    return $resultRedirect->setUrl($url);
                } else {
                    $model->setType("post");
                    $model->save();
                }
                $postId = $model->getId();
                $this->saveProducts($model, $data);
                $this->messageManager
                    ->addSuccess(__('The news post has been saved.'));
                $this->_objectManager
                    ->get('Magento\Backend\Model\Session')
                    ->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect(
                        '*/*/edit',
                        ['newspost_id' => $postId, '_current' => true]
                    );
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException(
                    $e,
                    __($e->getMessage())
                );
            }

            $this->_getSession()->setFormData($data);
            $this->_redirect(
                '*/*/edit',
                [
                    'newspost_id' => $this->getRequest()
                        ->getParam('newspost_id')
                ]
            );
            return;
        }
        $this->_redirect('*/*/');
    }

    public function saveProducts($model, $post)
    {
        $postId = $model->getId();
        if (isset($post['links'])) {
            $links = $post['links'];
            $jsHelper = $this->_jsHelper;
            $links = is_array($links) ? $links : [];
            $linkTypes = ['relatedposts', 'relatedproducts'];
            foreach ($linkTypes as $type) {
                if (isset($links[$type])) {
                    $links[$type] = $jsHelper
                        ->decodeGridSerializedInput($links[$type]);
                    if ($type == 'relatedposts') {
                        $relatedPost = $links[$type];
                    }
                    if ($type == 'relatedproducts') {
                        $relatedProduct = $links[$type];
                    }
                }
            }

            $newspostmanager = $this->_objectManager
                ->get('MageArray\News\Model\ResourceModel\Newspost');
            if (isset($relatedPost)) {
                $newRelatedIds = $relatedPost;
                if (is_array($newRelatedIds)) {
                    $oldRelatedIds = $newspostmanager
                        ->getRelatedPostIds($postId);
                    $insert = array_keys($newRelatedIds);
                    $delete = $oldRelatedIds;

                    $relatedTable = $this->_resource
                        ->getTableName(
                            'magearray_news_post_relatedpost'
                        );
                    if ($delete) {
                        foreach ($delete as $deleteId) {
                            $query = 'DELETE FROM ' .
                                $relatedTable .
                                ' WHERE 
							newspost_id="' . $postId . '" and
							 related_id = "' . $deleteId . '"';
                            $this->_resource->getConnection()
                                ->query($query);
                        }
                    }
                    if ($insert) {
                        $data = [];
                        foreach ($insert as $relatedId) {
                            if (isset($relatedPost[$relatedId])
                                && is_array($relatedPost[$relatedId])
                            ) {
                                $position = $relatedPost[$relatedId]['position'];
                            } else {
                                $position = 0;
                            }
                            if (!isset($position) || $position == "") {
                                $position = 0;
                            }
                            $query = 'INSERT INTO ' . $relatedTable . ' 
							(newspost_id,related_id,position) 
							VALUES (' . $postId . ',' . $relatedId . ',
							' . $position . ')';
                            $this->_resource->getConnection()
                                ->query($query);
                        }
                    }
                }
            }
            if (isset($relatedProduct)) {
                $newRelatedIds = $relatedProduct;
                if (is_array($newRelatedIds)) {
                    $oldRelatedIds = $newspostmanager
                        ->getRelatedProductIds($postId);
                    $insert = array_keys($newRelatedIds);
                    $delete = $oldRelatedIds;
                    $relatedTable = $this->_resource
                        ->getTableName(
                            'magearray_news_post_relatedproduct'
                        );
                    if ($delete) {
                        foreach ($delete as $deleteId) {
                            $query = 'DELETE FROM ' .
                                $relatedTable .
                                ' WHERE
							 newspost_id="' . $postId .
                                '" and related_id = "' .
                                $deleteId . '"';
                            $this->_resource->getConnection()
                                ->query($query);
                        }
                    }
                    if ($insert) {
                        $data = [];
                        foreach ($insert as $relatedId) {
                            if (isset($relatedProduct[$relatedId])
                                && is_array($relatedProduct[$relatedId])
                            ) {
                                $position = $relatedProduct[$relatedId]['position'];
                            } else {
                                $position = 0;
                            }
                            if (!isset($position) || $position == "") {
                                $position = 0;
                            }
                            $query = 'INSERT INTO ' .
                                $relatedTable .
                                ' (newspost_id,related_id,position) 
							VALUES (' . $postId .
                                ',' .
                                $relatedId .
                                ',' .
                                $position . '
								)';
                            $this->_resource->getConnection()
                                ->query($query);
                        }
                    }
                }
            }
        }
    }
}
