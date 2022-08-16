<?php

namespace Sugestions;

use App\Http\Controllers\Controller;

/**
 * Class SugestionController
 * @package Sugestions
 */
class SugestionController extends Controller
{
    use SugestionResponse;

    /** @var SugestionService */
    private SugestionService $sugestionService;

    /**
     * SugestionController constructor.
     * @param SugestionService $sugestionService
     */
    public function __construct(SugestionService $sugestionService)
    {
        $this->sugestionService = $sugestionService;
    }

    /**
     * @param SugestionRequest $request
     * @return mixed
     */
    public function index(SugestionRequest $request)
    {
        $result = $this->sugestionService->index($request->validated());

        return $this->response($result['response'], $result['status']);
    }

    /**
     * @param SugestionRequest $request
     * @return mixed
     */
    public function store(SugestionRequest $request)
    {
        $result = $this->sugestionService->store($request->validated());

        return $this->response($result['response'], $result['status']);
    }

    /**
     * @param Sugestion $sugestion
     * @return mixed
     */
    public function show(Sugestion $sugestion)
    {
        return $this->response($sugestion->load(\request('with') ?? [])->toArray());
    }

    /**
     * @param SugestionRequest $request
     * @param Sugestion $sugestion
     * @return mixed
     */
    public function update(SugestionRequest $request, Sugestion $sugestion)
    {
        $result = $this->sugestionService->update($sugestion, $request->validated());

        return $this->response($result['response'], $result['status']);
    }

    /**
     * @param Sugestion $sugestion
     * @return mixed
     */
    public function destroy(Sugestion $sugestion)
    {
        $result = $this->sugestionService->destroy($sugestion);

        return $this->response($result['response'], $result['status']);
    }
}
