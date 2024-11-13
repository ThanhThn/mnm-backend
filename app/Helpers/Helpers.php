<?php

namespace App\Helpers;

class Helpers
{
    public static function createSlug($text)
    {
        $slug = strtolower($text);
        $slug = preg_replace(
            ['/á|à|ả|ã|ạ|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/', '/đ/', '/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/', '/í|ì|ỉ|ĩ|ị/', '/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/', '/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/', '/ý|ỳ|ỷ|ỹ|ỵ/'],
            ['a', 'd', 'e', 'i', 'o', 'u', 'y'],
            $slug
        );
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
        $slug = preg_replace('/[\s-]+/', '-', $slug);
        $slug = trim($slug, '-');
        return $slug;
    }
}
