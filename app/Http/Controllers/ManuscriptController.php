<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubmitManuscriptRequest;
use App\Models\Manuscript;
use App\Services\ManuscriptService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ManuscriptController extends Controller
{
    protected ManuscriptService $manuscriptService;

    // Inject ManuscriptService lewat Constructor
    public function __construct(ManuscriptService $manuscriptService)
    {
        $this->manuscriptService = $manuscriptService;
    }

    /**
     * API Contract 2.1: Submit Naskah (POST /api/manuscripts)
     */
    public function store(SubmitManuscriptRequest $request): JsonResponse
    {
        $manuscript = $this->manuscriptService->createManuscript(
            $request->validated(),
            $request->user()->id
        );

        return response()->json([
            'success' => true,
            'message' => 'Naskah berhasil diajukan',
            'data'    => $manuscript,
        ], 201);
    }

    /**
     * API Contract 2.2: Tracking Naskah Pribadi (GET /api/manuscripts)
     */
    public function index(Request $request): JsonResponse
    {
        $manuscripts = Manuscript::with('author')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar naskah pribadi berhasil diambil',
            'data'    => $manuscripts,
        ], 200);
    }
}
