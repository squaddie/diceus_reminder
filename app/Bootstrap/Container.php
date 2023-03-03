<?php

namespace Reminder\App\Bootstrap;

use ReflectionClass;
use ReflectionException;

class Container
{
    /**
     * @throws ReflectionException
     */
    public function get(string $id): object
    {
        return $this->prepareObject($id);
    }

    /**
     * @throws ReflectionException
     */
    private function prepareObject(string $class): object
    {
        $classReflector = new ReflectionClass($class);

        $constructReflector = $classReflector->getConstructor();
        if (empty($constructReflector)) {
            return new $class;
        }

        $constructArguments = $constructReflector->getParameters();
        if (empty($constructArguments)) {
            return new $class;
        }

        $args = [];
        foreach ($constructArguments as $argument) {
            $argumentType = $argument->getType()->getName();
            $args[$argument->getName()] = $this->get($argumentType);
        }

        return new $class(...$args);
    }

}