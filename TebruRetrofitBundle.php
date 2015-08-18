<?php
/**
 * File TebruRetrofitBundle.php
 */

namespace Tebru\RetrofitBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tebru\Retrofit\Retrofit;
use Tebru\RetrofitBundle\DependencyInjection\Compiler\RegisterCompilerPass;

/**
 * Class TebruRetrofitBundle
 *
 * @author Nate Brunette <n@tebru.net>
 */
class TebruRetrofitBundle extends Bundle
{
    public function boot()
    {
        $cacheDir = $this->container->getParameter('kernel.cache_dir');
        $loader = require __DIR__ . '/../../autoload.php';
        $loader->addPsr4(Retrofit::NAMESPACE_PREFIX . '\\', $cacheDir . '/retrofit');
    }

    /**
     * Register compiler pass
     *
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new RegisterCompilerPass());
    }
}
