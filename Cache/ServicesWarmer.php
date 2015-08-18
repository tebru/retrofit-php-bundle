<?php
/**
 * File ServicesWarmer.php 
 */

namespace Tebru\RetrofitBundle\Cache;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;
use Tebru\Retrofit\Retrofit;
use Tebru\RetrofitBundle\DependencyInjection\Compiler\RegisterCompilerPass;
use Tebru\RetrofitBundle\ServicesCollection;

/**
 * Class ServicesWarmer
 *
 * Warms up defined services
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ServicesWarmer implements CacheWarmerInterface
{
    /**
     * @var ContainerInterface $container
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
     * Checks whether this warmer is optional or not.
     *
     * Optional warmers can be ignored on certain conditions.
     *
     * A warmer should return true if the cache can be
     * generated incrementally and on-demand.
     *
     * @return bool true if the warmer is optional, false otherwise
     */
    public function isOptional()
    {
        return false;
    }

    /**
     * Warms up the cache.
     *
     * Gets array of services from compiler pass and cache them
     *
     * @param string $cacheDir The cache directory
     */
    public function warmUp($cacheDir)
    {
        $retrofit = Retrofit::builder()
            ->setCacheDir($cacheDir)
            ->setEventDispatcher($this->container->get('event_dispatcher'))
            ->build();

        /** @var ServicesCollection $servicesCollection */
        $servicesCollection = $this->container->get(RegisterCompilerPass::COLLECTION_ID);

        $retrofit->registerServices($servicesCollection->getServices());
        $retrofit->createCache();
    }
}
