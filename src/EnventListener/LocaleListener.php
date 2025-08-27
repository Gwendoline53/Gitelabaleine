<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\KernelEvents;

#[AsEventListener(event: KernelEvents::REQUEST, priority: 20)]
class LocaleListener
{

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $session = $request->getSession();

        if ($request->query->get('locale')) {
            $locale = $request->query->get('locale');
            $session->set('_locale', $locale);
        } elseif ($session->has('_locale')) {
            $locale = $session->get('_locale');
        } else {
            $locale = $request->getPreferredLanguage(['fr', 'en']);
        }

        $request->setLocale($locale);
    }
}
