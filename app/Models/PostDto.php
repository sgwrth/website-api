<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;

class PostDto
{
    public function __construct(
        public int $id,
        public string $title,
        public string $text,
        public string $author,
        public string $authorEmail,
        public string $created,
        public string $updated,
    ) {}

    public static function fromStdClass($data)
    {
        return new self(
            id: $data->id,
            title: $data->title,
            text: $data->text,
            author: $data->author,
            authorEmail: $data->author_email,
            created: $data->created,
            updated: $data->updated,
        );
    }
}
