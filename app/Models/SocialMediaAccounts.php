<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class SocialMediaAccounts extends Model
{
    protected $fillable = ['items_id', 'source_user_id', 'username', 'site', 'is_active', 'is_primary', 'use_avatar','avatar'];


    /*
     * Get social media accounts already associated with item
     */
    public function getAssocAccountsArr($itemsColl, $itemsObj)
    {
        //\DB::enableQueryLog();

        $socialMediaAssocAccountsArr = [];
        $itemsIdArr = $itemsObj->getIdArrFromColl($itemsColl);
        if (count($itemsIdArr)) {

            $socialMediaAssocAccountsColl = \DB::table('social_media_accounts')
                ->whereIn('items_id', $itemsIdArr)->get();
            $socialMediaAssocAccountsArr = $socialMediaAssocAccountsColl->toArray();
        }
        //$r = dd( \DB::getQueryLog() );

        return $socialMediaAssocAccountsArr;

    }

    public static function updateRow(Request $request)
    {
        if ($request->add_source_user_id) {
            $q = "UPDATE social_media_accounts 
                  SET items_id = ? 
                  WHERE source_user_id = ?";
            \DB::update($q, [$request->items_id, $request->add_source_user_id]);
        } else if ($request->remove) {
            $q = "UPDATE social_media_accounts 
                  SET items_id = 0, is_active = 0, is_primary = 0 
                  WHERE 1 = 1 
                  AND source_user_id = ? 
                  AND site = ?";
            \DB::update($q, [$request->add_source_user_id, $request->site]);
        } else {
            $isActive = !empty($request->is_active) ? $request->is_active : 0;
            $isPrimary = !empty($request->is_primary) ? $request->is_primary : 0;
            $useAvatar = !empty($request->use_avatar) ? $request->use_avatar : 0;
            $q = "UPDATE social_media_accounts 
                  SET is_active = ?, is_primary = ?, use_avatar = ?, avatar = ?   
                  WHERE 1 = 1 
                  AND source_user_id = ? 
                  AND site = ? 
                  AND items_id = ?";
            \DB::update($q, [$isActive, $isPrimary, $useAvatar, $request->avatar,  $request->source_user_id, $request->site, $request->items_id]);
            //$r = dd( \DB::getQueryLog() );

        }
    }

}