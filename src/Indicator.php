<?php

namespace Marijoo\Fields;

use Laravel\Nova\Fields\Field;

class Indicator extends Field
{

    /**
     * Indicates if the element should be shown on the creation view.
     *
     * @var bool
     */
    public $showOnCreation = false;

    /**
     * Indicates if the element should be shown on the update view.
     *
     * @var bool
     */
    public $showOnUpdate = false;

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'indicator-field';

    /**
     * The callback to be used to hide the field.
     *
     * @var \Closure
     */
    public $hideCallback;

    /**
     * Specify the labels that should be displayed.
     *
     * @param  array  $labels
     * @return $this
     */
    public function labels(array $labels): self
    {
        return $this->withMeta(['labels' => $labels, 'withoutLabels' => false]);
    }

    /**
     * Specify the colours that should be displayed.
     *
     * @param  array  $colors
     * @return $this
     */
    public function colors(array $colors): self
    {
        return $this->withMeta(['colors' => $colors]);
    }

    /**
     * The label to display when the value is not one of the defined statuses.
     *
     * @param  string $label
     * @return $this
     */
    public function unknown(string $label): self
    {
        return $this->withMeta(['unknownLabel' => $label]);
    }

    /**
     * Display the raw value instead of a label.
     *
     * @return $this
     */
    public function withoutLabels(): self
    {
        return $this->withMeta(['withoutLabels' => true]);
    }

    /**
     * Define the callback or value(s) that should be used to hide the field.
     *
     * @param  callable|array|mixed  $hideCallback
     * @return $this
     */
    public function shouldHide($hideCallback): self
    {
        $this->hideCallback = $hideCallback;

        return $this;
    }

    /**
     * Define that the field should be hidden if falsy (0, false, null, '')
     *
     * @return $this
     */
    public function shouldHideIfNo(): self
    {
        $this->hideCallback = function ($value) {
            return !$value;
        };

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function resolveForDisplay($resource, ?string $attribute = null): void
    {
        parent::resolveForDisplay($resource, $attribute);

        if (is_null($this->hideCallback)) {
            return;
        }

        if (is_callable($this->hideCallback)) {
            $shouldHide = call_user_func($this->hideCallback, $this->value, $resource);
        } elseif (is_array($this->hideCallback)) {
            $shouldHide = in_array($this->value, $this->hideCallback, false);
        } else {
            $shouldHide = $this->value == $this->hideCallback;
        }

        $this->withMeta(['shouldHide' => (bool) $shouldHide]);
    }
}
