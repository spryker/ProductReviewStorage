<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Dependency\Facade;

use Spryker\Zed\Availability\Business\AvailabilityFacadeInterface;

class ProductBundleToAvailabilityBridge implements ProductBundleToAvailabilityInterface
{

    /**
     * @var AvailabilityFacadeInterface
     */
     protected $availabilityFacade;

    /**
     * @param \Spryker\Zed\Availability\Business\AvailabilityFacadeInterface $availabilityFacade
     */
    public function __construct($availabilityFacade)
    {
        $this->availabilityFacade = $availabilityFacade;
    }

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return bool
     */
    public function isProductSellable($sku, $quantity)
    {
        return $this->availabilityFacade->isProductSellable($sku, $quantity);
    }

    /**
     * @param string $sku
     *
     * @return int
     */
    public function calculateStockForProduct($sku)
    {
        return $this->availabilityFacade->calculateStockForProduct($sku);
    }

    /**
     * @param int
     *
     * @return void
     */
    public function touchAvailabilityAbstract($idAvailabilityAbstract)
    {
        $this->availabilityFacade->touchAvailabilityAbstract($idAvailabilityAbstract);
    }
}
