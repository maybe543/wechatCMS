<?php
if (!defined('IN_IA')) {
    die('Access Denied');
}
function img2thumb($src_img, $dst_img, $width = 75, $height = 75, $cut = 0, $proportion = 0)
{
    if (!is_file($src_img)) {
        return false;
    }
    $ot        = fileext($dst_img);
    $otfunc    = 'image' . ($ot == 'jpg' ? 'jpeg' : $ot);
    $srcinfo   = getimagesize($src_img);
    $src_w     = $srcinfo[0];
    $src_h     = $srcinfo[1];
    $type      = strtolower(substr(image_type_to_extension($srcinfo[2]), 1));
    $createfun = 'imagecreatefrom' . ($type == 'jpg' ? 'jpeg' : $type);
    $dst_h     = $height;
    $dst_w     = $width;
    $x         = $y = 0;
    if (($width > $src_w && $height > $src_h) || ($height > $src_h && $width == 0) || ($width > $src_w && $height == 0)) {
        $proportion = 1;
    }
    if ($width > $src_w) {
        $dst_w = $width = $src_w;
    }
    if ($height > $src_h) {
        $dst_h = $height = $src_h;
    }
    if (!$width && !$height && !$proportion) {
        return false;
    }
    if (!$proportion) {
        if ($cut == 0) {
            if ($dst_w && $dst_h) {
                if ($dst_w / $src_w > $dst_h / $src_h) {
                    $dst_w = $src_w * ($dst_h / $src_h);
                    $x     = 0 - ($dst_w - $width) / 2;
                } else {
                    $dst_h = $src_h * ($dst_w / $src_w);
                    $y     = 0 - ($dst_h - $height) / 2;
                }
            } else if ($dst_w xor $dst_h) {
                if ($dst_w && !$dst_h) {
                    $propor = $dst_w / $src_w;
                    $height = $dst_h = $src_h * $propor;
                } else if (!$dst_w && $dst_h) {
                    $propor = $dst_h / $src_h;
                    $width  = $dst_w = $src_w * $propor;
                }
            }
        } else {
            if (!$dst_h) {
                $height = $dst_h = $dst_w;
            }
            if (!$dst_w) {
                $width = $dst_w = $dst_h;
            }
            $propor = min(max($dst_w / $src_w, $dst_h / $src_h), 1);
            $dst_w  = (int) round($src_w * $propor);
            $dst_h  = (int) round($src_h * $propor);
            $x      = ($width - $dst_w) / 2;
            $y      = ($height - $dst_h) / 2;
        }
    } else {
        $proportion = min($proportion, 1);
        $height     = $dst_h = $src_h * $proportion;
        $width      = $dst_w = $src_w * $proportion;
    }
    $src   = $createfun($src_img);
    $dst   = imagecreatetruecolor($width ? $width : $dst_w, $height ? $height : $dst_h);
    $white = imagecolorallocate($dst, 255, 255, 255);
    imagefill($dst, 0, 0, $white);
    if (function_exists('imagecopyresampled')) {
        imagecopyresampled($dst, $src, $x, $y, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
    } else {
        imagecopyresized($dst, $src, $x, $y, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
    }
    $otfunc($dst, $dst_img);
    imagedestroy($dst);
    imagedestroy($src);
    return true;
}
function fileext($file)
{
    return pathinfo($file, PATHINFO_EXTENSION);
}
function imageWaterMark($groundImage, $waterPos = 0, $waterImage = '', $waterText = '', $textFont = 5, $textColor = '#FF0000')
{
    $isWaterImage = FALSE;
    $formatMsg    = '暂不支持该文件格式，请用图片处理软件将图片转换为GIF、JPG、PNG格式。';
    if (!empty($waterImage) && file_exists($waterImage)) {
        $isWaterImage = TRUE;
        $water_info   = getimagesize($waterImage);
        $water_w      = $water_info[0];
        $water_h      = $water_info[1];
        switch ($water_info[2]) {
            case 1:
                $water_im = imagecreatefromgif($waterImage);
                break;
            case 2:
                $water_im = imagecreatefromjpeg($waterImage);
                break;
            case 3:
                $water_im = imagecreatefrompng($waterImage);
                break;
            default:
                die($formatMsg);
        }
    }
    if (!empty($groundImage) && file_exists($groundImage)) {
        $ground_info = getimagesize($groundImage);
        $ground_w    = $ground_info[0];
        $ground_h    = $ground_info[1];
        switch ($ground_info[2]) {
            case 1:
                $ground_im = imagecreatefromgif($groundImage);
                break;
            case 2:
                $ground_im = imagecreatefromjpeg($groundImage);
                break;
            case 3:
                $ground_im = imagecreatefrompng($groundImage);
                break;
            default:
                die($formatMsg);
        }
    } else {
        die('需要加水印的图片不存在！');
    }
    if ($isWaterImage) {
        $w     = $water_w;
        $h     = $water_h;
        $label = '图片的';
    } else {
        $temp = imagettfbbox(ceil($textFont * 5), 0, './cour.ttf', $waterText);
        $w    = $temp[2] - $temp[6];
        $h    = $temp[3] - $temp[7];
        unset($temp);
        $label = '文字区域';
    }
    if (($ground_w < $w) || ($ground_h < $h)) {
        echo '需要加水印的图片的长度或宽度比水印' . $label . '还小，无法生成水印！';
        return;
    }
    switch ($waterPos) {
        case 0:
            $posX = rand(0, ($ground_w - $w));
            $posY = rand(0, ($ground_h - $h));
            break;
        case 1:
            $posX = 0;
            $posY = 0;
            break;
        case 2:
            $posX = ($ground_w - $w) / 2;
            $posY = 0;
            break;
        case 3:
            $posX = $ground_w - $w;
            $posY = 0;
            break;
        case 4:
            $posX = 0;
            $posY = ($ground_h - $h) / 2;
            break;
        case 5:
            $posX = ($ground_w - $w) / 2;
            $posY = ($ground_h - $h) / 2;
            break;
        case 6:
            $posX = $ground_w - $w;
            $posY = ($ground_h - $h) / 2;
            break;
        case 7:
            $posX = 0;
            $posY = $ground_h - $h;
            break;
        case 8:
            $posX = ($ground_w - $w) / 2;
            $posY = $ground_h - $h;
            break;
        case 9:
            $posX = $ground_w - $w;
            $posY = $ground_h - $h;
            break;
        default:
            $posX = rand(0, ($ground_w - $w));
            $posY = rand(0, ($ground_h - $h));
            break;
    }
    imagealphablending($ground_im, true);
    if ($isWaterImage) {
        imagecopy($ground_im, $water_im, $posX, $posY, 0, 0, $water_w, $water_h);
    } else {
        if (!empty($textColor) && (strlen($textColor) == 7)) {
            $R = hexdec(substr($textColor, 1, 2));
            $G = hexdec(substr($textColor, 3, 2));
            $B = hexdec(substr($textColor, 5));
        } else {
            die('水印文字颜色格式不正确！');
        }
        imagestring($ground_im, $textFont, $posX, $posY, $waterText, imagecolorallocate($ground_im, $R, $G, $B));
    }
    @unlink($groundImage);
    switch ($ground_info[2]) {
        case 1:
            imagegif($ground_im, $groundImage);
            break;
        case 2:
            imagejpeg($ground_im, $groundImage);
            break;
        case 3:
            imagepng($ground_im, $groundImage);
            break;
        default:
            die($errorMsg);
    }
    if (isset($water_info))
        unset($water_info);
    if (isset($water_im))
        imagedestroy($water_im);
    unset($ground_info);
    imagedestroy($ground_im);
}
?>