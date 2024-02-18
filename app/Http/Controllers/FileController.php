<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $files = File::where('is_deleted', false)->get();
        return view('files.index', compact('files'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();

        // Генерация уникального идентификатора для имени файла
        $uniqueId = Str::random(4);
        $fileName = pathinfo($originalName, PATHINFO_FILENAME) . '-' . $uniqueId . '.' . $file->getClientOriginalExtension();

        // Сохранение файла в директорию 'public/files'
        $path = $file->storeAs('public/files', $fileName);

        // Сохранение информации о файле в базе данных
        File::create([
            'name' => $originalName, // Сохраняем оригинальное имя для отображения
            'path' => $path,
        ]);

        return redirect()->back()->with('success', 'Файл успешно загружен.');
    }

    public function delete($id)
    {
        $file = File::find($id);
        if ($file) {
            $file->update(['is_deleted' => true]);
            // Storage::delete($file->path); // Раскомментируйте, если хотите физически удалять файлы
        }
        return redirect()->back()->with('success', 'Файл помечен как удаленный.');
    }

    public function download($id)
    {
        $file = File::findOrFail($id);
        return Storage::download($file->path, $file->name);
    }
}
