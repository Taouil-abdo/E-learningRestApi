<?php


test(" can list tags",function(){

    $responce = $this->get("api/tags");
    $responce->assertStatus(200);
    $responce->assertJsonStructure([
        "data" => [
            "*" => [
                'name',
            ],
        ],
    ]);
});

test("can add Tag", function(){
     $tag = [
        "name" => "yara"
     ];

     $responce = $this->post("/api/tags",$tag);
     $responce->assertStatus(201);
     $tag = $responce->json('data');

     $this->assertDatabaseHas('tags',['name' => $tag['name']]);

});