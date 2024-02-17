<?php
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
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
        $path = $file->store('public/files');

        File::create([
            'name' => $file->getClientOriginalName(),
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
