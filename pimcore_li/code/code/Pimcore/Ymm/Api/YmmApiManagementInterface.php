<?php
/**
 * Created by Pimcore.
 * Date: 17/9/18
 * Time: 4:01 PM
 */

namespace Pimcore\Ymm\Api;


interface YmmApiManagementInterface
{
    /**
     * Returns array of Unique years
     *
     * @api
     * @return integer[] of Unique years
     */
    public function getYearIds();

    /**
     * Returns array of Unique make names
     *
     * @api
     * @return integer[] of Unique make names
     */
    public function getMakeNames();

    /**
     * Returns array of Unique Model names
     *
     * @api
     * @return integer[] of Unique model names
     */
    public function getModelNames();

    /**
     * Returns array of Base vehicle ids
     *
     * @api
     * @return integer[] of Base vehicle ids
     */
    public function getBaseVehicleIds();


    /**
     * @api
     * @param integer $year
     * @return string[] of make
     */
    public function getMakeByYear($year);

    /**
     * @param $year
     * @return mixed
     */
    public function getMakesByYear($year);


    /**
     * @param integer $year
     * @param string  $make
     * @return string[] of model
     */
    public function getModelByYearAndMake($year, $make);

    /**
     * @return mixed
     */
    public function getAcesMakeNames();

    /**
     * @return mixed
     */
    public function getAcesModelNames();

    /**
     * Returns array of submodels
     *
     * @api
     * @param integer $baseid
     * @return string[]
     */
    public function getSubmodelByBaseId($baseid);
}