<?php

namespace UserPackage\App\Repositories;

use Geekhives\BaseRepository\Service\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use UserPackage\App\Models\ExampleModel;

class ExampleRepository extends BaseRepository
{
    /**
     * @var ExampleModel
     */
    protected ExampleModel $model;

    /**
     * ExampleRepository constructor.
     * @param ExampleModel $exampleModel
     */
    public function __construct(ExampleModel $exampleModel)
    {
        parent::__construct();

        $this->model = $ticket;
    }

    /**
     * @return Builder
     */
    public function model(): Builder
    {
        return $this->model->query();
    }
}
