<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 5/2/19
 * Time: 1:08 PM
 */

namespace Codilar\Rapnet\Api\Data;

interface RapnetInterface
{
    const RAPNET_ID = 'rapnet_id';
    const DIAMOND_ID = 'diamond_id';
    const DIAMOND_SHAPE = 'diamond_shape';
    const DIAMOND_LAB = 'diamond_lab';
    const DIAMOND_CARATS = 'diamond_carats';
    const DIAMOND_CLARITY = 'diamond_clarity';
    const DIAMOND_COLOR = 'diamond_color';
    const DIAMOND_CUT = 'diamond_cut';
    const DIAMOND_POLISH = 'diamond_polish';
    const DIAMOND_SYMMETRY = "diamond_symmetry";
    const DIAMOND_TABLE_PERCENT = "diamond_table_percent";
    const DIAMOND_DEPTH_PERCENT = "diamond_depth_percent";
    const DIAMOND_MEASUREMENTS = "diamond_measurements";
    const DIAMOND_FLUORESENCE = "diamond_fluoresence";
    const DIAMOND_CERTIFICATE_NUM = "diamond_certificate_num";
    const DIAMOND_HAS_CERTIFICATE_FILE = "diamond_has_cert_file";
    const DIAMOND_PRICE = "diamond_price";
    const DIAMOND_STOCK_NUM = "diamond_stock_num";
    const DIAMOND_HAS_IMAGE_FILE = "diamond_has_image_file";
    const DIAMOND_IMAGE_FILE_URL = "diamond_image_file_url";


    /**
     * @return mixed
     */
    public function getRapnetId();

    /**
     * @param $rapnetId
     * @return mixed
     */
    public function setRapnetId($rapnetId);

    /**
     * @return mixed
     */
    public function getDiamondId();

    /**
     * @param $diamondId
     * @return mixed
     */
    public function setDiamondId($diamondId);

    /**
     * @return mixed
     */
    public function getDiamondShape();

    /**
     * @param $diamondShape
     * @return mixed
     */
    public function setDiamondShape($diamondShape);

    /**
     * @return mixed
     */
    public function getDiamondLab();

    /**
     * @param $diaomdLab
     * @return mixed
     */
    public function setDiamondLab($diaomdLab);

    /**
     * @return mixed
     */
    public function getDiamondCarats();

    /**
     * @param $diamondCarats
     * @return mixed
     */
    public function setDiamondCarats($diamondCarats);

    /**
     * @return mixed
     */
    public function getDiamondClarity();

    /**
     * @param $diamondClarity
     * @return mixed
     */
    public function setDiamondClarity($diamondClarity);

    /**
     * @return mixed
     */
    public function getDiamondColor();

    /**
     * @param $diamondColor
     * @return mixed
     */
    public function setDiamondColor($diamondColor);

    /**
     * @return mixed
     */
    public function getDiamondCut();

    /**
     * @param $diamondCut
     * @return mixed
     */
    public function setDiamondCut($diamondCut);

    /**
     * @return mixed
     */
    public function getDiamondPolish();

    /**
     * @param $diamondPolish
     * @return mixed
     */
    public function setDiamondPolish($diamondPolish);

    /**
     * @return mixed
     */
    public function getDiamondSymmetry();

    /**
     * @param $diamondSymmetry
     * @return mixed
     */
    public function setDiamondSymmetry($diamondSymmetry);

    /**
     * @return mixed
     */
    public function getDiamondTablePercent();

    /**
     * @param $diamondTablePercent
     * @return mixed
     */
    public function setDiamondTablePercent($diamondTablePercent);

    /**
     * @return mixed
     */
    public function getDiamondDepthPercent();

    /**
     * @param $diamondDepthPercent
     * @return mixed
     */
    public function setDiamondDepthPercent($diamondDepthPercent);

    /**
     * @return mixed
     */
    public function getDiamondMeasurements();

    /**
     * @param $diamondMeasurements
     * @return mixed
     */
    public function setDiamondMeasurements($diamondMeasurements);

    /**
     * @return mixed
     */
    public function getDiamondFluoresence();

    /**
     * @param $diamondFluoresence
     * @return mixed
     */
    public function setDiamondFluoresence($diamondFluoresence);

    /**
     * @return mixed
     */
    public function getDiamondCertificateNum();

    /**
     * @param $diamondCertificateNum
     * @return mixed
     */
    public function setDiamondCertificateNum($diamondCertificateNum);

    /**
     * @return mixed
     */
    public function getDiamondHasCertificateFile();

    /**
     * @param $diamondHasCertificateFile
     * @return mixed
     */
    public function setDiamondHasCertificateFile($diamondHasCertificateFile);

    /**
     * @return mixed
     */
    public function getDiamondPrice();

    /**
     * @param $diamondPrice
     * @return mixed
     */
    public function setDiamondPrice($diamondPrice);

    /**
     * @return mixed
     */
    public function getDiamondStockNum();

    /**
     * @param $diamondStockNum
     * @return mixed
     */
    public function setDiamondStockNum($diamondStockNum);

    /**
     * @return mixed
     */
    public function getDiamondHasImageFile();

    /**
     * @param $diamondHasImageFile
     * @return mixed
     */
    public function setDiamondHasImageFile($diamondHasImageFile);

    /**
     * @return mixed
     */
    public function getDiamondImageFileUrl();

    /**
     * @param $diamondImageFileUrl
     * @return mixed
     */
    public function setDiamondImageFileUrl($diamondImageFileUrl);
}
