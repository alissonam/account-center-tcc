<?php

namespace Sugestions;

use App\Http\Services\Service;

/**
 * Class SugestionService
 * @package Sugestions
 */
class SugestionService extends Service
{
    /**
     * @param array $filters
     * @return array
     */
    public function index(array $filters)
    {
        $sugestionsQuery = SugestionRepository::index($filters);

        return self::buildReturn(
            $sugestionsQuery
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

        $sugestion = Sugestion::create($data);

        return self::buildReturn($sugestion);
    }

    /**
     * @param Sugestion $sugestion
     * @param array $data
     * @return array
     */
    public function update(Sugestion $sugestion, array $data)
    {
        $sugestion->update($data);

        return self::buildReturn($sugestion);
    }

    /**
     * @param Sugestion $sugestion
     * @return array
     */
    public function destroy(Sugestion $sugestion)
    {
        $sugestion->delete();

        return self::buildReturn();
    }
}
