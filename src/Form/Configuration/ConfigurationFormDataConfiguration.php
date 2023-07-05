<?php
/**
 * 2019-2023 Team Ever
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 *  @author    Team Ever <https://www.team-ever.com/>
 *  @copyright 2019-2021 Team Ever
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

namespace Ever\Oneclickorder\Form\Configuration;


use PrestaShop\PrestaShop\Core\Configuration\DataConfigurationInterface;
use PrestaShop\PrestaShop\Core\ConfigurationInterface;

class ConfigurationFormDataConfiguration implements DataConfigurationInterface
{

    public const EVERPS_OCO_CARRIER_REFERENCE = 'EVERPS_OCO_CARRIER_REFERENCE';

    public const EVERPS_OCO_PAYMENT_MODULE_NAME = 'EVERPS_OCO_PAYMENT_MODULE_NAME';
    private ConfigurationInterface $configuration;

    public function __construct(ConfigurationInterface $configuration)
    {
        $this->configuration = $configuration;
    }

    public function getConfiguration(): array
    {
        return [
            self::EVERPS_OCO_CARRIER_REFERENCE => $this->configuration->get(self::EVERPS_OCO_CARRIER_REFERENCE),
            self::EVERPS_OCO_PAYMENT_MODULE_NAME => $this->configuration->get(self::EVERPS_OCO_PAYMENT_MODULE_NAME),
        ];
    }

    public function updateConfiguration(array $configuration): void
    {
        $this->configuration->set(self::EVERPS_OCO_CARRIER_REFERENCE, $configuration[self::EVERPS_OCO_CARRIER_REFERENCE]);
        $this->configuration->set(self::EVERPS_OCO_PAYMENT_MODULE_NAME, $configuration[self::EVERPS_OCO_PAYMENT_MODULE_NAME]);
    }

    public function validateConfiguration(array $configuration): bool
    {
        return true;
    }
}
