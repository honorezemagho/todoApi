<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateTodoAPIRequest;
use App\Http\Requests\API\UpdateTodoAPIRequest;
use App\Models\Todo;
use App\Repositories\TodoRepository;
use Illuminate\Http\Request;
use App\Http\Resources\TodoResource;
use Response;

/**
 * Class TodoController
 * @package App\Http\Controllers\API
 */

class TodoAPIController extends AppBaseController
{
    /** @var  TodoRepository */
    private $todoRepository;

    public function __construct(TodoRepository $todoRepo)
    {
        $this->todoRepository = $todoRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/todos",
     *      summary="Get a listing of the Todos.",
     *      tags={"Todo"},
     *      description="Get all Todos",
     *      produces={"application/json"},
     * 
     *  @SWG\Parameter(
     *      required=false,
     *      name="state",
     *      description="search by state (done, undone)",
     *      in="query", type="string",
     *      enum={"done", "undone"}),
     * 
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="array",
     *                  @SWG\Items(ref="#/definitions/Todo")
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function index(Request $request)
    {

       if($request->state)

       {
         $request->validate([
            'state' => 'required|in:done,undone'
        ]);
       }


        $todos = $this->todoRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        $result['todos'] = TodoResource::collection($todos);

        $total_done_tasks = $this->todoRepository->allQuery(['state' => 'done'])->get()->count();;
        $total_tasks = $this->todoRepository->allQuery([])->get()->count();

        $result['productivity_score'] = $total_done_tasks / $total_tasks;

        return $this->sendResponse($result, 'Todos retrieved successfully');
    }

    /**
     * @param CreateTodoAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/todos",
     *      summary="Create a new todo",
     *      tags={"Todo"},
     *      description="Store Todo",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Todo that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/newTodo")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Todo"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateTodoAPIRequest $request)
    {
        $input = $request->all();
        $input['state'] = 'undone';

        $todo = $this->todoRepository->create($input);

        return $this->sendResponse(new TodoResource($todo), 'Todo saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/todos/{id}",
     *      summary="Display the specified Todo",
     *      tags={"Todo"},
     *      description="Get Todo",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Todo",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Todo"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function show($id)
    {
        /** @var Todo $todo */
        $todo = $this->todoRepository->find($id);

        if (empty($todo)) {
            return $this->sendError('Todo not found');
        }

        return $this->sendResponse(new TodoResource($todo), 'Todo retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateTodoAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/todos/{id}",
     *      summary="Update the status of the todo",
     *      tags={"Todo"},
     *      description="Update Todo",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Todo",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="update the state of the todo",
     *          required=false,
     *          @SWG\Schema(
     *           @SWG\Property(
     *            property="state",
     *            description="state of todo (done or undone)",
     *            type="string"
     *         ),
     *          ),
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Todo"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateTodoAPIRequest $request)
    {
        $input = $request->all();

        /** @var Todo $todo */
        $todo = $this->todoRepository->find($id);

        if (empty($todo)) {
            return $this->sendError('Todo not found');
        }

        $todo = $this->todoRepository->update($input, $id);

        return $this->sendResponse(new TodoResource($todo), 'Todo updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/todos/{id}",
     *      summary="Remove the specified Todo by id",
     *      tags={"Todo"},
     *      description="Delete Todo",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Todo",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function destroy($id)
    {
        /** @var Todo $todo */
        $todo = $this->todoRepository->find($id);

        if (empty($todo)) {
            return $this->sendError('Todo not found');
        }

        $todo->delete();

        return $this->sendSuccess('Todo deleted successfully');
    }
}
