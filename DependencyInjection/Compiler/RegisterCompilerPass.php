<?php
/**
 * File RegisterCompilerPass.php 
 */

namespace Tebru\RetrofitBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class RegisterCompilerPass
 *
 * @author Nate Brunette <n@tebru.net>
 */
class RegisterCompilerPass implements CompilerPassInterface
{
    /**#@+
     * Service IDs
     */
    const COLLECTION_ID = 'tebru_retrofit.services_collection';
    const TAG_ID = 'tebru_retrofit.register';
    /**#@-*/

    /**
     * @param ContainerBuilder $container
     *
     * @return null
     */
    public function process(ContainerBuilder $container)
    {
        // return if our collection is not defined as a service
        if (!$container->hasDefinition(self::COLLECTION_ID)) {
            return null;
        }

        // loop over each tagged service, adding the class name to the collection
        $definition = $container->getDefinition(self::COLLECTION_ID);
        $taggedServices = $container->findTaggedServiceIds(self::TAG_ID);

        foreach ($taggedServices as $id => $tag) {
            $definition->addMethodCall('add', [$container->getDefinition($id)->getClass()]);
        }
    }
}
