<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateTransactionItemAPIRequest;
use App\Http\Requests\API\UpdateTransactionItemAPIRequest;
use App\Models\TransactionItem;
use App\Repositories\TransactionItemRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Criterias\LimitOffsetCriteria;
use Response;

/**
 * Class TransactionItemController
 * @package App\Http\Controllers\API
 */

class TransactionItemAPIController extends AppBaseController
{
    /** @var  TransactionItemRepository */
    private $transactionItemRepository;

    public function __construct(TransactionItemRepository $transactionItemRepo)
    {
        $this->transactionItemRepository = $transactionItemRepo;
    }

    /**
     * Display a listing of the TransactionItem.
     * GET|HEAD /transactionItems
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->transactionItems->pushCriteria(new RequestCriteria($request));
        $this->transactionItems->pushCriteria(new LimitOffsetCriteria($request));
        $chops = $this->transactionItems->all();

        return $this->sendResponse($transactionItems->toArray(), 'Transaction Items retrieved successfully');
    }

    /**
     * Store a newly created TransactionItem in storage.
     * POST /transactionItems
     *
     * @param CreateTransactionItemAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateTransactionItemAPIRequest $request)
    {
        $input = $request->all();

        $transactionItem = $this->transactionItemRepository->create($input);

        return $this->sendResponse($transactionItem->toArray(), 'Transaction Item saved successfully');
    }

    /**
     * Display the specified TransactionItem.
     * GET|HEAD /transactionItems/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var TransactionItem $transactionItem */
        $transactionItem = $this->transactionItemRepository->find($id);

        if (empty($transactionItem)) {
            return $this->sendError('Transaction Item not found');
        }

        return $this->sendResponse($transactionItem->toArray(), 'Transaction Item retrieved successfully');
    }

    /**
     * Update the specified TransactionItem in storage.
     * PUT/PATCH /transactionItems/{id}
     *
     * @param int $id
     * @param UpdateTransactionItemAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTransactionItemAPIRequest $request)
    {
        $input = $request->all();

        /** @var TransactionItem $transactionItem */
        $transactionItem = $this->transactionItemRepository->find($id);

        if (empty($transactionItem)) {
            return $this->sendError('Transaction Item not found');
        }

        $transactionItem = $this->transactionItemRepository->update($input, $id);

        return $this->sendResponse($transactionItem->toArray(), 'TransactionItem updated successfully');
    }

    /**
     * Remove the specified TransactionItem from storage.
     * DELETE /transactionItems/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var TransactionItem $transactionItem */
        $transactionItem = $this->transactionItemRepository->find($id);

        if (empty($transactionItem)) {
            return $this->sendError('Transaction Item not found');
        }

        $transactionItem->delete();

        return $this->sendSuccess('Transaction Item deleted successfully');
    }
}
