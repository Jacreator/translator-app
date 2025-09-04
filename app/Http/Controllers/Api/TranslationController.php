<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Dtos\TranslationRequestDTO;
use App\Http\Controllers\Controller;
use App\Services\TranslationService;
use App\Http\Requests\StoreTranslationRequest;
use App\Http\Resources\TranslationRequestResource;

/**
 * Handles translation requests API endpoints.
 */
class TranslationController extends Controller
{
    /**
     * TranslationController constructor.
     *
     * @param TranslationService $translationService The service handling 
     *                                               translation logic.
     */
    public function __construct(
        private readonly TranslationService $translationService
    ) {}

    /**
     * Stores a new translation request.
     *
     * @param StoreTranslationRequest $request The validated translation request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreTranslationRequest $request): JsonResponse
    {
        $dto = TranslationRequestDTO::fromRequest($request->validated());

        $translationRequest = $this->translationService
            ->createTranslationRequest($dto);

        return response()->json(
            [
                'success' => true,
                'message' => 'Translation request created successfully',
                'data' => new TranslationRequestResource($translationRequest)
            ],
            201
        );
    }

    /**
     * Displays a specific translation request by its ID.
     *
     * @param int $id The ID of the translation request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $translationRequest = $this->translationService->getTranslationRequest($id);

        if (!$translationRequest) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Translation request not found'
                ],
                404
            );
        }

        return response()->json(
            [
                'success' => true,
                'data' => new TranslationRequestResource($translationRequest)
            ]
        );
    }

    /**
     * Lists translation requests with optional filters.
     *
     * @param Request $request The HTTP request containing filter parameters.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['status', 'target_language']);
        $translationRequests = $this->translationService
            ->getTranslationRequests($filters);

        return response()->json(
            [
                'success' => true,
                'data' => TranslationRequestResource::collection(
                    $translationRequests
                ),
                'meta' => [
                    'current_page' => $translationRequests->currentPage(),
                    'total' => $translationRequests->total(),
                    'per_page' => $translationRequests->perPage(),
                ]
            ]
        );
    }
}
