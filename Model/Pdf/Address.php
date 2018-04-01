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

namespace Frosit\AddressLabel\Model\Pdf;

use Magento\Sales\Model\Order\Pdf\AbstractPdf;

/**
 * Class Address
 * @package Frosit\AddressLabel\Model\Pdf
 */
class Address extends AbstractPdf
{

    /**
     * Default font size xml path
     */
    const XML_PATH_FONT_SIZE = 'addresslabel/settings/font_size';

    /**
     * @param array $orders
     * @return \Zend_Pdf
     * @throws \Zend_Pdf_Exception
     */
    public function getPdf($orders = [])
    {
        $this->_beforeGetPdf();

        $pdf = new \Zend_Pdf();
        $this->_setPdf($pdf);

        $fontSize = $this->_scopeConfig->getValue(self::XML_PATH_FONT_SIZE);

        foreach ($orders as $order) {

            $page = $this->newPage();
            $this->_setFontRegular($page, $fontSize);
            $order->setOrder($order);

            $shippingAddress = $this->_formatAddress($this->addressRenderer->format($order->getShippingAddress(), 'pdf'));

            $tel = $order->getShippingAddress()->getTelephone();
            $name = $order->getShippingAddress()->getName();

            $this->y = 765;

            foreach ($shippingAddress as $value) {
                if ($value !== '') {

                    // name in bold
                    if ($value === $name) {
                        $this->_setFontBold($page, $fontSize);
                    } else {
                        $this->_setFontRegular($page, $fontSize);
                    }

                    // Skip tel
                    if ($value === $tel) {
                        continue;
                    }

                    $text = [];
                    foreach ($this->string->split($value, 45, true, true) as $_value) {
                        $text[] = $_value;
                    }
                    foreach ($text as $part) {
                        $page->drawText(strip_tags(ltrim($part)), 70, $this->y, 'UTF-8');
                        $this->y -= 15;
                    }
                }
            }

        }
        $this->_afterGetPdf();
        return $pdf;
    }

    /**
     * @param string $input
     * @return string
     */
    public function getFileName($input = '')
    {
        return sprintf('%s.pdf', $input);
    }

    public function canRender()
    {
        return true;
    }
}