<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Forward;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ForwardController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/forwards",
     *      operationId="getForwardsList",
     *      tags={"Forwards"},
     *      summary="Get list of forwards",
     *      description="Returns paginated list of forwards with filtering and sorting",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="middle_name",
     *          description="Filter forwards by middle name (partial match)",
     *          required=false,
     *          in="query",
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Parameter(
     *          name="club_id",
     *          description="Filter forwards by club ID",
     *          required=false,
     *          in="query",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Parameter(
     *          name="sort",
     *          description="Sort field (default: id)",
     *          required=false,
     *          in="query",
     *          @OA\Schema(type="string", enum={"id", "middle_name", "club_id", "goals_scored", "assists", "penalty_minutes"})
     *      ),
     *      @OA\Parameter(
     *          name="order",
     *          description="Sort order (default: asc)",
     *          required=false,
     *          in="query",
     *          @OA\Schema(type="string", enum={"asc", "desc"})
     *      ),
     *      @OA\Parameter(
     *          name="per_page",
     *          description="Items per page (default: 1)",
     *          required=false,
     *          in="query",
     *          @OA\Schema(type="integer", minimum=1)
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/Forward")
     *              ),
     *              @OA\Property(
     *                  property="current_page",
     *                  type="integer",
     *                  example=1
     *              ),
     *              @OA\Property(
     *                  property="per_page",
     *                  type="integer",
     *                  example=1
     *              ),
     *              @OA\Property(
     *                  property="total",
     *                  type="integer",
     *                  example=5
     *              )
     *          )
     *       ),
     *      @OA\Response(response=401, description="Unauthorized"),
     *      @OA\Response(response=403, description="Forbidden")
     * )
     */
    // Получить всех нападающих (GET /api/forwards)
    public function index(Request $request)
    {
//        $forwards = Forward::with('club')->get();
//        return response()->json($forwards);

        // Фильтрация
        $query = Forward::with('club');
        if ($request->has('middle_name')) {
            $query->where('middle_name', 'like', '%' . $request->input('middle_name') . '%');
        }
        if ($request->has('club_id')) {
            $query->where('club_id', $request->input('club_id'));
        }

        // Сортировка
        $sortField = $request->input('sort', 'id');
        $sortOrder = $request->input('order', 'asc');
        $query->orderBy($sortField, $sortOrder);

        // Пагинация
        $perPage = $request->input('per_page', 5);
        $forwards = $query->paginate($perPage);

        return response()->json($forwards);
    }
    /**
     * @OA\Post(
     *      path="/api/forwards",
     *      operationId="createForward",
     *      tags={"Forwards"},
     *      summary="Create a new forward",
     *      description="Create and return a new forward",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Forward")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Forward created successfully",
     *          @OA\JsonContent(ref="#/components/schemas/Forward")
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="The given data was invalid."),
     *              @OA\Property(
     *                  property="errors",
     *                  type="object",
     *                  @OA\Property(
     *                      property="middle_name",
     *                      type="array",
     *                      @OA\Items(type="string", example="The middle name field is required.")
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(response=401, description="Unauthorized"),
     *      @OA\Response(response=403, description="Forbidden")
     * )
     */
    // Создать нападающего (POST /api/forwards)
    public function store(Request $request)
    {
        $request->validate([
            'middle_name' => 'required|string|max:100',
            'club_id' => 'required|exists:clubs,id',
            'goals_scored' => 'integer|min:0',
            'assists' => 'integer|min:0',
            'penalty_minutes' => 'integer|min:0',
        ]);

        $forward = Forward::create($request->all());
        return response()->json($forward, 201); // 201 - Created
    }
    /**
     * @OA\Get(
     *      path="/api/forwards/{id}",
     *      operationId="getForwardById",
     *      tags={"Forwards"},
     *      summary="Get a specific forward",
     *      description="Returns forward data by ID",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Forward ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer", format="int64", minimum=1)
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Forward")
     *      ),
     *      @OA\Response(response=401, description="Unauthorized"),
     *      @OA\Response(response=403, description="Forbidden"),
     *      @OA\Response(response=404, description="Forward not found")
     * )
     */
    // Получить нападающего по ID (GET /api/forwards/{id})
    public function show($id)
    {
        $forward = Forward::with('club')->findOrFail($id);
        return response()->json($forward);
    }
    /**
     * @OA\Put(
     *      path="/api/forwards/{id}",
     *      operationId="updateForward",
     *      tags={"Forwards"},
     *      summary="Update an existing forward",
     *      description="Updates and returns forward data",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Forward ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer", format="int64", minimum=1)
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Forward")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Forward updated successfully",
     *          @OA\JsonContent(ref="#/components/schemas/Forward")
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="The given data was invalid."),
     *              @OA\Property(
     *                  property="errors",
     *                  type="object",
     *                  @OA\Property(
     *                      property="middle_name",
     *                      type="array",
     *                      @OA\Items(type="string", example="The middle name field is required.")
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(response=401, description="Unauthorized"),
     *      @OA\Response(response=403, description="Forbidden"),
     *      @OA\Response(response=404, description="Forward not found")
     * )
     */
    // Обновить нападающего (PUT/PATCH /api/forwards/{id})
    public function update(Request $request, $id)
    {
        $forward = Forward::findOrFail($id);
        $request->validate([
            'middle_name' => 'required|string|max:100',
            'club_id' => 'required|exists:clubs,id',
            'goals_scored' => 'integer|min:0',
            'assists' => 'integer|min:0',
            'penalty_minutes' => 'integer|min:0',
        ]);

        $forward->update($request->all());
        return response()->json($forward);
    }
    /**
     * @OA\Delete(
     *      path="/api/forwards/{id}",
     *      operationId="deleteForward",
     *      tags={"Forwards"},
     *      summary="Delete a forward",
     *      description="Deletes a forward by ID",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Forward ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer", format="int64", minimum=1)
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Forward deleted successfully"
     *      ),
     *      @OA\Response(response=401, description="Unauthorized"),
     *      @OA\Response(response=403, description="Forbidden"),
     *      @OA\Response(response=404, description="Forward not found")
     * )
     */
    // Удалить нападающего (DELETE /api/forwards/{id})
    public function destroy($id)
    {
        Forward::destroy($id);
        return response()->noContent(); // 204 - No Content
    }
}
