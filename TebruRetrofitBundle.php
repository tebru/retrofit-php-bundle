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
     * Track if retrofit has already been loaded
     *
     * Running cache:clear will call this boot method twice
     * and throw errors if we try to require the file twice
     *
     * @var bool
     */
    private static $retrofitLoaded = false;

    /**
     * Load cache file
     */
    public function boot()
    {
        if (true === self::$retrofitLoaded) {
            return null;
        }

        $cacheDir = $this->container->getParameter('kernel.cache_dir');
        $retrofit = new Retrofit($cacheDir);
        $retrofit->load();
        self::$retrofitLoaded = true;
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
