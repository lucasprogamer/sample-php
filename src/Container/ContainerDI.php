<?php

namespace Src\Container;


class ContainerDI
{
    protected $bindings = [];

    public function bind($abstract, $concrete)
    {
        $this->bindings[$abstract] = $concrete;
    }

    public function resolve($abstract)
    {
        if (isset($this->bindings[$abstract])) {
            $concrete = $this->bindings[$abstract];
            if ($concrete instanceof \Closure) {
                return $concrete($this);
            } else {
                return $this->build($concrete);
            }
        }

        throw new \Exception("Dependency [$abstract] not found in the container.");
    }

    protected function build($concrete)
    {
        $reflector = new \ReflectionClass($concrete);

        if (!$reflector->isInstantiable()) {
            throw new \Exception("Class [$concrete] is not instantiable.");
        }

        $constructor = $reflector->getConstructor();

        if (is_null($constructor)) {
            return new $concrete;
        }

        $dependencies = $constructor->getParameters();
        $resolvedDependencies = $this->resolveDependencies($dependencies);

        return $reflector->newInstanceArgs($resolvedDependencies);
    }

    protected function resolveDependencies($dependencies)
    {
        $resolvedDependencies = [];

        foreach ($dependencies as $dependency) {
            if ($dependency->getClass()) {
                $resolvedDependencies[] = $this->resolve($dependency->getClass()->name);
            } else {
                throw new \Exception("Unable to resolve dependency [{$dependency->name}]");
            }
        }

        return $resolvedDependencies;
    }
}
