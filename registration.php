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

use \Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(
    ComponentRegistrar::MODULE,
    'Frosit_AddressLabel',
    __DIR__
);
