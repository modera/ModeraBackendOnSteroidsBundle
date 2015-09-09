<?php

namespace Modera\BackendOnSteroidsBundle\Contributions;

use Doctrine\ORM\EntityManager;
use Sli\ExpanderBundle\Ext\ContributorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Modera\SecurityBundle\Entity\User;
use Modera\BackendLanguagesBundle\Entity\UserSettings;
use Modera\MjrIntegrationBundle\DependencyInjection\ModeraMjrIntegrationExtension;

/**
 * Provides service definitions for client-side dependency injection container.
 *
 * @author    Sergei Vizel <sergei.vizel@modera.org>
 * @copyright 2014 Modera Foundation
 */
class ClientDiServiceDefinitionsProvider implements ContributorInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function getItems()
    {
        /* @var ContributorInterface $cssResourcesProvider */
        $jsResourcesProvider = $this->container->get('modera_mjr_integration.js_resources_provider');
        /* @var ContributorInterface $jsResourcesProvider */
        $cssResourcesProvider = $this->container->get('modera_mjr_integration.css_resources_provider');

        return array(
            'backend_on_steroids_resources_loader_plugin' => array(
                'className' => 'Modera.backend.backendonsteroids.runtime.ResourcesLoaderPlugin',
                'tags'      => ['runtime_plugin'],
                'args'      => array(
                    array(
                        'js_resources' => $jsResourcesProvider->getItems(),
                        'css_resources' => $cssResourcesProvider->getItems()
                    )
//                    array(
//                        'urls' => array(
//                            $runtimeConfig['extjs_path'] . '/locale/ext-lang-' . $locale . '.js',
//                            $router->generate('modera_backend_languages_extjs_l10n', array('locale' => $locale)),
//                        ),
//                    )
                ),
            )
        );
    }
}