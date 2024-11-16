<?php

namespace App\Support\Interaction;

use App\Models\Interaction;

class InteractionSupport
{
    static function getInteraction($object_a_id, $object_b_id, $object_b_type, $interaction_type, $object_a_type = 1)
    {
        return Interaction::where([
            ['object_a_id', '=', $object_a_id],
            ['object_a_type', '=', $object_a_type],
            ['object_b_id', '=', $object_b_id],
            ['object_b_type', '=', $object_b_type],
            ['interaction_type', '=', $interaction_type],
        ])->first();
    }

    static  function countInteraction($interaction_type, $object_b_id, $object_b_type)
    {
        $count = Interaction::where([
            ['object_b_id', '=', $object_b_id],
            ['object_b_type', '=', $object_b_type],
            ['interaction_type', '=', $interaction_type]])->count();
        return $count;
    }
}
