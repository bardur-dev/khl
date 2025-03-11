<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @OA\Schema(
 *     schema="Forward",
 *     type="object",
 *     title="Forward",
 *     required={"middle_name", "club_id"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         format="int64",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="middle_name",
 *         type="string",
 *         example="Иванов"
 *     ),
 *     @OA\Property(
 *         property="club_id",
 *         type="integer",
 *         format="int64",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="goals_scored",
 *         type="integer",
 *         example=10
 *     ),
 *     @OA\Property(
 *         property="assists",
 *         type="integer",
 *         example=5
 *     ),
 *     @OA\Property(
 *         property="penalty_minutes",
 *         type="integer",
 *         example=2
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
 * )
 */
class Forward extends Model
{
    use HasFactory;
    protected $fillable = ['middle_name', 'club_id', 'goals_scored', 'assists', 'penalty_minutes'];

    // Связь с клубом
    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    // Связь с партнерами по звену
    public function partners()
    {
        return $this->belongsToMany(Forward::class, 'line_partners', 'forward_id', 'partner_id');
    }
}
