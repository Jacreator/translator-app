<?php

namespace App\Http\Controllers\Api;

use App\Dtos\TranslationRequestDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTranslationRequest;
use App\Http\Resources\TranslationRequestResource;
use App\Services\TranslationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     title="N-Translator API",
 *     version="1.0.0",
 *     description="API for managing translation requests."
 * )
 *
 * @OA\Schema(
 *     schema="TranslationResponse",
 *     type="object",
 *     title="Translation Response",
 *     properties={
 *
 *         @OA\Property(property="success", type="boolean", example=true),
 *         @OA\Property(property="message", type="string", nullable=true, example="Operation successful"),
 *         @OA\Property(property="data", ref="#/components/schemas/Translation")
 *     }
 * )
 * Handles translation requests API endpoints.
 */
class TranslationController extends Controller
{
    /**
     * TranslationController constructor.
     *
     * @param  TranslationService  $translationService  The service handling
     *                                                  translation logic.
     */
    public function __construct(
        private readonly TranslationService $translationService
    ) {}

    /**
     * Stores a new translation request.
     *
     * @OA\Post(
     *     path="/api/v1/translations",
     *     summary="Create a new translation request",
     *     tags={"Translation"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(ref="#/components/schemas/TranslationRequest")
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Translation request created successfully",
     *
     *         @OA\JsonContent(ref="#/components/schemas/TranslationResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     *
     * @param  StoreTranslationRequest  $request  The validated translation request.
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
                'data' => new TranslationRequestResource($translationRequest),
            ],
            201
        );
    }

    /**
     * Displays a specific translation request by its ID.
     *
     * @OA\Get(
     *     path="/api/v1/translations/{id}",
     *     summary="Get a translation request by ID",
     *     tags={"Translation"},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the translation request",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Translation request found",
     *
     *         @OA\JsonContent(ref="#/components/schemas/TranslationResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Translation request not found"
     *     )
     * )
     *
     * @param  int  $id  The ID of the translation request.
     */
    public function show(int $id): JsonResponse
    {
        $translationRequest = $this->translationService->getTranslationRequest($id);

        if (! $translationRequest) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Translation request not found',
                ],
                404
            );
        }

        return response()->json(
            [
                'success' => true,
                'data' => new TranslationRequestResource($translationRequest),
            ]
        );
    }

    /**
     * Lists translation requests with optional filters.
     *
     * @OA\Get(
     *     path="/api/v1/translations",
     *     summary="List translation requests",
     *     tags={"Translation"},
     *
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         required=false,
     *         description="Filter by status",
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Parameter(
     *         name="target_language",
     *         in="query",
     *         required=false,
     *         description="Filter by target language",
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="List of translation requests",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *
     *                 @OA\Items(ref="#/components/schemas/Translation")
     *             ),
     *
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="total", type="integer", example=20),
     *                 @OA\Property(property="per_page", type="integer", example=15)
     *             )
     *         )
     *     )
     * )
     *
     * @param  Request  $request  The HTTP request containing filter parameters.
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
                ],
            ]
        );
    }
}
