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

use Ever\Oneclickorder\Form\Configuration\ConfigurationFormDataConfiguration;
use PrestaShop\PrestaShop\Adapter\SymfonyContainer;
use PrestaShop\PrestaShop\Core\Payment\PaymentOption;

class Everpsoneclickorder extends Module
{

    public function __construct()
    {
        $this->name = 'everpsoneclickorder';
        $this->author = 'Team Ever';
        $this->version = '1.0.1';
        $this->ps_versions_compliancy = ['min' => '8.1.0', 'max' => _PS_VERSION_];

        parent::__construct();

        $this->displayName = $this->trans('One click order', [], self::getTranslationDomain());
        $this->description = $this->trans('One click order module', [], self::getTranslationDomain());
    }

    private array $hooks = ['actionCheckoutRender'];

    public function install()
    {
        return parent::install() && $this->registerHook($this->hooks);
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    public function isUsingNewTranslationSystem()
    {
        return true;
    }

    public function getContent()
    {
        //config form to select carrier and payment method
        //PaymentModule::getInstalledPaymentModules()
        $route = SymfonyContainer::getInstance()->get('router')->generate('ever.oneclickorder.configuration_form.index');
        Tools::redirectAdmin($route);
    }

    public function hookActionCheckoutRender(array $params)
    {
        $carrier = Carrier::getCarrierByReference(Configuration::get(ConfigurationFormDataConfiguration::CL_OCO_CARRIER_REFERENCE));
        $deliveryAddress = $this->getAddressTypeByCustomer($this->context->customer, 'delivery');
        $invoiceAddress = $this->getAddressTypeByCustomer($this->context->customer, 'invoice');

        $this->context->cart->id_address_delivery = $deliveryAddress->id;
        $this->context->cart->id_address_invoice = $invoiceAddress->id;
        $this->context->cart->id_carrier = $carrier->id;

        $paymentModuleName = Configuration::get(ConfigurationFormDataConfiguration::CL_OCO_PAYMENT_MODULE_NAME);
        $paymentModule = Module::getInstanceByName($paymentModuleName);

        /** @var array<PaymentOption> $paymentOption */
        $paymentOption = Hook::exec('paymentOptions',
            [],
            $paymentModule->id,
            true);

        if (count($paymentOption)) {
            if (isset($paymentOption[$paymentModule->name]) && count($paymentOption[$paymentModule->name]) > 0) {
                $paymentOption = $paymentOption[$paymentModule->name][0];
                if ($paymentOption instanceof PaymentOption) {
                    /** @var PaymentOption $paymentOption */
                    if ($paymentOption->getAction()) {
                        Tools::redirect($paymentOption->getAction());
                    } else {
                        $this->context->controller->errors[] = $this->trans('Payment option has not action url for module ' . $paymentModuleName, [], self::getTranslationDomain());
                    }
                } else {
                    $this->context->controller->errors[] = $this->trans('Payment is not payment option for module ' . $paymentModuleName, [], self::getTranslationDomain());
                }
            } else {
                $this->context->controller->errors[] = $this->trans('No payment option find for module payment : ' . $paymentModuleName, [], self::getTranslationDomain());
            }
        } else {
            $this->context->controller->errors[] = $this->trans('No payment option find for module payment : ' . $paymentModuleName, [], self::getTranslationDomain());
        }


        $this->context->controller->redirectWithNotifications($this->context->link->getPageLink('cart', null, $this->context->language->id, ['action' => 'show']));
    }

    private array $addressesForCustomer = [];

    /**
     * @param Customer $customer
     * @param string $type delivery or invoice
     * @return Address|null
     */
    private function getAddressTypeByCustomer(Customer $customer, string $type = 'delivery')
    {
        if (count($this->addressesForCustomer) === 0) {
            $this->addressesForCustomer = $customer->getAddresses($this->context->language->id);
        }

        foreach ($this->addressesForCustomer as $addressInfo) {
            if ($addressInfo['alias'] === $type) {
                return new Address($addressInfo['id_address']);
            }
        }

        return null;
    }

    private static function getTranslationDomain(): string
    {
        return 'Modules.Everpsoneclickorder.Module';
    }
}
