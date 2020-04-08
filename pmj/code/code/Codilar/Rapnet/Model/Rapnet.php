<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 5/2/19
 * Time: 1:44 PM
 */

namespace Codilar\Rapnet\Model;

use Codilar\Rapnet\Api\Data\RapnetInterface;

class Rapnet extends \Magento\Framework\Model\AbstractModel implements RapnetInterface
{

    /**
     * CMS page cache tag.
     */
    const CACHE_TAG = 'codilar_rapnet';

    /**
     * @var string
     */
    protected $_cacheTag = 'codilar_rapnet';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'codilar_rapnet_collection';

    protected function _construct()
    {
        $this->_init('Codilar\Rapnet\Model\ResourceModel\Rapnet');
    }

    /**
     * @return mixed
     */
    public function getRapnetId()
    {
        return $this->getData(self::RAPNET_ID);
    }

    /**
     * @param $rapnetId
     * @return mixed
     */
    public function setRapnetId($rapnetId)
    {
        return $this->setData(self::RAPNET_ID, $rapnetId);
    }

    /**
     * @return mixed
     */
    public function getDiamondId()
    {
        return $this->getData(self::DIAMOND_ID);
    }

    /**
     * @param $diamondId
     * @return mixed
     */
    public function setDiamondId($diamondId)
    {
        return $this->setData(self::DIAMOND_ID, $diamondId);
    }

    /**
     * @return mixed
     */
    public function getDiamondShape()
    {
        return $this->getData(self::DIAMOND_SHAPE);
    }

    /**
     * @param $diamondShape
     * @return mixed
     */
    public function setDiamondShape($diamondShape)
    {
        return $this->setData(self::DIAMOND_SHAPE, $diamondShape);
    }

    /**
     * @return mixed
     */
    public function getDiamondLab()
    {
        return $this->getData(self::DIAMOND_LAB);
    }

    /**
     * @param $diaomdLab
     * @return mixed
     */
    public function setDiamondLab($diaomdLab)
    {
        return $this->setData(self::DIAMOND_LAB, $diaomdLab);
    }

    /**
     * @return mixed
     */
    public function getDiamondCarats()
    {
        return $this->getData(self::DIAMOND_CARATS);
    }

    /**
     * @param $diamondCarats
     * @return mixed
     */
    public function setDiamondCarats($diamondCarats)
    {
        return $this->setData(self::DIAMOND_CARATS, $diamondCarats);
    }

    /**
     * @return mixed
     */
    public function getDiamondClarity()
    {
        return $this->getData(self::DIAMOND_CLARITY);
    }

    /**
     * @param $diamondClarity
     * @return mixed
     */
    public function setDiamondClarity($diamondClarity)
    {
        return $this->setData(self::DIAMOND_CLARITY, $diamondClarity);
    }

    /**
     * @return mixed
     */
    public function getDiamondColor()
    {
        return $this->getData(self::DIAMOND_COLOR);
    }

    /**
     * @param $diamondColor
     * @return mixed
     */
    public function setDiamondColor($diamondColor)
    {
        return $this->setData(self::DIAMOND_COLOR, $diamondColor);
    }

    /**
     * @return mixed
     */
    public function getDiamondCut()
    {
        return $this->getData(self::DIAMOND_CUT);
    }

    /**
     * @param $diamondCut
     * @return mixed
     */
    public function setDiamondCut($diamondCut)
    {
        return $this->setData(self::DIAMOND_CUT, $diamondCut);
    }

    /**
     * @return mixed
     */
    public function getDiamondPolish()
    {
        return $this->getData(self::DIAMOND_POLISH);
    }

    /**
     * @param $diamondPolish
     * @return mixed
     */
    public function setDiamondPolish($diamondPolish)
    {
        return $this->setData(self::DIAMOND_POLISH, $diamondPolish);
    }

    /**
     * @return mixed
     */
    public function getDiamondSymmetry()
    {
        return $this->getData(self::DIAMOND_SYMMETRY);
    }

    /**
     * @param $diamondSymmetry
     * @return mixed
     */
    public function setDiamondSymmetry($diamondSymmetry)
    {
        return $this->setData(self::DIAMOND_SYMMETRY, $diamondSymmetry);
    }

