<?php

namespace App\Services;

use App\Models\Audit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditService
{
    /**
     * Registra un evento de auditoría
     */
    public static function log(string $action, string $description, ?string $modelType = null, ?int $modelId = null, ?array $changes = null)
    {
        if (!Auth::check()) {
            return;
        }

        Audit::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'description' => $description,
            'changes' => $changes,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    /**
     * Registra un login
     */
    public static function login(int $userId)
    {
        Audit::create([
            'user_id' => $userId,
            'action' => 'login',
            'description' => 'Inicio de sesión',
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    /**
     * Registra un logout
     */
    public static function logout()
    {
        if (Auth::check()) {
            Audit::create([
                'user_id' => Auth::id(),
                'action' => 'logout',
                'description' => 'Cierre de sesión',
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
            ]);
        }
    }
}
