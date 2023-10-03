<?php

namespace UserPackage\App\Transformers;

use UserPackage\App\Models\Ticket;
use League\Fractal\TransformerAbstract;

class ExampleTransformer extends TransformerAbstract
{
    /**
     * ExampleTransformer constructor.
     * @param ExampleModel $exampleModel
     * @return array
     */
    public function transform(ExampleModel $exampleModel): array
    {
        return [];
    }
}
