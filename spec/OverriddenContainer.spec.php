<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Container\ContainerInterface;

use Ellipse\Container\OverriddenContainer;

describe('ReflectionContainer', function () {

    beforeEach(function () {

        $this->overridden = new class {};

        $this->delegate = mock(ContainerInterface::class);
        $this->overrides = ['overridden' => $this->overridden];

        $this->container = new OverriddenContainer($this->delegate->get(), $this->overrides);

    });

    it('should implement ContainerInterface', function () {

        expect($this->container)->toBeAnInstanceOf(ContainerInterface::class);

    });

    describe('->get()', function () {

        context('when the id is in the associative array of alias => value pairs', function () {

            it('should return the value associated to the given alias', function () {

                $test = $this->container->get('overridden');

                expect($test)->toBe($this->overridden);

            });

        });

        context('when the id is not in the associative array of alias => value pairs', function () {

            it('should proxy the underlying container ->get() method', function () {

                $instance = new class () {};

                $this->delegate->get->with('id')->returns($instance);

                $test = $this->container->get('id');

                expect($test)->toBe($instance);

            });

        });

    });

    describe('->has()', function () {

        context('when the id is in the associative array of alias => value pairs', function () {

            it('should return true', function () {

                $test = $this->container->has('overridden');

                expect($test)->toBeTruthy();

            });

        });

        context('when the id is not in the associative array of alias => value pairs', function () {

            context('when the delegate ->has() method returns true', function () {

                it('should return true', function () {

                    $this->delegate->has->with('id')->returns(true);

                    $test = $this->container->has('id');

                    expect($test)->toBeTruthy();

                });

            });

            context('when the delegate ->has() method returns false', function () {

                it('should return false', function () {

                    $this->delegate->has->with('id')->returns(false);

                    $test = $this->container->has('id');

                    expect($test)->toBeFalsy();

                });

            });

        });

    });

});
