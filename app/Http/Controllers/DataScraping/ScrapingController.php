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
                    $comic = Comics::create([
                        "api_id" => $responseJson["data"]["items"][$i]["_id"],
                    ]);
                    NovelCategory::create([
                        "category_id" => $category->id,
                        "novel_id" => $comic->id,
                        "novel_type" => "comic"
                    ]);
                }
            }
        }
        return "Success scraping comics";
    }
}
