<?php

namespace App\Http\Requests\Slot;

use App\Models\User;
use App\Services\Slot\SlotWebhookValidator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WebhookRequest extends FormRequest
{
   private ?User $member;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $transaction_rules = [];

        if (in_array($this->getMethodName(), ['getbalance', 'buyin', 'buyout'])) {
            $transaction_rules['Transactions'] = ['nullable'];
            if ($this->getMethodName() !== 'getbalance') {
                $transaction_rules['Transaction'] = ['required'];
            }
        } else {
            $transaction_rules['Transactions'] = ['required'];
        }

        return [
            'MemberName' => ['required'],
            'OperatorCode' => ['required'],
            'ProductID' => ['required'],
            'MessageID' => ['required'],
            'RequestTime' => ['required'],
            'Sign' => ['required'],
            ...$transaction_rules,
        ];
    }

//     public function rules(): array
// {
//     $transaction_rules = [];

//     if (in_array($this->getMethodName(), ['getbalance', 'buyin', 'buyout'])) {
//         $transaction_rules['Transactions'] = ['nullable'];
//         if ($this->getMethodName() !== 'getbalance') {
//             $transaction_rules['Transaction'] = ['required'];
//         }
//     } else {
//         $transaction_rules['Transactions'] = ['required', 'array'];
//         $transaction_rules['Transactions'] = ['required', 'array'];
//         $transaction_rules['Transactions.*.TransactionID'] = ['required', 'string', 'regex:/^[a-zA-Z0-9_-]+$/'];
//         $transaction_rules['Transactions.*.GameType'] = ['required'];
//         $transaction_rules['Transactions.*.ProductID'] = ['required'];
//         //$transaction_rules['Transactions.*.MemberName'] = ['required', 'string'];
//     }

//     return [
//         'MemberName' => ['required'],
//         'OperatorCode' => ['required'],
//         'ProductID' => ['required'],
//         'MessageID' => ['required'],
//         'RequestTime' => ['required'],
//         'Sign' => ['required'],
//         ...$transaction_rules,
//     ];
// }

    public function check()
    {
        $validator = SlotWebhookValidator::make($this)->validate();

        return $validator;
    }

    public function getMember()
    {
        if (! isset($this->member)) {
            $this->member = User::where('user_name', $this->getMemberName())->first();
        }

        return $this->member;
    }

    public function getMemberName()
    {
        return $this->get('MemberName');
    }

    public function getProductID()
    {
        return $this->get('ProductID');
    }

    public function getMessageID()
    {
        return $this->get('MessageID');
    }

    public function getMethodName()
    {
        return strtolower(str($this->url())->explode('/')->last());
    }

    public function getOperatorCode()
    {
        return $this->get('OperatorCode');
    }

    public function getRequestTime()
    {
        return $this->get('RequestTime');
    }

    public function getSign()
    {
        return $this->get('Sign');
    }

    public function getTransactions()
{
    $transactions = $this->get('Transactions', []);

    if ($transactions) {
        return $transactions;
    }

    $transaction = $this->get('Transaction', []);

    if ($transaction) {
        return [$transaction];
    }

    return []; // Always return an array, even if empty
}
}