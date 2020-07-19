<?php

namespace App\Http\Controllers\API;

use App\Constants\TransactionConstant;
use App\Constants\RecordConstant;
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
        $this->transactionService = app(TransactionService::class);
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
        $transactions = $this->transactionService->listTransactions($request);
        $transactions->load(TransactionConstant::BASIC_RELATIONS);

        return $this->sendResponse(Transaction::collection($transactions), 'Transactions retrieved successfully');
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
            $transaction->load(TransactionConstant::WITH_CHPOS_RELATIONS);
            DB::commit();

            return $this->sendResponse(new Transaction($transaction), 'New Transaction successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Store a newly created Transaction in storage.
     * POST /transactions
     *
     * @param CreateTransactionAPIRequest $request
     *
     * @return Response
     */
    public function newTransactionWithoutEarnChops(CreateTransactionAPIRequest $request)
    {
        $input = $request->all();

        DB::beginTransaction();
        try {
            $transaction = $this->transactionManager->newTransactionWithoutEarnChops($input);
            $transaction->load(TransactionConstant::WITH_CHPOS_RELATIONS);
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
        $transaction = $this->transactionService->findTransaction($id);
        $transaction->load(TransactionConstant::BASIC_RELATIONS);

        return $this->sendResponse(new Transaction($transaction), 'Transaction retrieved successfully');
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
