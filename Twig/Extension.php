<?php

namespace GaylordP\BootstrapComponentBundle\Twig;

use Symfony\Component\Form\FormView;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class Extension extends AbstractExtension
{
    private $twig;
    private $translator;

    public function __construct(
        Environment $twig,
        TranslatorInterface $translator
    ) {
        $this->twig = $twig;
        $this->translator = $translator;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter(
                'button',
                [$this, 'button'],
                ['is_safe' => ['html']]
            ),
            new TwigFilter(
                'button_link',
                [$this, 'button_link'],
                ['is_safe' => ['html']]
            ),
            new TwigFilter(
                'label',
                [$this, 'label'],
                ['is_safe' => ['html']]
            ),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'form_delete',
                [$this, 'formDelete'],
                ['is_safe' => ['html']]
            ),
            new TwigFunction(
                'form_edit',
                [$this, 'formEdit'],
                ['is_safe' => ['html']]
            ),
            new TwigFunction(
                'form_new',
                [$this, 'formNew'],
                ['is_safe' => ['html']]
            ),
        ];
    }

    public function button(
        string $text = null,
        string $color = null,
        string $ico = null,
        bool $icoOnly = false,
        array $attr = [],
        string $element = 'button'
    ): string {
        /*
         * For _delete_ ; _edit_ ; _new_ ; _show.
         */
        $originalColor = $color;
        $text = $this->findRegisteredText($originalColor, $text);
        $color = $this->findRegisteredColor($originalColor);
        $ico = $this->findRegisteredIco($originalColor, $ico);
        /*
         * End
         */

        $attr['class'] = ltrim(($attr['class'] ?? null) . ' btn');

        if (null !== $color) {
            $attr['class'] .= ' btn-' . $color;
        }

        if ('button' === $element && !array_key_exists('type', $attr)) {
            $attr['type'] = 'button';
        }

        return $this->twig->render('@BootstrapComponent/button/_button.html.twig', [
            'text' => $text,
            'element' => $element,
            'color' => $color,
            'ico' => $ico,
            'ico_only' => $icoOnly,
            'attr' => $attr,
        ]);
    }

    public function button_link(
        string $text = null,
        string $path,
        string $color = null,
        string $ico = null,
        bool $icoOnly = false,
        array $attr = []
    ): string {
        if (!array_key_exists('href', $attr)) {
            $attr['href'] = $path;
        }

        return $this->button(
            $text,
            $color,
            $ico,
            $icoOnly,
            $attr,
            'a',
        );
    }

    public function formDelete(
        string $btnText = null,
        string $btnIco = null,
        bool $btnIcoOnly = false,
        array $btnAttr = [],
        string $path = null
    ): string {
        if (!array_key_exists('type', $btnAttr)) {
            $btnAttr['type'] = 'submit';
        }

        return $this->twig->render('@BootstrapComponent/form/_form_delete.html.twig', [
            'btn_text' => $btnText,
            'btn_ico' => $btnIco,
            'btn_ico_only' => $btnIcoOnly,
            'btn_attr' => $btnAttr,
            'path' => $path
        ]);
    }

    public function formEdit(
        FormView $formView,
        string $btnText = null,
        string $btnIco = null,
        bool $btnIcoOnly = false,
        array $btnAttr = []
    ): string {
        if (!array_key_exists('type', $btnAttr)) {
            $btnAttr['type'] = 'submit';
        }

        return $this->twig->render('@BootstrapComponent/form/_form_edit.html.twig', [
            'form' => $formView,
            'btn_text' => $btnText,
            'btn_ico' => $btnIco,
            'btn_ico_only' => $btnIcoOnly,
            'btn_attr' => $btnAttr
        ]);
    }

    public function formNew(
        FormView $formView,
        string $btnText = null,
        string $btnIco = null,
        bool $btnIcoOnly = false,
        array $btnAttr = []
    ): string {
        if (!array_key_exists('type', $btnAttr)) {
            $btnAttr['type'] = 'submit';
        }

        return $this->twig->render('@BootstrapComponent/form/_form_new.html.twig', [
            'form' => $formView,
            'btn_text' => $btnText,
            'btn_ico' => $btnIco,
            'btn_ico_only' => $btnIcoOnly,
            'btn_attr' => $btnAttr
        ]);
    }

    public function label(
        string $text = null,
        string $color = null,
        string $ico = null,
        bool $icoOnly = false,
        array $attr = []
    ): string {
        /*
         * For _delete_ ; _edit_ ; _new_ ; _show.
         */
        $originalColor = $color;
        $text = $this->findRegisteredText($originalColor, $text);
        $color = $this->findRegisteredColor($originalColor);
        $ico = $this->findRegisteredIco($originalColor, $ico);
        /*
         * End
         */

        $attr['class'] = ltrim(($attr['class'] ?? null) . ' label');

        if (null !== $color) {
            $attr['class'] .= ' label-' . $color;
        }

        return $this->twig->render('@BootstrapComponent/label/_label.html.twig', [
            'text' => $text,
            'color' => $color,
            'ico' => $ico,
            'ico_only' => $icoOnly,
            'attr' => $attr,
        ]);
    }

    private function findRegisteredText(string $color, ?string $text): ?string
    {
        if (null === $text) {
            if ('_delete_' === $color) {
                return $this->translator->trans('action.delete', [], 'bootstrap_component');
            } elseif ('_edit_' === $color) {
                return $this->translator->trans('action.edit', [], 'bootstrap_component');
            } elseif ('_new_' === $color) {
                return $this->translator->trans('action.new', [], 'bootstrap_component');
            } elseif ('_show_' === $color) {
                return $this->translator->trans('action.show', [], 'bootstrap_component');
            }
        }

        return $text;
    }

    private function findRegisteredColor(string $color): ?string
    {
        if ('_delete_' === $color) {
            return 'danger';
        } elseif ('_edit_' === $color) {
            return 'primary';
        } elseif ('_new_' === $color) {
            return 'green';
        } elseif ('_show_' === $color) {
            return 'yellow';
        }

        return $color;
    }

    private function findRegisteredIco(string $color, ?string $ico): ?string
    {
        if (null === $ico) {
            if ('_delete_' === $color) {
                return $this->twig->render('@BootstrapComponent/ico/_delete.html.twig');
            } elseif ('_edit_' === $color) {
                return $this->twig->render('@BootstrapComponent/ico/_edit.html.twig');
            } elseif ('_new_' === $color) {
                return $this->twig->render('@BootstrapComponent/ico/_new.html.twig');
            } elseif ('_show_' === $color) {
                return $this->twig->render('@BootstrapComponent/ico/_show.html.twig');
            }
        }

        return $ico;
    }
}
