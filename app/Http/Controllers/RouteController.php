<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\DynamicModel;
use App\Intermediary;
use App\Item;
use App\Tag;

class RouteController extends Controller
{
    private function manage_db($tableName)
    {
        $content = [];
        switch($tableName):
            case 'item':
                $inner = [
                    "Кроссовки Nike"  => 5,
                    "Джинсы Levi's"   => 10,
                    "Куртка NORMANN"  => 0,
                    "Футболка Adidas" => 1
                ];
                foreach($inner as $key => $val):
                    $content[] = ["name" => $key, "show_count" => $val];
                endforeach;
                break;
            case 'tag':
                $inner = ["одежда", "обувь", "стиль", "повседневное", "черное", "белое"];
                foreach($inner as $val):
                    $content[] = ["name" => $val];
                endforeach;
                break;
            case 'item_tag_link':
                $inner = [
                    "1"=> [2,3,5],
                    "2" => [1,4],
                    "3" => [1,4,6],
                    "4" => [1,6]
                ];
                Log::info(var_dump($inner));
                foreach($inner as $key => $val):
                    foreach($val as $id):
                        $content[] = ["item_id" => $key, "tag_id" => $id];
                    endforeach;
                endforeach;
                break;
            break;
        endswitch;        
        
        Schema::create($tableName,
            function(Blueprint $table) use ($tableName)
            {
                $table->increments('id')->unsigned();
                switch($tableName):
                    case 'item':
                        $table->string('name')->nullable();
                        $table->integer('show_count')->nullable()->unsigned();
                        break;
                    case 'tag':
                        $table->string('name')->nullable();
                        break;
                    case 'item_tag_link':
                        $table->integer('item_id')->nullable()->unsigned();
                        $table->integer('tag_id')->nullable()->unsigned();
                        $table->foreign('item_id')->references('id')->on('item');
                        $table->foreign('tag_id')->references('id')->on('tag');
                        break;
                    break;
                endswitch;
            });
        $table = new DynamicModel;
        $table->setTable($tableName);
        $table->insert($content);
    }
    
    public function index()
    {
        foreach(['item','tag', 'item_tag_link'] as $item):
            if(!Schema::hasTable($item)):
                $this->manage_db($item);
            endif;
        endforeach;
        $content = [];
        $item = Item::find(3);
        foreach($item->tags as $tag):
            $content[] = $tag->name;
        endforeach;
        return var_dump($content);
        //return view('main');
    }
}
