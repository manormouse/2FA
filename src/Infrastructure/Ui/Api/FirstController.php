<?php declare(strict_types=1);

namespace App\Infrastructure\Ui\Api;

use App\Application\FirstService;

class FirstController
{
    /** @var FirstService */
    private $firstService;

    public function __construct(FirstService $firstService)
    {
        $this->firstService = $firstService;
    }

    public function test()
    {
        $this->firstService->execute();
        exit('here');
    }
}
