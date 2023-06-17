<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Blog extends Model
{
    use HasFactory;

    const TABLE      = 'blog';
    const ID         = 'id';
    const TITLE       = 'title';
    const DESCRIPTION       = 'description';
    const STATUS     = 'status';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $table      = self::TABLE;
    protected $guarded    = [];
    public    $timestamps = true;

    public function addUpdate($postData, $id = 0)
    {
        if ($id) {
            $postData['updated_at'] = date("Y-m-d H:i:s");
            $res                    = DB::table('users')
                ->where('id', $id)
                ->update($postData);
            $result                 = $res ? $id : 0;
        } else {
            $postData['created_at'] = date("Y-m-d H:i:s");
            DB::table('users')
                ->insert($postData);
            $result = DB::getPdo()
                ->lastInsertId();
        }

        return $result;
    }
}
