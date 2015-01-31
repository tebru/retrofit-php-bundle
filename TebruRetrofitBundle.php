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
    /**
     * Load cache file
     */
    public function boot()
    {
        $cacheDir = $this->container->getParameter('kernel.cache_dir');
        $retrofit = new Retrofit($cacheDir);
        $retrofit->load();
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