    /**
     * @return mixed
     */
    public function getDiamondTablePercent()
    {
        return $this->getData(self::DIAMOND_TABLE_PERCENT);
    }

    /**
     * @param $diamondTablePercent
     * @return mixed
     */
    public function setDiamondTablePercent($diamondTablePercent)
    {
        return $this->setData(self::DIAMOND_TABLE_PERCENT, $diamondTablePercent);
    }

    /**
     * @return mixed
     */
    public function getDiamondDepthPercent()
    {
        return $this->getData(self::DIAMOND_DEPTH_PERCENT);
    }

    /**
     * @param $diamondDepthPercent
     * @return mixed
     */
    public function setDiamondDepthPercent($diamondDepthPercent)
    {
        return $this->setData(self::DIAMOND_DEPTH_PERCENT, $diamondDepthPercent);
    }

    /**
     * @return mixed
     */
    public function getDiamondMeasurements()
    {
        return $this->getData(self::DIAMOND_MEASUREMENTS);
    }

    /**
     * @param $diamondMeasurements
     * @return mixed
     */
    public function setDiamondMeasurements($diamondMeasurements)
    {
        return $this->setData(self::DIAMOND_MEASUREMENTS, $diamondMeasurements);
    }

    /**
     * @return mixed
     */
    public function getDiamondFluoresence()
    {
        return $this->getData(self::DIAMOND_FLUORESENCE);
    }

    /**
     * @param $diamondFluoresence
     * @return mixed
     */
    public function setDiamondFluoresence($diamondFluoresence)
    {
        return $this->setData(self::DIAMOND_FLUORESENCE, $diamondFluoresence);
    }

    /**
     * @return mixed
     */
    public function getDiamondCertificateNum()
    {
        return $this->getData(self::DIAMOND_CERTIFICATE_NUM);
    }

    /**
     * @param $diamondCertificateNum
     * @return mixed
     */
    public function setDiamondCertificateNum($diamondCertificateNum)
    {
        return $this->setData(self::DIAMOND_CERTIFICATE_NUM, $diamondCertificateNum);
    }

    /**
     * @return mixed
     */
    public function getDiamondHasCertificateFile()
    {
        return $this->getData(self::DIAMOND_HAS_CERTIFICATE_FILE);
    }

    /**
     * @param $diamondHasCertificateFile
     * @return Rapnet|mixed
     */
    public function setDiamondHasCertificateFile($diamondHasCertificateFile)
    {
        return $this->setData(self::DIAMOND_HAS_CERTIFICATE_FILE, $diamondHasCertificateFile);
    }

    /**
     * @return mixed
     */
    public function getDiamondPrice()
    {
        return $this->getData(self::DIAMOND_PRICE);
    }

    /**
     * @param $diamondPrice
     * @return Rapnet|mixed
     */
    public function setDiamondPrice($diamondPrice)
    {
        return $this->setData(self::DIAMOND_PRICE, $diamondPrice);
    }

    /**
     * @return mixed
     */
    public function getDiamondStockNum()
    {
        return $this->getData(self::DIAMOND_STOCK_NUM);
    }

    /**
     * @param $diamondStockNum
     * @return Rapnet|mixed
     */
    public function setDiamondStockNum($diamondStockNum)
    {
        return $this->setData(self::DIAMOND_STOCK_NUM, $diamondStockNum);
    }

    /**
     * @return mixed
     */
    public function getDiamondHasImageFile()
    {
        return $this->getData(self::DIAMOND_HAS_IMAGE_FILE);
    }

    /**
     * @param $diamondHasImageFile
     * @return Rapnet|mixed
     */
    public function setDiamondHasImageFile($diamondHasImageFile)
    {
        return $this->setData(self::DIAMOND_HAS_IMAGE_FILE, $diamondHasImageFile);
    }

    /**
     * @return mixed
     */
    public function getDiamondImageFileUrl()
    {
        return $this->getData(self::DIAMOND_IMAGE_FILE_URL);
    }

    /**
     * @param $diamondImageFileUrl
     * @return Rapnet|mixed
     */
    public function setDiamondImageFileUrl($diamondImageFileUrl)
    {
        return $this->setData(self::DIAMOND_IMAGE_FILE_URL, $diamondImageFileUrl);
    }
}
