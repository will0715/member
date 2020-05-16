<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Exceptions\TransactionDuplicateException;
use App\Http\Requests\API\CreateTransactionAPIRequest;
use App\Http\Requests\API\UpdateTransactionAPIRequest;
use App\Http\Resources\Transaction;
use App\Services\TransactionService;
use App\ServiceManagers\TransactionManager;
use Illuminate\Http\Request;
use Response;
use Log;
use DB;

/**
 * Class TransactionController
 * @package App\Http\Controllers\API
 */

class TransactionAPIController extends AppBaseController
{

    private $transactionService;

    public function __construct()
    {
        $this->transactionService = new TransactionService();
        $this->transactionManager = app(TransactionManager::class);
    }

    /**
     * Display a listing of the Transaction.
     * GET|HEAD /transactions
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->transactionRepository->pushCriteria(new RequestCriteria($request));
        $this->transactionRepository->pushCriteria(new LimitOffsetCriteria($request));
        $transactions = $this->transactionRepository->all();

        return $this->sendResponse($transactions->toArray(), 'Transactions retrieved successfully');
    }

    /**
     * Store a newly created Transaction in storage.
     * POST /transactions
     *
     * @param CreateTransactionAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateTransactionAPIRequest $request)
    {
        $input = $request->all();

        DB::beginTransaction();
        try {
            $transaction = $this->transactionManager->newTransaction($input);
            DB::commit();

            return $this->sendResponse(new Transaction($transaction), 'New Transaction successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Display the specified Transaction.
     * GET|HEAD /transactions/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Transaction $transaction */
        $transaction = $this->transactionRepository->find($id);

        if (empty($transaction)) {
            return $this->sendError('Transaction not found');
        }

        return $this->sendResponse($transaction->toArray(), 'Transaction retrieved successfully');
    }

    /**
     * Remove the specified Transaction from storage.
     * DELETE /transactions/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id, Request $request)
    {
        $input = $request->all();

        DB::beginTransaction();
        try {
            $chops = $this->transactionManager->voidTransaction($id, $input);
            DB::commit();

            return $this->sendResponse(new Transaction($chops), 'Void Transaction successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
