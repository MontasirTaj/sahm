<?php

namespace App\Helpers;

class ArabicPdfHelper
{
    /**
     * تحويل النص العربي لعكس اتجاه الكتابة للـ PDF
     */
    public static function reverseArabic($text)
    {
        // تحويل النص إلى UTF-16
        $utf16 = mb_convert_encoding($text, 'UTF-16BE', 'UTF-8');
        
        // عكس النص
        $reversed = strrev($utf16);
        
        // إعادة التحويل إلى UTF-8
        return mb_convert_encoding($reversed, 'UTF-8', 'UTF-16LE');
    }
    
    /**
     * إصلاح النص العربي للعرض الصحيح في PDF
     */
    public static function fixArabicForPdf($text)
    {
        // إزالة HTML tags
        $text = strip_tags($text);
        
        // تنظيف المسافات
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);
        
        return $text;
    }
}
