<?php
namespace Softprodigy\Bluedart\Model\Mail;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TransportBuilder
 *
 * @author mannu
 */
class TransportBuilder extends \Magento\Framework\Mail\Template\TransportBuilder {
    
    /**
     * 
     * @return \Magento\Framework\Mail\Message
     */
    public function getMail(){
        return $this->message;
    }
}
