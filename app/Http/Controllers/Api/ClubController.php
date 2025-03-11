<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Club;
use OpenApi\Annotations as OA;
use Illuminate\Http\Request;

class ClubController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/clubs",
     *      operationId="getClubsList",
     *      tags={"Clubs"},
     *      summary="Get list of clubs",
     *      description="Returns paginated list of clubs with filtering and sorting",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="name",
     *          description="Filter clubs by name (partial match)",
     *          required=false,
     *          in="query",
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Parameter(
     *          name="division_id",
     *          description="Filter clubs by division ID",
     *          required=false,
     *          in="query",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Parameter(
     *          name="sort",
     *          description="Sort field (default: id)",
     *          required=false,
     *          in="query",
     *          @OA\Schema(type="string", enum={"id", "name", "division_id", "foundation_year"})
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
     *                  @OA\Items(ref="#/components/schemas/Club")
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
    //Получить все клубы (GET /api/clubs)
    public function index(Request $request)
    {
//        $clubs = Club::with('division')->get();
//        return response()->json($clubs);

        // Фильтрация
        $query = Club::with('division');
        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }
        if ($request->has('division_id')) {
            $query->where('division_id', $request->input('division_id'));
        }

        // Сортировка
        $sortField = $request->input('sort', 'id');
        $sortOrder = $request->input('order', 'asc');
        $query->orderBy($sortField, $sortOrder);

        // Пагинация
        $perPage = $request->input('per_page', 5);
        $clubs = $query->paginate($perPage);

        return response()->json($clubs);
    }
    /**
     * @OA\Post(
     *      path="/api/clubs",
     *      operationId="createClub",
     *      tags={"Clubs"},
     *      summary="Create a new club",
     *      description="Create and return a new club",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Club")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Club created successfully",
     *          @OA\JsonContent(ref="#/components/schemas/Club")
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
     *                      property="name",
     *                      type="array",
     *                      @OA\Items(type="string", example="The name field is required.")
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(response=401, description="Unauthorized"),
     *      @OA\Response(response=403, description="Forbidden")
     * )
     */
    // Создать клуб (POST /api/clubs)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'coach_first_name' => 'required|string|max:100',
            'coach_middle_name' => 'nullable|string|max:100',
            'coach_last_name' => 'required|string|max:100',
            'foundation_year' => 'required|integer|min:1900|max:' . date('Y'),
            'division_id' => 'required|exists:divisions,id',
            'coach_photo' => 'nullable|url',
        ]);

        $club = Club::create($request->all());
        return response()->json($club, 201); // 201 - Created
    }
    /**
     * @OA\Get(
     *      path="/api/clubs/{id}",
     *      operationId="getClubById",
     *      tags={"Clubs"},
     *      summary="Get a specific club",
     *      description="Returns club data by ID",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Club ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer", format="int64", minimum=1)
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Club")
     *      ),
     *      @OA\Response(response=401, description="Unauthorized"),
     *      @OA\Response(response=403, description="Forbidden"),
     *      @OA\Response(response=404, description="Club not found")
     * )
     */
    // Получить клуб по ID (GET /api/clubs/{id})
    public function show($id)
    {
        $club = Club::with('division')->findOrFail($id);
        return response()->json($club);
    }
    /**
     * @OA\Put(
     *      path="/api/clubs/{id}",
     *      operationId="updateClub",
     *      tags={"Clubs"},
     *      summary="Update an existing club",
     *      description="Updates and returns club data",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Club ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer", format="int64", minimum=1)
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Club")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Club updated successfully",
     *          @OA\JsonContent(ref="#/components/schemas/Club")
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
     *                      property="name",
     *                      type="array",
     *                      @OA\Items(type="string", example="The name field is required.")
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(response=401, description="Unauthorized"),
     *      @OA\Response(response=403, description="Forbidden"),
     *      @OA\Response(response=404, description="Club not found")
     * )
     */
    // Обновить клуб (PUT/PATCH /api/clubs/{id})
    public function update(Request $request, $id)
    {
        $club = Club::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:100',
            'coach_first_name' => 'required|string|max:100',
            'coach_middle_name' => 'nullable|string|max:100',
            'coach_last_name' => 'required|string|max:100',
            'foundation_year' => 'required|integer|min:1900|max:' . date('Y'),
            'division_id' => 'required|exists:divisions,id',
        ]);

        $club->update($request->all());
        return response()->json($club);
    }
    /**
     * @OA\Delete(
     *      path="/api/clubs/{id}",
     *      operationId="deleteClub",
     *      tags={"Clubs"},
     *      summary="Delete a club",
     *      description="Deletes a club by ID",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Club ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer", format="int64", minimum=1)
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Club deleted successfully"
     *      ),
     *      @OA\Response(response=401, description="Unauthorized"),
     *      @OA\Response(response=403, description="Forbidden"),
     *      @OA\Response(response=404, description="Club not found")
     * )
     */
    // Удалить клуб (DELETE /api/clubs/{id})
    public function destroy($id)
    {
        Club::destroy($id);
        return response()->noContent(); // 204 - No Content
    }

    // Получить игроков клуба (GET /api/clubs/{id}/forwards)
    public function forwards($id)
    {
        $club = Club::with('forwards')->findOrFail($id);
        return response()->json($club->forwards);
    }
}
