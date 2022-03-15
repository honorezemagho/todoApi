<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *      definition="Todo",
 *      required={"name", "activity", "state", "dateline"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="name",
 *          description="name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="activity",
 *          description="activity",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="state",
 *          description="state",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="dateline",
 *          description="dateline",
 *          type="string",
 *          format="date"
 *      ),
 *      @SWG\Property(
 *          property="created_at",
 *          description="created_at",
 *          type="string",
 *          format="date-time"
 *      )
 * )
 */

/**
 * @SWG\Definition(
 *      definition="newTodo",
 *      required={"name", "activity", "dateline"},
 *      @SWG\Property(
 *          property="name",
 *          description="name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="activity",
 *          description="activity",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="dateline",
 *          description="dateline",
 *          type="string",
 *          format="date"
 *      )
 * )
 */


class Todo extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $hidden = ['updated_at'];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $create_rules = [
        'name' => 'required|string|max:255',
        'activity' => 'required|string',
        'dateline' => 'required|date|after:yesterday'
    ];

    public static $update_rules = [
        'state' => 'required|string|in:done,undone'
    ];
}
