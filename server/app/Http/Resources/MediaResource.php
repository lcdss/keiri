<?php

namespace App\Http\Resources;

class MediaResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'fileName' => $this->file_name,
            'url' => $this->getUrl(),
            'mimeType' => $this->mime_type,
            'size' => $this->size,
            'tags' => $this->tags,
        ];
    }
}
