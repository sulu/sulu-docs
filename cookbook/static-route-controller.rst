Create a static route controller
================================

Sometimes you have static symfony routes like a custom search controller or any other
route which is not a sulu content page.

In order to use the same ``base`` template for these custom routes, you need to provide the correct
attributes to your template. To do this, you can use the``TemplateAttributeResolver`` service in your controller:

.. code-block:: php

    <?php

    namespace App\Controller\Website;

    use Sulu\Bundle\HttpCacheBundle\Cache\SuluHttpCache;
    use Sulu\Bundle\WebsiteBundle\Resolver\TemplateAttributeResolverInterface;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Twig\Environment;

    class StaticController
    {
        /**
         * @Route("/custom", name="app_custom")
         */
        public function indexAction(
            TemplateAttributeResolverInterface $resolver,
            Environment $twig
        ): Response {
            $response = new Response($twig->render(
                'static/custom.html.twig',
                $resolver->resolve([
                    'customAttribute' => 'parameter',
                ])
            ));

            // Cached response:
            $response->setPublic();
            $response->setMaxAge(240);
            $response->setSharedMaxAge(240);

            // Server Cache Lifetime (how long the server should cache the page in seconds)
            $response->headers->set(SuluHttpCache::HEADER_REVERSE_PROXY_TTL, '604800'); // 604800 seconds = 1 week

            // Uncached response private response (for controller containing user specific data):
            // $response->setPrivate();
            // $response->setMaxAge(0);
            // $response->setSharedMaxAge(0);
            // $response->headers->addCacheControlDirective('no-cache', true);
            // $response->headers->addCacheControlDirective('must-revalidate', true);
            // $response->headers->addCacheControlDirective('no-store', true);

            return $response;
        }
    }

Now your ``templates/static/custom.html.twig`` can use the same base template as your pages:

.. code-block:: twig

    {% extends "base.html.twig" %}

    {% block content %}
        {{ customAttribute }}
    {% endblock %}
