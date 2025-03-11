<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @OA\Schema(
 *     schema="Club",
 *     type="object",
 *     title="Club",
 *     required={"name", "coach_first_name", "coach_last_name", "foundation_year", "division_id"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         format="int64",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         example="Клуб 1"
 *     ),
 *     @OA\Property(
 *         property="coach_first_name",
 *         type="string",
 *         example="Иван"
 *     ),
 *     @OA\Property(
 *         property="coach_middle_name",
 *         type="string",
 *         example="Иванович"
 *     ),
 *     @OA\Property(
 *         property="coach_last_name",
 *         type="string",
 *         example="Иванов"
 *     ),
 *     @OA\Property(
 *         property="foundation_year",
 *         type="integer",
 *         example=1990
 *     ),
 *     @OA\Property(
 *         property="coach_photo",
 *         type="string",
 *         example="https://example.com/photo.jpg"
 *     ),
 *     @OA\Property(
 *         property="division_id",
 *         type="integer",
 *         format="int64",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         example="2023-01-01T00:00:00Z"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         example="2023-01-01T00:00:00Z"
 *     ),
 *     @OA\Property(
 *         property="division",
 *         ref="#/components/schemas/Division"
 *     ),
 *     @OA\Property(
 *         property="forwards",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Forward")
 *     )
 * )
 */
class Club extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'coach_first_name','coach_middle_name','coach_last_name', 'foundation_year', 'coach_photo', 'division_id'];

    // Связь с дивизионом
    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    // Связь с нападающими
    public function forwards()
    {
        return $this->hasMany(Forward::class);
    }
}
