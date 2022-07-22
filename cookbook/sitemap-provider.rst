Provider for XML-Sitemap
========================

`SitemapProvider` are used to load data for the XML-Sitemap. It returns
an array of `SitemapUrl` instances. This API has to be paginated because
Google only allows 50000 urls in a single Sitemap. The `SitemapController`
takes care of generating a `sitemapindex` if more than one Provider or
more than one pages are available. Otherwise it will deliver a the Sitemap
of the first Provider.

The `SitemapUrl` consists of the following properties:

* loc - Url to page.
* locale - Locale of the page
* defaultLocale - Default locale of the page
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
implemented in the Repository.

.. code-block:: php

    <?php

    namespace AppBundle\Sitemap;

    use AppBundle\Entity\ExampleRepository;
    use Sulu\Bundle\WebsiteBundle\Sitemap\Sitemap;
    use Sulu\Bundle\WebsiteBundle\Sitemap\SitemapProviderInterface;
    use Sulu\Bundle\WebsiteBundle\Sitemap\SitemapUrl;

    class SitemapProvider implements SitemapProviderInterface
    {
        public function __construct(private ExampleRepository $repository)
        {
        }

        public function build($page, $scheme, $host)
        {
            $result = [];
            foreach ($this->repository->findAllForSitemap($page, self::PAGE_SIZE) as $item) {
                $result[] = new SitemapUrl(
                    $scheme . '://' . $host . $item->getUrl(),
                    $item->getLocale(),
                    $item->getDefaultLocale(),
                    $item->getChanged()
                );
            }

            return $result;
        }

        public function getAlias()
        {
            return 'myalias';
        }

        public function createSitemap($scheme, $host)
        {
            return new Sitemap($this->getAlias(), $this->getMaxPage($scheme, $host));
        }

        public function getMaxPage($scheme, $host)
        {
            if ($host !== 'example.org') {
                // If the pages are only for a specific
                return 0;
            }

            return ceil($this->repository->countForSitemap() / self::PAGE_SIZE);
        }
    }

If you are not using autowiring you need to tag the service with `sulu.sitemap.provider`.
