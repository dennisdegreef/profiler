<?php

/**
 * ProfilerInterface.php
 */
namespace Link0\Profiler;

/**
 * Interface ProfilerInterface
 *
 * @package Link0\Profiler
 * @author  Dennis de Greef <github@link0.net>
 */
interface ProfilerInterface
{
    /**
     * @param ProfilerAdapterInterface $profilerAdapter
     *
     * @return ProfilerInterface
     */
    public function setProfilerAdapter(ProfilerAdapterInterface $profilerAdapter);

    /**
     * @return ProfilerAdapterInterface
     */
    public function getProfilerAdapter();

    /**
     * @return PersistenceService
     */
    public function getPersistenceService();

    /**
     * @param ProfileFactoryInterface $profileFactory
     *
     * @return ProfilerInterface
     */
    public function setProfileFactory(ProfileFactoryInterface $profileFactory);

    /**
     * @return ProfileFactoryInterface
     */
    public function getProfileFactory();

    /**
     * @return ProfilerInterface
     */
    public function start();

    /**
     * @return bool
     */
    public function isRunning();

    /**
     * @return ProfileInterface
     */
    public function stop();
}
