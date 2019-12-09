<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 09/12/2019, 1:42 pm.
 * @license Apache-2.0
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Rating.
 *
 * @property int $id
 * @property string $imdbID
 * @property double $rating
 * @property int $votes
 */
class Rating extends Model
{
    public const TABLE_NAME = 'ratings';
    protected $table = self::TABLE_NAME;

    protected $guarded = [];
}
