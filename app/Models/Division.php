<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @OA\Schema(
 *     schema="Division",
 *     type="object",
 *     title="Division",
 *     required={"id", "name"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         format="int64",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         example="Premier League"
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
 *     )
 * )
 */
class Division extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // Связь с клубами
    public function clubs()
    {
        return $this->hasMany(Club::class);
    }
}
