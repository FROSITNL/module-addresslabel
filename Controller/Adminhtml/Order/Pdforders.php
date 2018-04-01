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

namespace Frosit\AddressLabel\Controller\Adminhtml\Order;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Pdforders
 * @package Frosit\AddressLabel\Controller\Adminhtml\Order
 *
 * @todo change deprecated depend
 */
class Pdforders extends \Magento\Sales\Controller\Adminhtml\Order\AbstractMassAction
{
    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    protected $fileFactory;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;

    /**
     * @var \Frosit\AddressLabel\Model\Pdf\AddressFactory
     */
    protected $addressPdfFactory;

    /**
     * Pdforders constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     * @param \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $collectionFactory
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     * @param \Frosit\AddressLabel\Model\Pdf\AddressFactory $addressPdfFactory
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $collectionFactory,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Frosit\AddressLabel\Model\Pdf\AddressFactory $addressPdfFactory,
        \Magento\Framework\Stdlib\DateTime\DateTime $date
    )
    {
        $this->collectionFactory = $collectionFactory;
        $this->fileFactory = $fileFactory;
        $this->addressPdfFactory = $addressPdfFactory;
        $this->date = $date;
        parent::__construct($context, $filter);
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magento_Sales::sales_order');
    }

    /**
     * @param AbstractCollection $collection
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws \Exception
     */
    protected function massAction(AbstractCollection $collection)
    {
        $pdf = $this->addressPdfFactory->create()->getPdf($collection);
        $date = $this->date->date('Y-m-d_H-i-s');

        return $this->fileFactory->create(
            'addresses-' . $date . '.pdf',
            $pdf->render(),
            DirectoryList::VAR_DIR,
            'application/pdf'
        );
    }
}
