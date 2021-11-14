<?php

namespace App\Models\Coins;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $coin_id
 * @property string $sub_type
 * @property string $grouping
 * @property string $variety
 * @property string $label
 * @property string $designation
 * @property string $type
 * @property string $description
 * @property string $obv
 * @property string $rev
 * @property string $sequence
 * @property string $dmr
 * @property int $err_id
 * @property string $details
 * @property string $reference
 */
class CoinVariety extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'coins_variety';

    /**
     * @var array
     */
    protected $fillable = ['coin_id', 'sub_type', 'grouping', 'variety', 'label', 'designation', 'type', 'description', 'obv', 'rev', 'sequence', 'dmr', 'err_id', 'details', 'reference'];

}
