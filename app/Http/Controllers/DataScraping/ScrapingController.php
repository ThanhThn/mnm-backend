<?php

namespace App\Http\Controllers\DataScraping;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Comics;
use App\Models\NovelCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ScrapingController extends Controller
{
    function scrapingCategories()
    {
        $response = Http::get(env('API_URL_COMIC') . "/the-loai");
        $responseJson = $response->json();
        if($responseJson['status'] == 'success'){
            $items = $responseJson['data']["items"];
            foreach($items as $item){
                $category = Category::where("slug", $item["slug"])->first();
                if($category && $category->name != $item["name"]){
                    Category::where("slug", $item["slug"])->update(["name" => $item["name"]]);
                }

                if(!$category){
                    Category::create([
                        "name" => $item["name"],
                        "slug" => $item["slug"]
                    ]);

                }
            }
            return "Success scraping categories";
        }
        return "Error scrapping categories";
    }

    function scrapingComics(){
        $categories = Category::all();
        $quantity = 2;
        foreach($categories as $category){
            $response = Http::get(env('API_URL_COMIC') . "/the-loai/".$category->slug."?page=1");
            $responseJson = $response->json();
            if($responseJson['status'] == 'success'){
                $quantityComics = $quantity <= count($responseJson["data"]["items"]) ? $quantity : count($responseJson["data"]["items"]);
                for($i = 0; $i < $quantityComics; $i++){
                    $exist = Comics::where('api_id', $responseJson["data"]["items"][$i]["_id"])->exists();
                    $comic = $exist ?
                        Comics::where('api_id', $responseJson["data"]["items"][$i]["_id"])->first() :
                        Comics::create([
                        "api_id" => $responseJson["data"]["items"][$i]["_id"],
                        "name" => $responseJson["data"]["items"][$i]["name"],
                        "slug" => $responseJson["data"]["items"][$i]["slug"],
                        "thumbnail" => env("URL_IMAGE_COMIC") . "/" .$responseJson["data"]["items"][$i]["thumb_url"],
                    ]);

                    if($exist){
                        $comic->update([
                            "name" => $responseJson["data"]["items"][$i]["name"],
                            "slug" => $responseJson["data"]["items"][$i]["slug"],
                            "thumbnail" => env("URL_IMAGE_COMIC") . "/" .$responseJson["data"]["items"][$i]["thumb_url"]
                        ]);
                    }
                    $existConnect = NovelCategory::where("category_id", $category->id)
                        ->where("novel_id", $comic->id)
                        ->exists();
                    if(!$existConnect){
                        NovelCategory::create([
                            "category_id" => $category->id,
                            "novel_id" => $comic->id,
                            "novel_type" => "comic"
                        ]);
                    }
                }
            }
        }
        return "Success scraping comics";
    }
}
