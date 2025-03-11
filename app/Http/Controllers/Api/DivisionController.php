<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Division;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *      path="/api/divisions",
 *      operationId="getDivisionsList",
 *      tags={"Divisions"},
 *      summary="Get list of divisions",
 *      description="Returns paginated",
 *      security={{"bearerAuth":{}}},
 *      @OA\Parameter(
 *          name="name",
 *          description="Filter divisions by name (partial match)",
 *          required=false,
 *          in="query",
 *          @OA\Schema(type="string")
 *      ),
 *      @OA\Parameter(
 *          name="sort",
 *          description="Sort field ",
 *          required=false,
 *          in="query",
 *          @OA\Schema(type="string", enum={"id", "name"})
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
 *                  @OA\Items(ref="#/components/schemas/Division")
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
class DivisionController extends Controller
{

    // Получить все дивизионы (GET /api/divisions)
    public function index(Request $request)
    {
//        $divisions = Division::all();
//        return response()->json($divisions);


        $query = Division::query();
        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');// Фильтрация
        }


        $sortField = $request->input('sort', 'id');
        $sortOrder = $request->input('order', 'asc');
        $query->orderBy($sortField, $sortOrder);// Сортировка


        $perPage = $request->input('per_page', 5);
        $divisions = $query->paginate($perPage); // Пагинация

        return response()->json($divisions);
    }
    /**
     * @OA\Post(
     *      path="/api/divisions",
     *      operationId="groupsStore",
     *      tags={"Divisions"},
     *      summary="Create a group",
     *      description="Create group and save it",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            required={"name"},
     *            @OA\Property(property="name", type="string", format="string", example="Test name"),
     *         ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Retunred created group"
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Error: unauthorized (need to login)"
     *       ),
     *      @OA\Response(
     *          response=403,
     *          description="Error: forbidden (not enough rights)"
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Error: unable to process data (incorrect input)"
     *       ),
     *     )
     *
     * Create group
     */
    // Создать дивизион (POST /api/divisions)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:divisions',
        ]);

        $division = Division::create($request->all());
        return response()->json($division, 201); // 201 - Created
    }
    /**
     * @OA\Get(
     *      path="/api/divisions/{id}",
     *      operationId="getDivisionById",
     *      tags={"Divisions"},
     *      summary="Get specific division",
     *      description="Returns division data by ID",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Division ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer",
     *              format="int64",
     *              minimum=1
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Division")
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Unauthenticated.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Forbidden.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Division not found.")
     *          )
     *      )
     * )
     */
    // Получить дивизион по ID (GET /api/divisions/{id})
    public function show($id)
    {
        $division = Division::findOrFail($id);
        return response()->json($division);
    }
    /**
     * @OA\Put(
     *      path="/api/divisions/{id}",
     *      operationId="updateDivision",
     *      tags={"Divisions"},
     *      summary="Update existing division",
     *      description="Updates and returns division data",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Division ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer",
     *              format="int64",
     *              minimum=1
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Division")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Division")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Invalid input.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Unauthenticated.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Forbidden.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Division not found.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation Error",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="The given data was invalid."),
     *              @OA\Property(
     *                  property="errors",
     *                  type="object",
     *                  @OA\Property(
     *                      property="name",
     *                      type="array",
     *                      @OA\Items(type="string", example="The name field is required.")
     *                  )
     *              )
     *          )
     *      )
     * )
     */
    // Обновить дивизион (PUT/PATCH /api/divisions/{id})
    public function update(Request $request, $id)
    {
        $division = Division::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:100|unique:divisions,name,' . $division->id,
        ]);

        $division->update($request->all());
        return response()->json($division);
    }

    /**
     * @OA\Delete(
     *      path="/api/divisions/{id}",
     *      operationId="divisionDestroy",
     *      tags={"Divisions"},
     *      summary="Delete division",
     *      description="Delete division by ID",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Division ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Division deleted successfully"
     *       ),
     *      @OA\Response(response=401, description="Unauthorized"),
     *      @OA\Response(response=403, description="Forbidden"),
     *      @OA\Response(response=404, description="Division not found")
     * )
     */
    // Удалить дивизион (DELETE /api/divisions/{id})
    public function destroy($id)
    {
        Division::destroy($id);
        return response()->noContent(); // 204 - No Content
    }
}
