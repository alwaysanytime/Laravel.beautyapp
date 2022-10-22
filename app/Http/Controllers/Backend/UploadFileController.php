<?php


namespace App\Http\Controllers\Backend;


use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use GuzzleHttp\Psr7\UploadedFile;
use Stripe\File;

class UploadFileController extends Controller
{
    public function FileUploadLoad()
    {

        $data = DB::table('customers')
            ->select('customers.*')
            ->orderBy('customers.lastname')
            ->get();

        return view("backend.fileupload", compact("data"));
    }

    public function FileUpload(Request $request)
    {
        define("PATH", realpath("."));

        if ($_FILES["file"]["error"][0] == 4) { // Simdilik tek kontrol, dosya varmi yokmu(4), sonra degismeli!!! 1-2-3-5-6-7-8 kontrol var upload icin!!!!
            $msg["error"] = "Resim secilmedi!";
        } elseif (!isset($_GET["id"])) {
            $msg["error"] = "Musteri secilmedi!";
        } else {

            if (/*isset($_POST['submit']) &&*/ is_numeric($_GET["id"])) {

                $total_count = count($_FILES["file"]["tmp_name"]);

                for ($i = 0; $i < $total_count; $i++) {

                    if (is_uploaded_file($_FILES["file"]["tmp_name"][$i])) {

                        $valid_types = ["image/jpeg"];
                        $file_type = $_FILES["file"]["type"][$i];
                        $valid_size = (1024 * 1024 * 10);
//                        $thumb = file_get_contents($_FILES['file']['tmp_name'][$i]);


//                        $path = "C:\laragon\www\medical-hairless-hamm\Clientimages\IMG_5596.JPG";
//                        $thumbnail = exif_thumbnail($path, $width, $height, $type);
//
////                        header('Content-type: ' .image_type_to_mime_type($type));
////                        echo $thumbnail;
//                        echo '<img src="data:image/jpeg;base64,' . base64_encode($thumbnail) . '"/>';
//                        print $thumbnail;
//                        exit();


                        if (in_array($file_type, $valid_types)) {

                            if ($_FILES["file"]["size"][$i] <= $valid_size) {

                                $customer_id = (int)$_GET["id"];;
                                $filename = $_FILES['file']['name'][$i];
                                $img_path = PATH . '/Clientimages/' . $customer_id . "/" . $filename;
                                $imgpath_exists = PATH . '/Clientimages/' . $customer_id;
                                $fullfilename = $_GET["id"] . "\\";
                                $temp_path = $_FILES['file']['tmp_name'][$i];


                                if (!file_exists($imgpath_exists)) {
                                    mkdir($imgpath_exists);
                                }

                                if (file_exists($img_path)) {
                                    $explodedFile_type = explode(".", $filename);
                                    $img_new_name = $explodedFile_type[0] . "_" . date('YmdHis') . "." . $explodedFile_type[1];
                                    $fullfilename .= $img_new_name;
                                    $img_path = PATH . '/Clientimages/' . $customer_id . "/" . $img_new_name;
	                        					$is_inserted = $this->uploadImage($customer_id, $img_new_name, $fullfilename, $temp_path);
                                    $is_uploaded = move_uploaded_file($_FILES['file']['tmp_name'][$i], $img_path);
                                } else {
                                    $fullfilename .= $filename;
																		$is_inserted = $this->uploadImage($customer_id, $filename, $fullfilename, $temp_path);
                                    $is_uploaded = move_uploaded_file($_FILES['file']['tmp_name'][$i], $img_path);
                                }

                                if ($is_uploaded /*&& $is_inserted*/) {
                                    $msg["success"] = "Erfolgreich hochgeladen";
                                    $num_id = (int)$_GET["id"];
                                    $client_uploaded_info = $this->getImage($customer_id);
                                } else {
                                    $msg["error"] = "Fehler beim Hochladen";
                                }
                            } else {

                                $msg["error"] = "Datei größer als 10 MB";
                            }
                        } else {
                            $msg["error"] = "Ungültige Datei";
                        }
                    }
                }
            }
        }

        if (isset($client_uploaded_info)) {
            session(["client_infos" => $client_uploaded_info]);
        }

        session(["msg" => $msg]);
        return back()->withInput();
    }

		public function imagedata($gdimage)
		{
		    ob_start();
		    imagejpeg($gdimage,NULL,70);
		    return(ob_get_clean());
		}

    public function uploadImage($customer_id, $filename, $fullfilename, $imgpath)
    {
				// Neue Größe
				list($width, $height) = getimagesize($imgpath);
				$newwidth = 160;
				$newheight = 120;
				
				// Bild laden
				$source = imagecreatefromjpeg($imgpath);
				$thumb = imagecreatetruecolor($newwidth, $newheight);
				
				// Skalieren
				imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
				
				// Binäres Image machen!
				$thumb = $this->imagedata($thumb);
						
        //$thumb = exif_thumbnail($imgpath, $width, $height, $type);
        try {
            $inserted = DB::table('pictures')
                ->insert(["customerid" => $customer_id, "filename" => $filename, "fullfilename" => $fullfilename, "uploaddttm" => date('Y.m.d H:i:s'), "thumb" => $thumb]);
              	DB::table('refresh_flags')->update(["pictures" => 1]);
        } catch(QueryException $e) {
            echo $e->getMessage();
        }
        return $inserted;

    }

    public function getImage($id)
    {

        $query = DB::table("pictures")
            ->select("id", "customerid", "filename", "thumb", "uploaddttm")
            ->where("customerid", "=", $id)
            ->whereDate("uploaddttm", "=", Carbon::today())
            ->get();

        $client_img_thumbs = [];
        $i = 0;
        foreach ($query as $que) {
            if ($i < $query->count()) {
                $array = (array)$que;
                $client_img_thumbs[$i] = $array;
            }
            $i++;
        }
        return $client_img_thumbs;
    }

    public function deleteImage(Request $request)
    {
        // PROBLEME BURADAN DEVAM

        $is_deleted = DB::table('pictures')->where('id', '=', $request->id)->delete();

        $is_deleted = 1;
        if ($is_deleted) {
						DB::table('refresh_flags')->update(["pictures" => 1]);
            $msg["success"] = "Fotos erfolgreich gelöscht";
            $client_uploaded_info = $this->getImage((int)$request->customer_id);
            if (isset($client_uploaded_info)) {
                session(["client_infos" => $client_uploaded_info]);
            }
        } else {
            $msg["error"] = "Fehler beim Löschen";
        }

        return session("client_infos");
        exit();


        session(["msg" => $msg]);
        return back()->withInput();

    }
}
