<?php

namespace Suggestions;

use App\Http\Controllers\Controller;

/**
 * Class SuggestionController
 * @package Suggestions
 */
class SuggestionController extends Controller
{
    use SuggestionResponse;

    /** @var SuggestionService */
    private SuggestionService $suggestionService;

    /**
     * SuggestionController constructor.
     * @param SuggestionService $suggestionService
     */
    public function __construct(SuggestionService $suggestionService)
    {
        $this->suggestionService = $suggestionService;
    }

    /**
     * @param SuggestionRequest $request
     * @return mixed
     */
    public function index(SuggestionRequest $request)
    {
        $result = $this->suggestionService->index($request->validated());

        return $this->response($result['response'], $result['status']);
    }

    /**
     * @param SuggestionRequest $request
     * @return mixed
     */
    public function store(SuggestionRequest $request)
    {
        $result = $this->suggestionService->store($request->validated());

        return $this->response($result['response'], $result['status']);
    }

    /**
     * @param Suggestion $suggestion
     * @return mixed
     */
    public function show(Suggestion $suggestion)
    {
        return $this->response($suggestion->load(\request('with') ?? [])->toArray());
    }

    /**
     * @param SuggestionRequest $request
     * @param Suggestion $suggestion
     * @return mixed
     */
    public function update(SuggestionRequest $request, Suggestion $suggestion)
    {
        $result = $this->suggestionService->update($suggestion, $request->validated());

        return $this->response($result['response'], $result['status']);
    }

    /**
     * @param Suggestion $suggestion
     * @return mixed
     */
    public function destroy(Suggestion $suggestion)
    {
        $result = $this->suggestionService->destroy($suggestion);

        return $this->response($result['response'], $result['status']);
    }
}
