<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class Comment extends Model
{
    use HasFactory;

    const TABLE      = 'comment';
    const ID         = 'id';
    const USER_ID       = 'user_id';
    const BLOG_ID       = 'blog_id';
    const COMMENT       = 'comment';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $table      = self::TABLE;
    protected $guarded    = [];
    public    $timestamps = true;

    public static function addUpdate($postData, $id = 0)
    {
        if ($id) {
            $postData['updated_at'] = date("Y-m-d H:i:s");
            $res                    = DB::table(self::TABLE)
                ->where('id', $id)
                ->update($postData);
            $result                 = $res ? $id : 0;
        } else {
            $postData['created_at'] = date("Y-m-d H:i:s");
            DB::table(self::TABLE)
                ->insert($postData);
            $result = DB::getPdo()
                ->lastInsertId();
        }

        return $result;
    }
}
