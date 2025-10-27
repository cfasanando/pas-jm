<?php

namespace App\Http\Controllers;

use App\Models\Acta;
use App\Models\Evidencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EvidenciaController extends Controller
{
    public function index(Acta $acta)
    {
        return view('evidencias.index', [
            'acta'       => $acta->load('tipificaciones.infraccion', 'administrado'),
            'evidencias' => $acta->evidencias()->latest()->get(),
        ]);
    }

    public function store(Request $r, Acta $acta)
    {
        if ($acta->estado === 'anulada') {
            return back()->with('warn', 'No se puede adjuntar a un acta anulada.');
        }

        // Puede venir como "files[]" (múltiple) o "archivo" (simple)
        $files = $r->file('files', []);
        if (empty($files)) {
            $r->validate(['archivo' => 'required|file|max:20480']);
            $files = [$r->file('archivo')];
        } else {
            $r->validate(['files.*' => 'file|max:20480']);
        }

        $dir = "actas/{$acta->id}";

        foreach ($files as $file) {
            if (!$file) continue;

            $mime     = $file->getMimeType() ?: 'application/octet-stream';
            $ext      = strtolower($file->getClientOriginalExtension() ?: 'bin');
            $origName = $file->getClientOriginalName();
            $size     = (int) $file->getSize();

            $name = Str::uuid()->toString().'.'.$ext;
            $path = $file->storeAs($dir, $name, 'public');

            // Thumb si es imagen
            $thumbPath = null;
            if (str_starts_with($mime, 'image/')) {
                $thumbDir  = "{$dir}/thumbs";
                Storage::disk('public')->makeDirectory($thumbDir);
                $thumbName = Str::beforeLast($name, '.').'_th.jpg';
                $thumbPath = "{$thumbDir}/{$thumbName}";
                try {
                    if (class_exists(\Intervention\Image\ImageManager::class)) {
                        $manager = new \Intervention\Image\ImageManager(
                            \Intervention\Image\Drivers\Gd\Driver::class
                        );
                        $img = $manager->read(Storage::disk('public')->path($path));
                        $img->scale(width: 900);
                        $img->toJpeg(85)->save(Storage::disk('public')->path($thumbPath));
                    } else {
                        $this->makeThumbWithGd(
                            Storage::disk('public')->path($path),
                            Storage::disk('public')->path($thumbPath),
                            900, 900
                        );
                    }
                } catch (\Throwable $e) {
                    $thumbPath = null;
                }
            }

            Evidencia::create([
                'acta_id'       => $acta->id,
                'path'          => $path,
                'thumb_path'    => $thumbPath,
                'mime'          => $mime,
                'size'          => $size,
                'original_name' => $origName,
            ]);
        }

        return back()->with('ok', 'Evidencia(s) subida(s)');
    }

    public function destroy(Evidencia $ev)
    {
        // borra archivos físicos
        if ($ev->path && Storage::disk('public')->exists($ev->path)) {
            Storage::disk('public')->delete($ev->path);
        }
        if ($ev->thumb_path && Storage::disk('public')->exists($ev->thumb_path)) {
            Storage::disk('public')->delete($ev->thumb_path);
        }
        $ev->delete();

        return back()->with('ok', 'Evidencia eliminada');
    }

    // --- Fallback con GD si no tienes Intervention/Image 3.x ---
    protected function makeThumbWithGd(string $src, string $dest, int $maxW, int $maxH): void
    {
        $info = @getimagesize($src);
        if (!$info) return;

        [$w, $h, $type] = $info;
        switch ($type) {
            case IMAGETYPE_JPEG: $im = imagecreatefromjpeg($src); break;
            case IMAGETYPE_PNG:  $im = imagecreatefrompng($src);  break;
            case IMAGETYPE_GIF:  $im = imagecreatefromgif($src);  break;
            default: return;
        }

        $scale = min($maxW / $w, $maxH / $h, 1);
        $nw = (int) round($w * $scale);
        $nh = (int) round($h * $scale);

        $canvas = imagecreatetruecolor($nw, $nh);
        imagecopyresampled($canvas, $im, 0, 0, 0, 0, $nw, $nh, $w, $h);
        imagejpeg($canvas, $dest, 85);
        imagedestroy($im);
        imagedestroy($canvas);
    }
}
