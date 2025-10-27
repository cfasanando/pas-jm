<?php

namespace App\Http\Controllers;

use App\Models\{User, Infraccion};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index(Request $r)
    {
        return view('admin.index', [
            'users'        => User::orderBy('name')->get(),
            'infracciones' => Infraccion::orderBy('codigo')->get(),
            'series'       => DB::table('sequences')->where('key', 'like', 'boleta_%')->get(),
            'settings'     => DB::table('settings')->pluck('value','key')->toArray(),
        ]);
    }

    /* -------------------- USERS -------------------- */
    public function usersStore(Request $r)
    {
        $data = $r->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $data['name'],
            'email'=> $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        return redirect()->route('admin', ['tab' => 'users'])->with('ok','Usuario creado');
    }

    public function usersUpdate(Request $r, User $user)
    {
        $data = $r->validate([
            'name'     => 'required',
            'email'    => ['required','email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|min:6',
        ]);

        $user->name  = $data['name'];
        $user->email = $data['email'];
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->save();

        return redirect()->route('admin', ['tab' => 'users'])->with('ok','Usuario actualizado');
    }

    public function usersDestroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin', ['tab' => 'users'])->with('ok','Usuario eliminado');
    }

    /* ---------------- INFRACCIONES / SERIES ---------------- */
    public function catalogsStore(Request $r)
    {
        $r->validate(['type'=>'required|in:infraccion,serie']);

        if ($r->type === 'infraccion') {
            $data = $r->validate([
                'codigo'      => 'required|unique:infracciones,codigo',
                'descripcion' => 'required',
                'base_legal'  => 'nullable|string',
                'multa'       => 'required|numeric|min:0',
                'activo'      => 'nullable',
            ]);
            $data['activo'] = $r->boolean('activo');
            Infraccion::create($data);

            return redirect()->route('admin', ['tab' => 'infracciones'])->with('ok','Infracción creada');
        }

        // serie de boletas
        $data = $r->validate([
            'serie'   => 'required',
            'padding' => 'required|integer|min:3|max:10',
        ]);

        DB::table('sequences')->updateOrInsert(
            ['key' => 'boleta_'.strtoupper($data['serie'])],
            ['value' => 0, 'created_at'=>now(), 'updated_at'=>now()]
        );

        return redirect()->route('admin', ['tab' => 'series'])->with('ok','Serie agregada');
    }

    public function catalogsUpdate(Request $r, $id)
    {
        if ($r->type === 'infraccion') {
            $inf = Infraccion::findOrFail($id);
            $data = $r->validate([
                'codigo'      => ['required', Rule::unique('infracciones')->ignore($inf->id)],
                'descripcion' => 'required',
                'base_legal'  => 'nullable',
                'multa'       => 'nullable|numeric',
                'activo'      => 'boolean',
            ]);
            $inf->update($data);
            return back()->with(['ok' => 'Infracción actualizada', 'tab' => 'infracciones']);
        }

        if ($r->type === 'serie') {
            $r->validate([
                'current' => 'nullable|integer|min:0',
                'padding' => 'nullable|integer|min:3|max:10',
            ]);

            $updates = [];
            if ($r->filled('current')) $updates['value'] = (int) $r->current;

            if ($updates) {
                DB::table('sequences')->where('key','boleta_'.strtoupper($id))->update($updates);
                return back()->with(['ok'=>'Serie actualizada','tab'=>'series']);
            }
            return back()->with(['warn'=>'No hubo cambios','tab'=>'series']);
        }

        return back()->with('warn', 'Tipo inválido');
    }

    public function catalogsDestroy(Request $r, $id)
    {
        if ($r->type === 'infraccion') {
            Infraccion::findOrFail($id)->delete();
            return redirect()->route('admin', ['tab' => 'infracciones'])->with('ok','Infracción eliminada');
        }

        DB::table('sequences')->where('key', 'boleta_'.strtoupper($id))->delete();
        return redirect()->route('admin', ['tab' => 'series'])->with('ok','Serie eliminada');
    }

    /* -------------------- SETTINGS -------------------- */
    public function settingsUpdate(Request $r)
    {
        // Valida usando notación "dot" (porque ahora el form usa corchetes)
        $r->validate([
            'app.name'     => 'required|string|max:150',
            'app.primary'  => 'required|string|regex:/^#([A-Fa-f0-9]{6})$/',
            'app.logo'     => 'nullable|string|max:255',
            'mail.from'    => 'nullable|email',
            'pdf.footer'   => 'nullable|string|max:255',
        ], [
            'app.primary.regex' => 'El color debe ser un HEX de 6 dígitos, ej. #0d6efd.',
        ]);

        // Pares clave-valor a guardar
        $pairs = [
            'app.name'     => $r->input('app.name'),
            'app.primary'  => $r->input('app.primary'),
            'app.logo'     => $r->input('app.logo'),
            'mail.from'    => $r->input('mail.from'),
            'pdf.footer'   => $r->input('pdf.footer'),
        ];

        foreach ($pairs as $k => $v) {
            DB::table('settings')->updateOrInsert(['key' => $k], [
                'value'      => $v ?? '',
                'updated_at' => now(),
                'created_at' => now(),
            ]);
        }

        // Volvemos a la misma pestaña
        return redirect()->route('admin', ['tab' => 'settings'])->with('ok', 'Configuración guardada');
    }

}
