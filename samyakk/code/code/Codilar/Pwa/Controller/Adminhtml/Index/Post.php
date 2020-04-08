<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Pwa\Controller\Adminhtml\Index;


use Codilar\Pwa\Api\PwaRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class Post extends Action
{
    /**
     * @var PwaRepositoryInterface
     */
    private $pwaRepository;

    /**
     * Post constructor.
     * @param Action\Context $context
     * @param PwaRepositoryInterface $pwaRepository
     */
    public function __construct(
        Action\Context $context,
        PwaRepositoryInterface $pwaRepository
    )
    {
        parent::__construct($context);
        $this->pwaRepository = $pwaRepository;
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        $csv = $this->getCsvData($_FILES['csv']['tmp_name']);
        $count = 0;
        try{
            foreach ($csv as $row) {
                try {
                    $model = $this->pwaRepository->load($row['request_url'], 'request_url');
                    if (empty($row['redirect_url'])) {
                        $this->pwaRepository->delete($model);
                    }
                } catch (NoSuchEntityException $noSuchEntityException) {
                    $model = $this->pwaRepository->create();
                }
                if (!empty($row['redirect_url'])) {
                    $model->setRequestUrl($row['request_url'])->setRedirectUrl($row['redirect_url']);
                    try {
                        $this->pwaRepository->save($model);
                        $count++;
                    } catch (LocalizedException $localizedException) {
                        $this->messageManager->addErrorMessage(__("Error importing"));
                    }
                }
            }
            if ($count) {
                $this->messageManager->addSuccessMessage(__("Successfully imported %1 Urls", $count));
            }
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage(__("Couldn't import CSV. Please contact administrator or check log for details"));
        }

        return $this->resultRedirectFactory->create()->setPath('*');
    }

    protected function getCsvData($fileName) {
        $file = fopen($fileName, 'r');
        $headers = null;
        $data = [];
        while (($line = fgetcsv($file)) !== FALSE) {
            if (!$headers) {
                $headers = $line;
            } else {
                $data[] = array_combine($headers, $line);
            }
        }
        fclose($file);
        return $data;
    }
}