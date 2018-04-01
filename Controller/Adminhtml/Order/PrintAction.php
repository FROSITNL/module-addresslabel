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

use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Class PrintAction
 * @package Frosit\AddressLabel\Controller\Adminhtml\Order
 */
class PrintAction extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    protected $fileFactory;

    /**
     * @var \Magento\Backend\Model\View\Result\RedirectFactory
     */
    protected $resultRedirectFactory;

    /**
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @todo remove this include
     *
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;

    /**
     * @var \Frosit\AddressLabel\Model\Pdf\addressFactory
     */
    protected $addressPdfFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     * @param \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
     * @param \Frosit\AddressLabel\Model\Pdf\AddressFactory $addressPdfFactory
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Frosit\AddressLabel\Model\Pdf\AddressFactory $addressPdfFactory,
        \Magento\Framework\Stdlib\DateTime\DateTime $date
    )
    {
        parent::__construct($context);
        $this->fileFactory = $fileFactory;
        $this->resultRedirectFactory = $context->getResultRedirectFactory();
        $this->orderRepository = $orderRepository;
        $this->addressPdfFactory = $addressPdfFactory;
        $this->date = $date;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magento_Sales::sales_order');
    }


    /**
     * Print order address label
     *
     * @return $this|ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws \Exception
     */
    public function execute()
    {
        $orderId = $this->getRequest()->getParam('order_id');
        if ($orderId) {
            $order = $this->orderRepository->get($orderId);
            if ($order) {
                $pdf = $this->addressPdfFactory->create()->getPdf([$order]);

                return $this->fileFactory->create(
                    'address-' . $order->getIncrementId() . '.pdf',
                    $pdf->render(),
                    DirectoryList::VAR_DIR,
                    'application/pdf'
                );
            }
        }
        return $this->resultRedirectFactory->create()->setPath('sales/*/view');
    }
}
