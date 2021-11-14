<?php

namespace App\Models\Coins;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $mintMark
 * @property int $coinYear
 * @property string $coinCategory
 * @property string $coinType
 * @property int $cointypes_id
 * @property int $coincats_id
 * @property string $coinSubCategory
 * @property string $coinName
 * @property string $coinVersion
 * @property string $coinSize
 * @property string $coinMetal
 * @property string $strike
 * @property int $commemorative
 * @property string $commemorativeVersion
 * @property string $commemorativeCategory
 * @property string $commemorativeType
 * @property string $commemorativeGroup
 * @property int $byMint
 * @property int $byMintGold
 * @property float $denomination
 * @property int $keyDate
 * @property int $semiKeyDate
 * @property int $errorCoin
 * @property int $varietyCoin
 * @property string $varietyType
 * @property int $century
 * @property int $release
 * @property string $state
 * @property string $rarity
 * @property string $genre
 * @property string $subGenre
 * @property string $subGenre2
 * @property string $series
 * @property string $seriesType
 * @property string $design
 * @property string $designType
 * @property string $mms
 * @property string $mms2
 * @property string $mms3
 * @property string $mms4
 * @property string $dateSize
 * @property string $obv
 * @property string $obv2
 * @property string $obv3
 * @property string $rev
 * @property string $rev2
 * @property string $rev3
 */
class Coin extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['mintMark', 'coinYear', 'coinCategory', 'coinType', 'cointypes_id', 'coincats_id', 'coinSubCategory', 'coinName', 'coinVersion', 'coinSize', 'coinMetal', 'strike', 'commemorative', 'commemorativeVersion', 'commemorativeCategory', 'commemorativeType', 'commemorativeGroup', 'byMint', 'byMintGold', 'denomination', 'keyDate', 'semiKeyDate', 'errorCoin', 'varietyCoin', 'varietyType', 'century', 'release', 'state', 'rarity', 'genre', 'subGenre', 'subGenre2', 'series', 'seriesType', 'design', 'designType', 'mms', 'mms2', 'mms3', 'mms4', 'dateSize', 'obv', 'obv2', 'obv3', 'rev', 'rev2', 'rev3'];

}
