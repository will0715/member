<?php

namespace App\Http\Controllers\API;

use App\Exceptions\TransactionDuplicateException;
use App\Http\Requests\API\CreateTransactionAPIRequest;
use App\Http\Requests\API\UpdateTransactionAPIRequest;
use App\Models\Transaction;
use App\Repositories\TransactionRepository;
use App\Repositories\MemberRepository;
use App\Repositories\BranchRepository;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Criterias\LimitOffsetCriteria;
use Response;
use Log;

/**
 * Class TransactionController
 * @package App\Http\Controllers\API
 */

class TransactionAPIController extends AppBaseController
{
    /** @var  TransactionRepository */
    private $transactionRepository;

    private $transactionService;

    public function __construct(TransactionRepository $transactionRepo)
    {
        $this->transactionRepository = $transactionRepo;
        $this->memberRepository = app(MemberRepository::class);
        $this->branchRepository = app(BranchRepository::class);
        $this->transactionService = new TransactionService();
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
        $memberId = $request->get('member_id');
        $branchId = $request->get('branch_id');
        $chops = $request->get('chops');
        $customer = $this->getCustomer($request);

        $member = $this->memberRepository->findByPhone($memberId);
        if (!$member) {
            return $this->sendError('member not exist', 404);
        }
    
        $branch = $this->branchRepository->findByBranchId($branchId);
        if (!$branch) {
            return $this->sendError('branch not exist', 404);
        }

        try {
            $this->transactionService->setCustomer($customer);
            $this->transactionService->setMember($member);
            $this->transactionService->setBranch($branch);

            $transaction = $this->transactionService->newTransaction($input);
        } catch (TransactionDuplicateException $e) {
            Log::warning($e);
            return $this->sendError($e->getMessage(), 422);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->sendError('New Transaction Failed', 500);
        }

        return $this->sendResponse($transaction->toArray(), 'Transaction saved successfully');
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
     * Update the specified Transaction in storage.
     * PUT/PATCH /transactions/{id}
     *
     * @param int $id
     * @param UpdateTransactionAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTransactionAPIRequest $request)
    {
        $input = $request->all();

        /** @var Transaction $transaction */
        $transaction = $this->transactionRepository->find($id);

        if (empty($transaction)) {
            return $this->sendError('Transaction not found');
        }

        $transaction = $this->transactionRepository->update($input, $id);

        return $this->sendResponse($transaction->toArray(), 'Transaction updated successfully');
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
        $customer = $this->getCustomer($request);
        try {
            $this->transactionService->setCustomer($customer);

            $this->transactionService->voidTransaction($id);
        } catch (AlreadyVoidedException $e) {
            Log::error($e);
            return $this->sendError($e->getMessage(), 409);
        } catch (\Exception $e) {
            Log::error($e);
            dd($e);
            return $this->sendError('Consume Chops failed', 500);
        }

        return $this->sendSuccess('Transaction deleted successfully');
    }
}
