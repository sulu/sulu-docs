Provider for XML-Sitemap
========================

`SitemapProvider` are used to load data for the XML-Sitemap. It returns
an array of `SitemapUrl` instances. This Api has to be paginated because
Google only allows 50000 urls in a single Sitemap. The `SitemapController`
takes care of generating a `sitemapindex` if more than one Provider or
more than one pages are available. Otherwise it will deliver a the Sitemap
of the first Provider.

The `SitemapUrl` consists of the following properties:

* loc - Url to page.
* lastmod (optional) - Latest modification datetime.
* changefreq (optional) - Frequency of change (see
  `SitemapUrl::CHANGE_FREQUENCY_*` constants)
* priority (optional) - Priority of page in relation to other pages.
* alternateLinks (optional) - Alternate links like other representations
  or translations

The Sulu core provides a single Provider for pages (including homepage).
Custom modules can provide their own Providers that this URLs also will
be published over the `sitemap.xml`.

Example
-------

This is a simple example which assumes that the logic to load entities is
implemented in the Respository.

.. code-block:: php

    namespace AppBundle\Sitemap;

    use AppBundle\Entity\ExampleRepository;
    use Sulu\Bundle\WebsiteBundle\Sitemap\Sitemap;
    use Sulu\Bundle\WebsiteBundle\Sitemap\SitemapProviderInterface;
    use Sulu\Bundle\WebsiteBundle\Sitemap\SitemapUrl;

    class SitemapProvider implements SitemapProviderInterface
    {
        /**
         * @var ExampleRepository
         */
        private $repository;

        /**
         * @param ExampleRepository $repository
         */
        public function __construct(ExampleRepository $repository)
        {
            $this->repository = $repository;
        }

        /**
         * {@inheritdoc}
         */
        public function build($page, $portalKey, $locale)
        {
            $result = [];
            foreach ($this->repository->findAllForSitemap($page, self::PAGE_SIZE) as $item) {
                $result[] = new SitemapUrl($item->getRoute()->getPath(), $item->getChanged());
            }

            return $result;
        }

        /**
         * {@inheritdoc}
         */
        public function createSitemap($alias)
        {
            return new Sitemap($alias, $this->getMaxPage());
        }

        /**
         * {@inheritdoc}
         */
        public function getMaxPage()
        {
            return ceil($this->repository->countForSitemap() / self::PAGE_SIZE);
        }
    }

Now you can create a service for this class and add the tag
`<tag name="sulu.sitemap.provider" alias="{your link-type}"/>`.
