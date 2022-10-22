<?php


namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Elibyy\TCPDF\Facades\TCPDF;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\View;


trait PdfController
{

    public function getPdfContract($pdffile)
    {

        define("PATH", realpath("."));
        $filename = PATH . '/Documents/' . $pdffile;

        header("Content-type: application/pdf");
        header("Content-Length: " . filesize($filename));
        readfile($filename);

    }

    public function savePdfContracttoLocal($id, $mpdf_returned)
    {

        define("PATH", realpath("."));
        $customer_id = $id;

        $imgpath_exists = PATH . '/Documents/';

        if (!file_exists($imgpath_exists)) {
            mkdir($imgpath_exists);
        }
        $pdf_path_local = $imgpath_exists . "/" . $id . ".pdf";
        $mpdf_returned->Output($pdf_path_local, "F");

        return $pdf_path_local;

    }

    public function createPdfTemplate($arr)
    {
        $data = $arr;

        $mpdf = new Mpdf();

        $stylesheet = file_get_contents(public_path("assets/css/pdfprint.css"));
        $mpdf->WriteHTML($stylesheet, 1);

        $html = View::make("backend.contract-pdf-template")->with("data", $data);
        $html->render();
        $mpdf->WriteHTML($html);
                
        return $this->savePdfContracttoLocal($data[4], $mpdf);

    }

    public function jsignaturetoPdf($signature)
    {

        require_once app_path("Http\Middleware\jSignature_Tools_Base30.php");

        array_map('unlink', glob(base_path("/downloads/tmp/*.png")));

        foreach ($signature as $keys => $signs) {

            $data = $signs["base30"];
            $data = str_replace('image/jsignature;base30,', '', $data);

            $converter = new jSignature_Tools_Base30();
            $raw = $converter->Base64ToNative($data);
            $im = imagecreatetruecolor(600, 200);
            imagesavealpha($im, true);
            $trans_colour = imagecolorallocatealpha($im, 255, 255, 255, 127);
            imagefill($im, 0, 0, $trans_colour);
            imagesetthickness($im, 5);
            $black = imagecolorallocate($im, 0, 0, 0);

            for ($i = 0; $i < count($raw); $i++) {
                for ($j = 0; $j < count($raw[$i]['x']); $j++) {
                    if (!isset($raw[$i]['x'][$j]) or !isset($raw[$i]['x'][$j + 1])) break;
                    imageline($im, $raw[$i]['x'][$j], $raw[$i]['y'][$j], $raw[$i]['x'][$j + 1], $raw[$i]['y'][$j + 1], $black);
                }
            }

            $filename = base_path('/downloads/tmp/'.$keys.'.png');

            ob_start();
            imagepng($im, $filename);
            ob_end_clean();

            imagedestroy($im);

        }
    }

}
