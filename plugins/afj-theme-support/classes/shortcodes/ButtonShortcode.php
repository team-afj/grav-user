<?php

namespace Grav\Plugin\Shortcodes;

use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class ButtonShortcode extends Shortcode
{
    public function init()
    {
        $this->shortcode->getHandlers()->add('button', function (ShortcodeInterface $sc) {
            // Add assets
            // $this->shortcode->addAssets('css',
            // 'plugin://shortcode-ui/css/ui-polaroid.css');

            return $this->twig->processTemplate(
                'partials/ui-button.html.twig',
                [
                    'link' => $sc->getParameter("link"),
                    'color' => $sc->getParameter("color"),
                    'target' => $sc->getParameter("target"),
                    'content' => $sc->getContent(),
                    'shortcode' => $sc,
                ]
            );
        });
    }
}
