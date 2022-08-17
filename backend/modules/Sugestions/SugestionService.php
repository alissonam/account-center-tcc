<?php

namespace Suggestions;

use App\Http\Services\Service;

/**
 * Class SuggestionService
 * @package Suggestions
 */
class SuggestionService extends Service
{
    /**
     * @param array $filters
     * @return array
     */
    public function index(array $filters)
    {
        $suggestionsQuery = SuggestionRepository::index($filters);

        return self::buildReturn(
            $suggestionsQuery
                ->orderBy('id', 'desc')
                ->with(\request()->with ?? [])
                ->paginate(\request()->perPage)
        );
    }

    /**
     * @param array $data
     * @return array
     */
    public function store(array $data)
    {

        /** @var User $loggedUser */
        $loggedUser = auth()->user();

        if ( !$loggedUser ) {
            throw self::exception([
                'message' => 'Realize login!'
            ], 403);
        }

        $userId = $loggedUser->id;

        self::prepareData($data, [
            'user_id'    => fn($value) => $userId,
        ], true);

        $suggestion = Suggestion::create($data);

        return self::buildReturn($suggestion);
    }

    /**
     * @param Suggestion $suggestion
     * @param array $data
     * @return array
     */
    public function update(Suggestion $suggestion, array $data)
    {
        $suggestion->update($data);

        return self::buildReturn($suggestion);
    }

    /**
     * @param Suggestion $suggestion
     * @return array
     */
    public function destroy(Suggestion $suggestion)
    {
        $suggestion->delete();

        return self::buildReturn();
    }
}
