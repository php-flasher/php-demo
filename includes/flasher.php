<?php

/*
 * This file is part of the PHPFlasher package.
 * (c) Younes KHOUBZA <younes.khoubza@gmail.com>
 */

$config = new \Flasher\Prime\Config\Config(require __DIR__.'/../config/flasher.php');

$eventDispatcher = new \Flasher\Prime\EventDispatcher\EventDispatcher();
$eventDispatcher->addSubscriber(new \Flasher\Prime\EventDispatcher\EventListener\StampsListener());
$eventDispatcher->addSubscriber(new \Flasher\Prime\EventDispatcher\EventListener\RemoveListener());
$eventDispatcher->addSubscriber(new \Flasher\Prime\EventDispatcher\EventListener\ResponseListener(new \Flasher\Prime\Filter\Filter()));
$eventDispatcher->addSubscriber(new \Flasher\Prime\EventDispatcher\EventListener\FilterListener(new \Flasher\Prime\Filter\Filter()));

class flasher implements \Flasher\Prime\Storage\StorageInterface
{
    const ENVELOPES_NAMESPACE = 'flasher::envelopes';

    public function all()
    {
        return $_SESSION[self::ENVELOPES_NAMESPACE] ?? array();
    }

    public function add($envelopes)
    {
        $envelopes = is_array($envelopes) ? $envelopes : func_get_args();

        $_SESSION[self::ENVELOPES_NAMESPACE] = array_merge($this->all(), $envelopes);
    }

    public function remove($envelopes)
    {
        $envelopes = is_array($envelopes) ? $envelopes : func_get_args();
        $map = \Flasher\Prime\Stamp\UuidStamp::indexByUuid($envelopes);

        $store = array_filter($this->all(), function (Flasher\Prime\Envelope $envelope) use ($map) {
            $uuid = $envelope->get('Flasher\Prime\Stamp\UuidStamp')->getUuid();

            return !isset($map[$uuid]);
        });

        $_SESSION[self::ENVELOPES_NAMESPACE] = $store;
    }

    public function clear()
    {
        $_SESSION[self::ENVELOPES_NAMESPACE] = array();
    }

    public function update($envelopes)
    {
        $envelopes = is_array($envelopes) ? $envelopes : func_get_args();
        $map = \Flasher\Prime\Stamp\UuidStamp::indexByUuid($envelopes);

        $store = $this->all();
        foreach ($store as $index => $envelope) {
            $uuid = $envelope->get('Flasher\Prime\Stamp\UuidStamp')->getUuid();

            if (!isset($map[$uuid])) {
                continue;
            }

            $store[$index] = $map[$uuid];
        }

        $_SESSION[self::ENVELOPES_NAMESPACE] = $store;
    }
}

$storageManager = new \Flasher\Prime\Storage\StorageManager(new SessionStorage(), $eventDispatcher);

$flasher = new \Flasher\Prime\Flasher($config);
$flasher->addFactory('toastr', new \Flasher\Toastr\Prime\ToastrFactory($storageManager));

$resourceManager = new \Flasher\Prime\Response\Resource\ResourceManager($config, new \Flasher\Prime\Template\Engine());
$resourceManager->addScripts('toastr', $config->get('adapters.toastr.scripts', array()));
$resourceManager->addStyles('toastr', $config->get('adapters.toastr.styles', array()));
$resourceManager->addOptions('toastr', $config->get('adapters.toastr.options', array()));

$responseManager = new \Flasher\Prime\Response\ResponseManager($storageManager, $eventDispatcher, $resourceManager);
$responseManager->addPresenter('html', new \Flasher\Prime\Response\Presenter\HtmlPresenter());

function flasher(): Flasher\Prime\FlasherInterface
{
    global $flasher;

    return $flasher;
}

function flasher_render(array $criteria = array(), $format = 'html', array $context = array())
{
    global $responseManager;

    echo $responseManager->render($criteria, $format, $context);
}
