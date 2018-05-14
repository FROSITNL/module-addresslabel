<?php
/**
 * FROSIT     Enterprise eCommerce - Information Security
 *
 * @author    Fabio Ros <fabio@frosit.nl>
 * @copyright Copyright (c) 2018 Fabio (H.J.C.) Ros <fabio@frosit.nl> (https://frosit.nl)
 * @license   http://frosit.nl/license FROSIT Proprietary license - All Rights Reserved
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. If no license is present, refer to the license URL.
 * This code is by default copyright protected under Dutch law and property of the author as specified above.
 */

namespace Frosit\AddressLabel\Block\Adminhtml;

class PrintOrderButton extends \Magento\Backend\Block\Widget\Container
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry = null;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
    
        $this->coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    protected function _construct()
    {
        $this->addButton(
            'frosit_print',
            [
                'label' => __('Print Address'),
                'class' => 'print',
                'onclick' => 'setLocation(\'' . $this->getPdfPrintUrl() . '\')'
            ]
        );

        parent::_construct();
    }

    /**
     * @return string
     */
    public function getPdfPrintUrl()
    {
        return $this->getUrl('frosit_addresslabel/order/print/order_id/' . $this->getOrderId());
    }

    /**
     * @return integer
     */
    public function getOrderId()
    {
        return $this->coreRegistry->registry('sales_order')->getId();
    }
}
