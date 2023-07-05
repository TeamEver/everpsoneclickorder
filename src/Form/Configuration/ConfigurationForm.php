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

use PrestaShop\PrestaShop\Core\Form\FormChoiceProviderInterface;
use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\TranslatorInterface;

class ConfigurationForm extends TranslatorAwareType
{

    private FormChoiceProviderInterface $paymentModulesChoiceProvider;

    public function __construct(
        TranslatorInterface $translator,
        array $locales,
        FormChoiceProviderInterface $paymentModulesChoiceProvider
    )
    {
        $this->paymentModulesChoiceProvider = $paymentModulesChoiceProvider;

        parent::__construct($translator, $locales);

    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add(ConfigurationFormDataConfiguration::EVERPS_OCO_PAYMENT_MODULE_NAME, ChoiceType::class, [
            'label' => $this->trans('Mode de paiement', 'Admin.Actions'),
            'choices' => $this->getPaymentModuleChoices(),
            'required' => true,
            'placeholder' => $this->trans(
                '-- Choose --',
                'Admin.Actions',
            ),
        ]);

        $builder
            ->add(ConfigurationFormDataConfiguration::EVERPS_OCO_CARRIER_REFERENCE, ChoiceType::class, [
                'label' => $this->trans('Mode de livraison', 'Admin.Actions'),
                'choices' => $this->getCarriersChoices(),
                'required' => true,
                'placeholder' => $this->trans(
                    '-- Choose --',
                    'Admin.Actions',
                    []
                ),
            ]);
    }

    /**
     * Gets payment module choices
     *
     * @return array
     */
    private function getPaymentModuleChoices(): array
    {
        $choices = [];

        foreach ($this->paymentModulesChoiceProvider->getChoices() as $name => $displayName) {
            $choices[$displayName] = $name;
        }

        return $choices;
    }

    private function getCarriersChoices(): array
    {
        foreach(\Carrier::getCarriers(\Context::getContext()->language->id) as $carrier) {
            $choices[$carrier['name']] = $carrier['id_reference'];
        }

        return $choices;
    }
}
