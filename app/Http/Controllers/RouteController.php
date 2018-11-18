<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;
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
        return view('main')->with('tags', Tag::all());
    }
    
    public function manageTags(Request $request)
    {
        $errorInfo = ['stat' => 'err', 'message' => 'no_data'];
        if(!empty($request->tags)):
            $items = Tag::with('items')->whereIn('id', $request->tags)->get();
        else:
            $items = Tag::with('items')->get();
        endif;
        $text = "";
        $message = [];
        foreach($items as $item):
            foreach($item['items'] as $val):
                $collection[$val['id']] = $val['name'];
            endforeach;
        endforeach;
        foreach($collection as $key=>$val):
            $col = Item::find($key);
            $tagsId =[];
            foreach($col->tags as $ctags):
                $tagsId[] = $ctags['id'];
            endforeach;
            if(empty($request->exclude)):
                $content[$key] = $val;
            else:
                (empty(array_intersect($request->exclude, $tagsId)))&&($content[$key] = $val);
            endif;
        endforeach;
        if(empty($content)) return response()->json($errorInfo);
        foreach($content as $key=>$val):
            $text .= $key.'; "'.$val.'"; ';
            $update[] = $key;
            $message[] = $val;
        endforeach;
        foreach($update as $id):
            $item = Item::find($id);
            $item->update(['show_count'=>$item->show_count + 1]);
        endforeach;
        $path = 'output_'.rand(1000, 9999).'.csv';
        Storage::disk('public')->put($path, $text);
        return response()->json(['stat' => 'ok', 'message' => $message, 'link' => $path]);
    }

    public function download($name)
    {
        $filePath = storage_path('app/public/'.$name);
        $headers = [
            'Content-Length: '. filesize($filePath),
            "Content-type: text/csv; charset=utf-8"
        ];
        if (file_exists($filePath)):
            $response = Response::make(file_get_contents($filePath), 200, $headers);
            File::delete($filePath);
            return $response;
        else:
            return response()->json(['stat' => 'err', 'message' => 'not_exist']);
        endif;
    }
}
