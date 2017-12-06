<?php declare(strict_types=1);

namespace Ellipse\Container;

use Psr\Container\ContainerInterface;

class OverriddenContainer implements ContainerInterface
{
    /**
     * The delegate.
     *
     * @var \Psr\Container\ContainerInterface
     */
    private $delegate;

    /**
     * The associative array of alias => value pairs.
     *
     * @var array
     */
    private $overrides;

    /**
     * Set up a reflection container with the given delegate and overrides.
     *
     * @param \Psr\Container\ContainerInterface $delegate
     * @param array                             $overrides
     */
    public function __construct(ContainerInterface $delegate, array $overrides)
    {
        $this->delegate = $delegate;
        $this->overrides = $overrides;
    }

    /**
     * @inheritdoc
     */
    public function get($id)
    {
        return $this->overrides[$id] ?? $this->delegate->get($id);
    }

    /**
     * @inheritdoc
     */
    public function has($id)
    {
        return array_key_exists($id, $this->overrides) ?: $this->delegate->has($id);
    }
}
