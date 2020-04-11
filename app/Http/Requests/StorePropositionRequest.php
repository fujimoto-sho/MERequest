<?php

namespace App\Http\Requests;

use App\Rules\PropositionNumberMax;
use Illuminate\Foundation\Http\FormRequest;

/**
 * フォームリクエスト 案件
 *
 * ここの設定を元にバリデーションを行う
 *
 * @package App\Http\Requests
 */
class StorePropositionRequest extends FormRequest
{
    /**
     * 認証
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * バリデーションルール
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'      => ['required', 'string', 'max:255'],
            'type'       => ['required', 'integer', 'between:0,1'],
            'number_min' => [
                'required',
                'integer',
                'min:1',
                new PropositionNumberMax($this->request->all()),
            ],
            'number_max' => [
                'required',
                'integer',
                'min:1',
                'gte:number_min',
                new PropositionNumberMax($this->request->all()),
            ],
            'recruiting_count' => ['required', 'integer', 'min:1'],
            'status'           => ['required', 'integer', 'between:0,1'],
            'content'          => ['required', 'string'],
        ];
    }
}
