<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class TweetResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function toArray($request)
    {
        $user = User::where('id', $this->user_id)->first();

        return [
            'id' => $this->id,
            'twitter_text' => $this->twitter_text,
            'is_published' => $this->is_published,
            'likes' => $this->likes,
            'created_at' => $this->created_at,
            'user' => UserResource::make($user),
        ];
    }
}
