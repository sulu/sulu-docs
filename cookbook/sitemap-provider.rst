Provider for XML-Sitemap
========================

``SitemapProvider`` classes are used to load data for the XML sitemap. Each provider
returns an array of ``SitemapUrl`` instances. The API is paginated because Google
limits a single sitemap file to 50,000 URLs. The ``SitemapController`` generates a
``sitemapindex`` automatically when more than one provider exists or when a provider
spans multiple pages; otherwise it delivers the single sitemap directly.

The ``SitemapUrl`` constructor accepts the following arguments:

* ``loc`` - Absolute URL to the page.
* ``locale`` - Locale of the page.
* ``defaultLocale`` - Default locale of the page.
* ``lastmod`` (optional) - Latest modification datetime (``\DateTimeInterface``).
* ``changefreq`` (optional) - Frequency of change (see ``SitemapUrl::CHANGE_FREQUENCY_*`` constants).
* ``priority`` (optional) - Priority of page relative to other pages (float).
* ``attributes`` (optional) - Additional attributes passed to the sitemap template.
* ``alternateLinks`` (optional) - Added via ``addAlternateLink()`` after construction.

Sulu provides built-in providers for pages (including the homepage) and, when
``SuluArticleBundle`` is enabled, for articles. Custom providers add their own
URLs to the sitemap.

Example
-------

The recommended approach is to extend ``AbstractSitemapProvider``. It implements
``createSitemap()`` for you (you rarely need to override it) and provides a default
``getMaxPage()`` that returns ``1``, meaning a single page of up to ``PAGE_SIZE``
(50,000) URLs. Override ``getMaxPage()`` when your provider exceeds that limit —
return the total number of pages — or return ``0`` to exclude the provider entirely
for a given host.

.. code-block:: php

    <?php

    namespace App\Sitemap;

    use App\Repository\ExampleRepository;
    use Sulu\Bundle\WebsiteBundle\Sitemap\AbstractSitemapProvider;
    use Sulu\Bundle\WebsiteBundle\Sitemap\Sitemap;
    use Sulu\Bundle\WebsiteBundle\Sitemap\SitemapUrl;

    class ExampleSitemapProvider extends AbstractSitemapProvider
    {
        public function __construct(private ExampleRepository $repository)
        {
        }

        public function build($page, $scheme, $host): array
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

        public function getAlias(): string
        {
            return 'myalias';
        }

        public function createSitemap($scheme, $host): Sitemap
        {
            return new Sitemap($this->getAlias(), $this->getMaxPage($scheme, $host));
        }

        public function getMaxPage($scheme, $host): int
        {
            if ($host !== 'example.org') {
                // If the pages are only for a specific host.
                return 0;
            }

            return (int) ceil($this->repository->countForSitemap() / self::PAGE_SIZE);
        }
    }

If you have disabled autowiring, add the tag manually:

.. code-block:: yaml

    # config/services.yaml
    App\Sitemap\ExampleSitemapProvider:
        tags:
            - { name: sulu.sitemap.provider }
