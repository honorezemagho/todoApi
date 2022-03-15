<?php

namespace App\Repositories;

use App\Models\Todo;
use App\Repositories\BaseRepository;

/**
 * Class TodoRepository
 * @package App\Repositories
 * @version March 15, 2022, 8:43 am UTC
*/

class TodoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'activity',
        'state',
        'dateline'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Todo::class;
    }
}
