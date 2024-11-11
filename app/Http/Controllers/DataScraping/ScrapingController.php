<?php

namespace App\Http\Controllers\DataScraping;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ScrapingController extends Controller
{
    function scrapingCategories()
    {
        $response = Http::get(env('API_URL_COMIC') . "/the-loai");
        $posts = $response->json();
        if($posts['status'] == 'success'){
            $items = $posts['data']["items"];
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
}
