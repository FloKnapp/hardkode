<?php

namespace Hardkode;

use Hardkode\Exception\ContainerException;
use Hardkode\Exception\NotFoundException;
use Psr\Container\ContainerInterface;

/**
 * Class Container
 * @package Hardkode
 */
class Container implements ContainerInterface
{

    private $items;

    /**
     * @param string $id
     * @return mixed|void
     *
     * @throws NotFoundException
     */
    public function get($id)
    {
        $item = $this->items[$id] ?? null;

        if (null === $item) {
            throw new NotFoundException('Requested item "' . $id . '" not found in Container. Class: ' . get_called_class());
        }

        return $this->items[$id];
    }

    /**
     * @param string $id
     * @param object $object
     * @param array $aliases
     *
     * @throws ContainerException
     */
    public function set($id, $object, array $aliases = [])
    {
        $aliases[] = $id;

        foreach ($aliases as $alias) {

            if ($this->has($alias)) {
                throw new ContainerException('Item "' . $id . '" is already defined.');
            }

            $this->items[$alias] = $object;

        }
    }

    /**
     * @param string $id
     * @return bool
     */
    public function has($id): bool
    {
        return !empty($items[$id]);
    }

}