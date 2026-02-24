<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mpdf\Mpdf;

class GuideController extends Controller
{
    /**
     * عرض صفحة الدليل الشامل لكيفية عمل المنصة
     */
    public function index()
    {
        return view('guide.index');
    }
    
    /**
     * تحميل دليل المستخدم PDF باستخدام mPDF
     */
    public function downloadPdf()
    {
        // إنشاء instance من mPDF مع دعم RTL والعربية
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P',
            'margin_left' => 20,
            'margin_right' => 20,
            'margin_top' => 20,
            'margin_bottom' => 20,
            'margin_header' => 10,
            'margin_footer' => 10,
            'default_font' => 'dejavusans',
            'directionality' => 'rtl',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
        ]);
        
        // تحميل HTML من view
        $html = view('guide.pdf-simple')->render();
        
        // كتابة HTML في PDF
        $mpdf->WriteHTML($html);
        
        // اسم الملف
        $filename = 'sahmi-guide-' . date('Y-m-d') . '.pdf';
        
        // تحميل الملف
        return response()->streamDownload(function() use ($mpdf) {
            echo $mpdf->Output('', 'S');
        }, $filename, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
