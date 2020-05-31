<?php

namespace App\Services;

use App\Constants\PaymentTypeConstant;
use App\Exceptions\AlreadyVoidedException;
use App\Exceptions\TransactionDuplicateException;
use App\Exceptions\ResourceNotFoundException;
use App\Criterias\LimitOffsetCriteria;
use App\Criterias\RequestDateRangeCriteria;
use App\Criterias\TransactionValidCriteria;
use App\Repositories\TransactionRepository;
use App\Repositories\TransactionItemRepository;
use App\Repositories\TransactionItemCondimentRepository;
use App\Repositories\ChopRecordRepository;
use App\Repositories\ChopRepository;
use App\Repositories\RankRepository;
use App\Repositories\MemberRepository;
use App\Repositories\BranchRepository;
use App\Repositories\EarnChopRuleRepository;
use App\Models\Member;
use App\Helpers\TransactionUsedChopRuleHelper;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Cache;
use DB;
use Arr;
use Str;

class TransactionService
{
    /** @var  TransactionRepository */
    private $transactionRepository;
    /** @var  ChopRepository */
    private $chopRepository;

    public function __construct($customer = '')
    {
        $this->transactionRepository = app(TransactionRepository::class);
        $this->transactionItemRepository = app(TransactionItemRepository::class);
        $this->transactionItemCondimentRepository = app(TransactionItemCondimentRepository::class);
        $this->chopRepository = app(ChopRepository::class);
        $this->chopRecordRepository = app(ChopRecordRepository::class);
        $this->earnChopRuleRepository = app(EarnChopRuleRepository::class);
        $this->memberRepository = app(MemberRepository::class);
        $this->branchRepository = app(BranchRepository::class);
        $this->chopRecordRepository = app(ChopRecordRepository::class);
    }

    public function listTransactions(Request $request)
    {
        $this->transactionRepository->pushCriteria(new RequestDateRangeCriteria($request));
        $this->transactionRepository->pushCriteria(new RequestCriteria($request));
        $this->transactionRepository->pushCriteria(new LimitOffsetCriteria($request));
        $transactions = $this->transactionRepository->all();

        return $transactions;
    }

    public function findTransaction($id)
    {
        $transaction = $this->transactionRepository->findWithoutFail($id);
        if (!$transaction) {
            throw new ResourceNotFoundException('Transaction not exist');
        }
        return $transaction;
    }

    public function findByOrderId($orderId)
    {
        $transaction = $this->transactionRepository->findByOrderId($orderId);
        return $transaction;
    }

    public function getByMemberId($memberId)
    {
        $transaction = $this->transactionRepository->getByMemberId($memberId);
        return $transaction;
    }

    public function newTransaction($attributes)
    {
        $memberId = $attributes['member_id'];
        $branchId = $attributes['branch_id'];
        $chops = $attributes['chops'];
        $transactionData = $attributes['transaction'];
        $transactionItems = collect(Arr::get($transactionData, 'items', []));
        $orderId = $transactionData['order_id'];
        $order = $this->findByOrderId($orderId);
        if ($order) {
            throw new TransactionDuplicateException($orderId . ' is already exist');
        }

        $transaction = $this->transactionRepository->createTransaction([
            'order_id' => $orderId,
            'member_id' => $memberId,
            'branch_id' => $branchId,
            'destination' => Arr::get($transactionData, 'destination', ''),
            'payment_type' => Arr::get($transactionData, 'payment_type', ''),
            'clerk' => Arr::get($transactionData, 'clerk', ''),
            'items_count' => count($transactionItems),
            'amount' => Arr::get($transactionData, 'amount', 0),
            'remark' => Arr::get($transactionData, 'remark', ''),
            'discount' => Arr::get($transactionData, 'discount', 0),
            'chops' => $chops,
            'consume_chops' => Arr::get($transactionData, 'consume_chops', 0),
            'status' => 1,
            'transaction_time' => Arr::get($transactionData, 'transaction_time', Carbon::now()),
        ], $transactionItems);

        $newTransactionItems = collect([]);
        $newTransactionItemCondiments = collect([]);

        // make transaction item
        // TODO: transaction data check
        $transactionItems = $transactionItems->map(function ($item, $key) use ($transaction, $newTransactionItems, $newTransactionItemCondiments) {
            $itemId = (string) Str::uuid();
            $condiments = collect($item['condiments'] ?: []);
            
            $newTransactionItems->push([
                'id' => $itemId,
                'item_no' => $item['no'],
                'item_name' => $item['name'],
                'price' => $item['price'],
                'subtotal' => $item['subtotal'],
                'quantity' => $item['qty'],
                'transaction_id' => $transaction->id,
            ]);
            $condiments->map(function ($condiment, $key) use ($newTransactionItemCondiments, $itemId) {
                $newTransactionItemCondiments->push([
                    'id' => (string) Str::uuid(),
                    'no' => $condiment['no'],
                    'name' => $condiment['name'],
                    'price' => $condiment['price'],
                    'subtotal' => $condiment['subtotal'],
                    'quantity' => $condiment['qty'],
                    'transaction_item_id' => $itemId,
                ]);
            });
        });

        $this->transactionItemRepository->createMany($newTransactionItems->toArray());
        $this->transactionItemCondimentRepository->createMany($newTransactionItemCondiments->toArray());

        return $transaction;
    }

    public function voidTransaction($id)
    {
        $transaction = $this->transactionRepository->find($id);
        if (!$transaction->isValid()) {
            throw new AlreadyVoidedException('Transaction already been voided', $transaction);
        }
        $voidTransaction = $this->transactionRepository->voidTransaction($transaction->id);

        return $voidTransaction;
    }
}
