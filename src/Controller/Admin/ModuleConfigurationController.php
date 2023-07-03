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

namespace Ever\Oneclickorder\Controller\Admin;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ModuleConfigurationController extends FrameworkBundleAdminController
{

    public function indexAction(Request $request): Response
    {
        $configurationFormDataHandler = $this->get('ever.oneclickorder.form.configuration.data_handler');
        $configurationModuleForm = $configurationFormDataHandler->getForm();

        $configurationModuleForm->handleRequest($request);

        if ($configurationModuleForm->isSubmitted()) {
            /** You can return array of errors in form handler and they can be displayed to user with flashErrors */
            $errors = $configurationFormDataHandler->save($configurationModuleForm->getData());

            if (empty($errors)) {
                $this->addFlash('success', $this->trans('Successful update.', 'Admin.Notifications.Success'));
            }

            $this->flashErrors($errors);
        }

        return $this->render('@Modules/everpsoneclickorder/views/templates/admin/configurationForm.html.twig', [
            'configurationForm' => $configurationModuleForm->createView(),
        ]);
    }
}
